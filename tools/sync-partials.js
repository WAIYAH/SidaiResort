#!/usr/bin/env node
/**
 * Push the shared header and footer from partials/ into every page.
 *
 * The site is plain static HTML with no template engine, so the header and
 * footer had been copy-pasted and had drifted: ten distinct footers across
 * eleven pages, four of which still showed a gold "S" placeholder instead of
 * the logo. Editing one phone number meant editing thirteen files.
 *
 *   npm run sync          rewrite each page from partials/
 *   npm run sync -- --check   report drift and exit 1, for CI or a pre-push hook
 *
 * The homepage keeps its own header: its logo link returns to the top of the
 * page it is already on, and its aria-label says so.
 */
const fs = require('fs');
const path = require('path');

const ROOT = path.join(__dirname, '..');
const CHECK = process.argv.includes('--check');

const HEADER_RE = /<header id="site-header"[\s\S]*?<\/header>/;
const FOOTER_RE = /<footer\b[\s\S]*?<\/footer>/;

const read = (p) => fs.readFileSync(path.join(ROOT, p), 'utf8');

const footer = read('partials/footer.html').trimEnd();
const headerHome = read('partials/header-home.html').trimEnd();
const header = read('partials/header.html').trimEnd();

const pages = fs
  .readdirSync(ROOT)
  .filter((f) => f.endsWith('.html'))
  .sort();

let changed = 0;
const drifted = [];

for (const page of pages) {
  const before = read(page);
  let after = before;

  const wantHeader = page === 'index.html' ? headerHome : header;
  if (HEADER_RE.test(after)) after = after.replace(HEADER_RE, () => wantHeader);
  if (FOOTER_RE.test(after)) after = after.replace(FOOTER_RE, () => footer);

  if (after === before) continue;

  changed++;
  drifted.push(page);
  if (!CHECK) fs.writeFileSync(path.join(ROOT, page), after, 'utf8');
}

if (CHECK) {
  if (changed) {
    console.error(`${changed} page(s) differ from partials/: ${drifted.join(', ')}`);
    console.error('Run `npm run sync` to bring them back in line.');
    process.exit(1);
  }
  console.log(`All ${pages.length} pages match partials/.`);
} else {
  console.log(
    changed
      ? `Synced ${changed} of ${pages.length} page(s): ${drifted.join(', ')}`
      : `All ${pages.length} pages already match partials/.`
  );
}
