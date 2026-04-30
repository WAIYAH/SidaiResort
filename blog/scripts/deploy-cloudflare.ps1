param(
  [string]$ProjectName = 'narok-travel-blog',
  [string]$ProductionBranch = 'main'
)

$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $PSScriptRoot

Write-Host "Checking Cloudflare auth..."
wrangler whoami | Out-Null

Write-Host "Ensuring Pages project exists: $ProjectName"
$existing = wrangler pages project list 2>$null
if ($LASTEXITCODE -ne 0) {
  throw 'Could not list Cloudflare Pages projects. Run: wrangler login --browser=false --callback-port 8977'
}

if ($existing -notmatch "\b$ProjectName\b") {
  wrangler pages project create $ProjectName --production-branch $ProductionBranch
}

Write-Host "Deploying static site..."
wrangler pages deploy $root --project-name $ProjectName --commit-dirty=true

Write-Host "Deployment command completed."
Write-Host "Next: run scripts/set-blog-url.ps1 with your final Pages URL and redeploy."
