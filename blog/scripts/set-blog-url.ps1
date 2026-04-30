param(
  [Parameter(Mandatory = $true)]
  [string]$BlogUrl
)

$ErrorActionPreference = 'Stop'

$normalized = $BlogUrl.Trim()
$normalized = $normalized.TrimEnd('/')
$normalized = $normalized -replace '^https?://', ''
$normalized = "https://$normalized"

$root = Split-Path -Parent $PSScriptRoot
$targets = @(
  (Join-Path $root 'robots.txt'),
  (Join-Path $root 'sitemap.xml')
)

foreach ($path in $targets) {
  $content = Get-Content -Raw $path
  $updated = $content -replace 'https://\[BLOG-URL\]', $normalized
  Set-Content -Path $path -Value $updated -Encoding UTF8
}

Write-Host "Updated robots.txt and sitemap.xml with $normalized"

