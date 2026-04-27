# Sidai Resort - Cloudflare Pages Hosting Guide

## Overview
This guide explains how to deploy the Sidai Resort website to **Cloudflare Pages**.

## Prerequisites
- Domain registered in Cloudflare: `sidairesort.com`
- Cloudflare account
- Git repository (GitHub, GitLab, or Gitbucket)

---

## Step 1: Connect Your Git Repository to Cloudflare

1. **Log in to Cloudflare Dashboard**
   - Go to https://dash.cloudflare.com/
   - Select your account

2. **Navigate to Pages**
   - In the left sidebar, click **Pages**
   - Click **Connect to Git**

3. **Authorize and Select Repository**
   - Authorize Cloudflare to access your Git provider
   - Select the `SidaiResort` repository

4. **Configure Build Settings**
   - **Project name**: `sidai-resort`
   - **Production branch**: `main` (or your default branch)
   - **Build command**: Leave empty (static site, no build needed)
   - **Build output directory**: `.` (root directory)
   - **Root directory**: Leave empty

5. **Environment Variables** (Optional)
   - Add any custom variables if needed
   - Leave empty for static site hosting

6. **Deploy**
   - Click **Save and Deploy**
   - Cloudflare will build and deploy your site

---

## Step 2: Point Your Domain to Cloudflare Pages

1. **Add Custom Domain**
   - In Cloudflare Pages project dashboard
   - Go to **Settings** → **Custom Domains**
   - Click **Add Custom Domain**

2. **Enter Your Domain**
   - Type: `sidairesort.com`
   - Cloudflare will verify ownership

3. **Set Nameservers**
   - Cloudflare will provide nameserver addresses
   - Update your domain registrar to use Cloudflare nameservers:
     - `ns1.cloudflare.com`
     - `ns2.cloudflare.com`
     - `ns3.cloudflare.com`
     - `ns4.cloudflare.com`

4. **Wait for DNS Propagation**
   - DNS changes can take 24-48 hours
   - Check status in Cloudflare dashboard

---

## Step 3: Configure DNS Records

1. **In Cloudflare Dashboard**
   - Go to **DNS** section
   - Add these records:

```
Type    Name            Content              TTL      Proxy Status
A       sidairesort.com Cloudflare IP       Auto     Proxied (Orange)
CNAME   www             sidairesort.com     Auto     Proxied (Orange)
```

2. **SSL/TLS Settings**
   - Go to **SSL/TLS** → **Overview**
   - Set to **Flexible** or **Full** (recommended: Full)

---

## Step 4: Configure Cloudflare Settings

### Security
1. **SSL/TLS Encryption Mode**: Set to **Full** (Strict)
2. **Browser Integrity Check**: **On**
3. **Challenge Passage**: **30 minutes**

### Performance
1. **Caching Level**: **Cache Everything**
2. **Browser Cache TTL**: **4 hours**
3. **Enable Gzip**: **On**
4. **Brotli**: **On**

### Rules (Optional)
1. **Page Rules** for caching:
   ```
   sidairesort.com/assets/* → Cache Level: Cache Everything
   sidairesort.com/*.html → Cache Level: Cache Everything
   ```

---

## Step 5: URL Redirects

The `_redirects` file handles:
- Clean URLs (remove .html extensions)
- URL redirects with 301 status
- 404 error handling

**Example routing:**
- `sidairesort.com/about` → `/about.html` (200)
- `sidairesort.com/about.html` → `/about` (301 redirect)

---

## Step 6: SEO Configuration

✅ **Already configured:**
- `robots.txt` - Allows all crawlers, specifies sitemaps
- `sitemap.xml` - Lists all pages for search engines
- `meta tags` - In HTML files for Open Graph, Twitter, etc.
- `.htaccess` - Handles 404s and security headers

**Submit to Search Engines:**
1. Google Search Console: https://search.google.com/search-console
   - Add property: `sidairesort.com`
   - Submit sitemap: `https://sidairesort.com/sitemap.xml`

2. Bing Webmaster Tools: https://www.bing.com/webmasters
   - Add site: `sidairesort.com`

