#!/usr/bin/env node
/**
 * Render data/menu.json into menu.html as static HTML, plus the Menu JSON-LD.
 *
 * The menu used to be built in the browser from a JavaScript array, so the page
 * shipped an empty <div> and every visitor waited on script execution before
 * seeing a single dish. Now the markup is generated here and served complete
 * from Cloudflare's edge; the only JavaScript left on the page is the category
 * tabs and the "add to order" buttons.
 *
 *   npm run build:menu
 *
 * Images resolve in three steps, matching the agreed strategy:
 *   1. assets/images/dining/menu/<slug>.webp  - local, 1200x750, with a 600w variant
 *   2. an Unsplash CDN photo for the section  - when no local asset exists
 *   3. an inline SVG placeholder              - if the chosen source fails to load
 */
const fs = require('fs');
const path = require('path');

const ROOT = path.join(__dirname, '..');
const IMG_DIR = path.join(ROOT, 'assets', 'images', 'dining', 'menu');
const IMG_BASE = 'assets/images/dining/menu/';
const UNSPLASH = 'https://images.unsplash.com/';

const START = '<!-- @menu:start -->';
const END = '<!-- @menu:end -->';

const data = JSON.parse(fs.readFileSync(path.join(ROOT, 'data', 'menu.json'), 'utf8'));
const local = new Set(
  fs.existsSync(IMG_DIR) ? fs.readdirSync(IMG_DIR).map((f) => f.replace(/(-600)?\.webp$/, '')) : []
);

const esc = (s) =>
  String(s).replace(/&(?!(?:[a-zA-Z]+|#\d+);)/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

const money = (p, cur) =>
  typeof p === 'number'
    ? `${cur} ${p.toLocaleString('en-KE')}`
    : `${cur} ${String(p).split('/').map((x) => Number(x.trim()).toLocaleString('en-KE')).join(' / ')}`;

/** Lowest number in a price, for schema.org and the price-range summary. */
const lowest = (p) => (typeof p === 'number' ? p : Math.min(...String(p).split('/').map((x) => Number(x.trim()))));
const highest = (p) => (typeof p === 'number' ? p : Math.max(...String(p).split('/').map((x) => Number(x.trim()))));

let stats = { local: 0, stock: 0 };

function picture(item, section) {
  const alt = esc(`${item.name} served at Sidai Resort in Naroosura, Narok Kenya`);
  if (local.has(item.img)) {
    stats.local++;
    return (
      `<img src="${IMG_BASE}${item.img}.webp"\n` +
      `                             srcset="${IMG_BASE}${item.img}-600.webp 600w, ${IMG_BASE}${item.img}.webp 1200w"\n` +
      `                             sizes="(min-width: 1024px) 24rem, (min-width: 640px) 45vw, 92vw"\n` +
      `                             alt="${alt}" width="1200" height="750" loading="lazy" decoding="async"\n` +
      `                             onerror="menuImageFallback(this)">`
    );
  }
  stats.stock++;
  const src = `${UNSPLASH}${section.stock}?auto=format&fit=crop&w=1200&h=750&q=80`;
  return (
    `<img src="${src}"\n` +
    `                             alt="${alt}" width="1200" height="750" loading="lazy" decoding="async"\n` +
    `                             onerror="menuImageFallback(this)">`
  );
}

const html = data.sections
  .map((section) => {
    const cards = section.items
      .map(
        (item) => `                <article class="menu-card" data-menu-card>
                    <div class="menu-card-media">
                        ${picture(item, section)}
                    </div>
                    <div class="menu-card-body">
                        <div class="menu-card-head">
                            <h3 class="menu-card-title">${esc(item.name)}</h3>
                            <p class="menu-card-price"><span class="sr-only">Price </span>${money(item.price, data.currency)}</p>
                        </div>
                        <p class="menu-card-desc">${esc(item.desc)}</p>
                        <button type="button" class="menu-card-add" data-add-item="${esc(item.name)}">Add to order</button>
                    </div>
                </article>`
      )
      .join('\n');

    return `            <section id="${section.id}" class="menu-section menu-animate-section" data-menu-section="${section.id}">
                <div class="menu-section-header">
                    <p class="menu-section-eyebrow">Sidai Resort Menu</p>
                    <h2>${esc(section.title)}</h2>
                    <p class="menu-section-subtitle">${esc(section.subtitle)}</p>
                </div>
                <div class="menu-card-grid">
${cards}
                </div>
            </section>`;
  })
  .join('\n\n');

const tabs = data.sections
  .map(
    (s, i) =>
      `                    <a class="menu-tab${i === 0 ? ' is-active' : ''}" href="#${s.id}" data-tab-target="${s.id}">${esc(s.title)}</a>`
  )
  .join('\n');

// ---- write the rendered sections and the tab strip -------------------------
const menuPath = path.join(ROOT, 'menu.html');
let page = fs.readFileSync(menuPath, 'utf8');

if (!page.includes(START) || !page.includes(END)) {
  console.error(`menu.html is missing the ${START} / ${END} markers.`);
  process.exit(1);
}
page = page.replace(
  new RegExp(`${START}[\\s\\S]*?${END}`),
  () => `${START}\n${html}\n            ${END}`
);
page = page.replace(
  /(<div class="menu-tabs-track" id="menu-tabs">)[\s\S]*?(<\/div>)/,
  (_m, open, close) => `${open}\n${tabs}\n                ${close}`
);

// ---- regenerate the Restaurant / Menu structured data ----------------------
const all = data.sections.flatMap((s) => s.items);
const ld = JSON.parse(
  page.match(/<script type="application\/ld\+json">([\s\S]*?)<\/script>/)[1]
);
ld.priceRange = `KSh ${Math.min(...all.map((i) => lowest(i.price)))} - KSh ${Math.max(
  ...all.map((i) => highest(i.price))
).toLocaleString('en-KE')}`;
ld.hasMenu = {
  '@type': 'Menu',
  name: 'Sidai Resort Menu',
  url: 'https://sidairesort.com/menu',
  inLanguage: 'en',
  hasMenuSection: data.sections.map((s) => ({
    '@type': 'MenuSection',
    name: s.title,
    description: s.subtitle,
    hasMenuItem: s.items.map((i) => ({
      '@type': 'MenuItem',
      name: i.name,
      description: i.desc,
      offers: {
        '@type': 'Offer',
        price: String(lowest(i.price)),
        priceCurrency: 'KES',
        availability: 'https://schema.org/InStock',
      },
    })),
  })),
};
page = page.replace(
  /<script type="application\/ld\+json">[\s\S]*?<\/script>/,
  () => `<script type="application/ld+json">${JSON.stringify(ld)}</script>`
);

fs.writeFileSync(menuPath, page, 'utf8');

console.log(
  `menu.html: ${data.sections.length} sections, ${all.length} items ` +
    `(${stats.local} local images, ${stats.stock} Unsplash fallbacks)`
);
console.log(`  priceRange ${ld.priceRange}`);
