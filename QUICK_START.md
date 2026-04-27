# Quick Start: Deploy Sidai Resort to Cloudflare Pages

## ⚡ 5-Minute Setup

### Step 1: Prepare Your Git Repository
```bash
# Navigate to project directory
cd "d:\MY PROJECTS\Sidai Resort\SidaiResort"

# Initialize Git (if not already done)
git init

# Add all files
git add .

# Commit changes
git commit -m "Initial commit: Sidai Resort static site ready for Cloudflare"

# Add remote repository (replace with your GitHub URL)
git remote add origin https://github.com/yourusername/sidai-resort.git

# Push to GitHub
git branch -M main
git push -u origin main
```

### Step 2: Connect to Cloudflare Pages
1. **Log in to Cloudflare**: https://dash.cloudflare.com/
2. **Navigate to Pages** (Left sidebar)
3. **Click "Connect to Git"**
4. **Select GitHub** (authorize if needed)
5. **Select repository**: `sidai-resort`
6. **Configure build**:
   - **Project name**: `sidai-resort`
   - **Production branch**: `main`
   - **Build command**: (leave empty)
   - **Build output directory**: `.`
7. **Save & Deploy** ✅

### Step 3: Add Your Domain
1. **In Cloudflare Pages project**:
   - Go to **Settings** → **Custom Domains**
   - Click **Add Custom Domain**
   - Enter: `sidairesort.com`

2. **Update Nameservers**:
   - Get Cloudflare nameservers:
     - `ns1.cloudflare.com`
     - `ns2.cloudflare.com`
     - `ns3.cloudflare.com`
     - `ns4.cloudflare.com`
   - Update your registrar's nameserver settings

3. **Wait for DNS** (24-48 hours)

### Step 4: Verify DNS Records
In Cloudflare **DNS** section, add:
```
Type    Name            Content           TTL    Proxy
A       sidairesort.com X.X.X.X          Auto   Proxied
CNAME   www             sidairesort.com   Auto   Proxied
```

### Step 5: Test Your Site
- Visit `https://sidairesort.com`
- Check HTTPS works (green lock)
- Test links and pages
- Test on mobile

---

## 📁 What's Included

| File | Purpose |
|------|---------|
| `_redirects` | Cloudflare URL routing & redirects |
| `.htaccess` | Apache backup config |
| `wrangler.toml` | Cloudflare Workers setup |
| `_config.yml` | Jekyll build config |
| `robots.txt` | SEO robots file |
| `manifest.json` | PWA config |
| `HOSTING_GUIDE.md` | Complete hosting instructions |
| `DEPLOYMENT_CHECKLIST.md` | Pre-launch checklist |
| `OPTIMIZATION_GUIDE.md` | Performance tips |

---

## 🔄 Making Changes & Updates

### Update Content
```bash
# Make changes locally
# Edit HTML files, images, etc.

# Commit & push
git add .
git commit -m "Update [description]"
git push origin main
```

**Cloudflare auto-deploys!** ✨
- Changes appear live within 1-2 minutes
- Check deployment status in Cloudflare Pages dashboard

---

## 🐛 Quick Troubleshooting

| Issue | Solution |
|-------|----------|
| Domain not loading | Check nameservers (24-48h to propagate) |
| 404 errors | Verify `_redirects` file syntax |
| Slow loading | Clear cache: Cloudflare **Caching** → **Purge All** |
| HTTPS errors | Set SSL to **Full (Strict)** in Cloudflare |
| Images not loading | Check file paths, verify assets/ folder |

---

## 📊 Monitor Your Site

1. **Cloudflare Analytics**
   - Pages dashboard → Analytics
   - Monitor traffic, page views, errors

2. **Google Search Console**
   - https://search.google.com/search-console
   - Add property: sidairesort.com
   - Monitor search traffic, indexing

3. **Bing Webmaster Tools**
   - https://www.bing.com/webmasters
   - Track Bing search traffic

---

## 🎯 Next Steps

- [ ] Connect Git repository
- [ ] Set up Cloudflare Pages
- [ ] Add custom domain
- [ ] Update DNS nameservers
- [ ] Test all pages
- [ ] Submit to Google Search Console
- [ ] Monitor analytics

---

**Need help?** See detailed guides:
- Full Setup: [HOSTING_GUIDE.md](HOSTING_GUIDE.md)
- Pre-Launch: [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)
- Performance: [OPTIMIZATION_GUIDE.md](OPTIMIZATION_GUIDE.md)

---

**Status**: 🟢 Ready for Live Deployment
**Domain**: sidairesort.com
**Hosting**: Cloudflare Pages