---

## File Structure for Deployment

```
SidaiResort/
├── index.html              # Home page
├── about.html              # About page
├── services.html           # Services page
├── rooms.html              # Rooms page
├── menu.html               # Menu page
├── booking.html            # Booking page
├── experiences.html        # Experiences page
├── privacy-policy.html     # Privacy policy
├── cookie-policy.html      # Cookie policy
├── terms-of-service.html   # Terms of service
├── 404.html                # 404 error page
├── assets/                 # Static assets
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── images/            # Images and media
├── manifest.json           # PWA configuration
├── robots.txt              # SEO robots file
├── sitemap.xml             # XML sitemap
├── _redirects              # Cloudflare redirects
├── .htaccess               # Apache config (for fallback hosting)
├── wrangler.toml           # Cloudflare Workers config
├── _config.yml             # Jekyll build config
├── netlify.toml            # Netlify config (for fallback hosting)
└── favicon.ico             # Site favicon
```

---

## Important Configuration Files

### `_redirects` (Cloudflare Pages)
Handles URL routing and redirects. Key features:
- Clean URL mapping (remove .html)
- HTTP status codes (200 for silent redirect, 301 for permanent)
- 404 error handling

### `.htaccess` (Apache / Traditional Hosting)
Backup configuration for Apache-based hosting:
- URL rewriting
- GZIP compression
- Browser caching
- Security headers

### `robots.txt`
- Allows search engines to crawl
- Specifies sitemap location
- Disallows admin/api paths

### `manifest.json`
PWA (Progressive Web App) configuration:
- App name and description
- Icons for mobile devices
- Theme colors
- Display mode

---

## Deployment Workflow

1. **Make changes locally**
   ```bash
   git add .
   git commit -m "Update website content"
   git push origin main
   ```

2. **Cloudflare auto-deploys**
   - Connected repository triggers automatic build
   - Check deployment status in Cloudflare Pages dashboard

3. **Verify live site**
   - Visit `https://sidairesort.com`
   - Check all pages and links work
   - Test on mobile devices

---

## Testing Your Site

### Functionality
- [ ] Home page loads correctly
- [ ] All navigation links work
- [ ] Clean URLs work (e.g., `/about` instead of `/about.html`)
- [ ] 404 page displays for missing pages
- [ ] Images load from CDN

### SEO
- [ ] robots.txt accessible: https://sidairesort.com/robots.txt
- [ ] sitemap.xml accessible: https://sidairesort.com/sitemap.xml
- [ ] Meta tags present in HTML
- [ ] Canonical URLs set correctly

### Performance
- [ ] CSS and JS files load quickly
- [ ] Images optimized and cached
- [ ] Page load time < 3 seconds
- [ ] Mobile responsive

### Security
- [ ] HTTPS enabled (green lock icon)
- [ ] Security headers present
- [ ] No mixed content warnings

---

## Troubleshooting

### Domain not resolving
- Check nameserver settings
- DNS propagation can take 24-48 hours
- Use https://whois.com/ to verify

### Pages not loading
- Check `_redirects` file syntax
- Ensure all files are committed to Git
- Review Cloudflare build logs

### 404 errors on pages
- Verify file names match HTML file names
- Check `_redirects` configuration
- Clear browser cache and hard refresh (Ctrl+Shift+R)

### Slow loading
- Enable caching rules
- Check asset optimization
- Review Cloudflare analytics

---

## Additional Resources

- **Cloudflare Pages Docs**: https://developers.cloudflare.com/pages/
- **Cloudflare DNS**: https://developers.cloudflare.com/dns/
- **HTTP Status Codes**: https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
- **SEO Best Practices**: https://support.google.com/webmasters/

---

## Contact & Support

For deployment issues:
1. Check Cloudflare status: https://www.cloudflarestatus.com/
2. Review deployment logs in Cloudflare Pages dashboard
3. Consult Cloudflare community: https://community.cloudflare.com/

---

**Last Updated**: April 27, 2026
**Domain**: sidairesort.com
**Hosting**: Cloudflare Pages
