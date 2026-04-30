param(
  [string]$RepoName = 'narok-travel-blog',
  [ValidateSet('public','private')]
  [string]$Visibility = 'public'
)

$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $PSScriptRoot

$ghPath = (Get-Command gh -ErrorAction SilentlyContinue | Select-Object -ExpandProperty Source -ErrorAction SilentlyContinue)
if (-not $ghPath) {
  $fallback = 'C:\Program Files\GitHub CLI\gh.exe'
  if (Test-Path $fallback) { $ghPath = $fallback }
}
if (-not $ghPath) { throw 'GitHub CLI not found.' }

Write-Host "Checking GitHub auth..."
& $ghPath auth status | Out-Null

Push-Location $root
try {
  if (-not (Test-Path '.git')) {
    git init | Out-Null
    git add .
    git commit -m 'Initial Narok Travel Guide blog build'
  }

  $origin = git remote get-url origin 2>$null
  if (-not $origin) {
    & $ghPath repo create $RepoName --$Visibility --source . --remote origin --push
  } else {
    git push -u origin main
  }

  $owner = (& $ghPath api user --jq .login).Trim()

  # Try create Pages config, fall back to update if already exists.
  & $ghPath api -X POST "repos/$owner/$RepoName/pages" -f source[branch]=main -f source[path]='/' 2>$null
  if ($LASTEXITCODE -ne 0) {
    & $ghPath api -X PUT "repos/$owner/$RepoName/pages" -f source[branch]=main -f source[path]='/'
  }

  Write-Host "GitHub Pages setup command completed for $owner/$RepoName"
  Write-Host "Expected URL: https://$owner.github.io/$RepoName/"
  Write-Host "Next: run scripts/set-blog-url.ps1 with that URL, commit, and push again."
}
finally {
  Pop-Location
}
