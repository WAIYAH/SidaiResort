# Narok Travel Guide

Static SEO blog for Sidai Resort and Narok County travel content.

## Project Path

`d:\MY PROJECTS\Sidai Resort\Blog\narok-travel-blog`

## One-Command Deploy (Preferred)

### GitHub Pages

1. Login once:
   - `& "C:\Program Files\GitHub CLI\gh.exe" auth login --web --git-protocol https`
2. Deploy:
   - `.\scripts\deploy-github.ps1 -RepoName narok-travel-blog -Visibility public`
3. Set live URL in SEO files:
   - `.\scripts\set-blog-url.ps1 -BlogUrl https://<username>.github.io/narok-travel-blog`
4. Commit and push the SEO URL update:
   - `git add robots.txt sitemap.xml`
   - `git commit -m "Set production blog URL"`
   - `git push`

### Cloudflare Pages

1. Login once:
   - `wrangler login --browser=false --callback-port 8977`
2. Deploy:
   - `.\scripts\deploy-cloudflare.ps1 -ProjectName narok-travel-blog -ProductionBranch main`
3. Set live URL in SEO files:
   - `.\scripts\set-blog-url.ps1 -BlogUrl https://<project>.pages.dev`
4. Redeploy to publish updated sitemap/robots:
   - `.\scripts\deploy-cloudflare.ps1 -ProjectName narok-travel-blog -ProductionBranch main`

## Google Search Console

1. Add the live blog URL as a new property.
2. Verify ownership.
3. Submit: `https://[BLOG-URL]/sitemap.xml`.
4. Re-submit sitemap whenever new pages are added.

## Social Distribution Checklist

- Share each article on Sidai Resort Facebook.
- Share each article on Sidai Resort Instagram (link in bio).
- Share each article on Sidai Resort WhatsApp status.

## Monthly Content Pipeline

Seed articles already included in this build:

- Best nyama choma in Narok County
- Weekend getaway from Nairobi to Narok
- Birdwatching in Loita Hills Kenya
- Wedding venues in Narok County Kenya
- Conference venues Narok Kenya 2026

Continue adding one new article each month for sustained SEO growth.

## Notes

- Every core article includes multiple links to `https://www.sidairesort.com`.
- Keep publishing cadence consistent for SEO growth.
