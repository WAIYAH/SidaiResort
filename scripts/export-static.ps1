param(
    [string]$AppUrl = "https://sidairesort.com",
    [string]$PhpPath = "C:\xampp\php\php.exe"
)

$ErrorActionPreference = "Stop"

$php = $PhpPath
$staticDir = "public-static"
$staticWebRoot = "/__STATIC_ROOT__"

if (-not (Test-Path $php)) {
    throw "PHP executable not found at $php"
}

if (Test-Path $staticDir) {
    Remove-Item -LiteralPath $staticDir -Recurse -Force
}

New-Item -ItemType Directory -Path $staticDir | Out-Null

Copy-Item -Path "public/assets" -Destination "$staticDir/assets" -Recurse -Force
Copy-Item -Path "public/favicon.ico","public/manifest.json","public/placeholder.svg","public/robots.txt","public/sitemap.xml" -Destination $staticDir -Force

$pages = @(
    @{ name = "index"; uri = "/" },
    @{ name = "about"; uri = "/about" },
    @{ name = "services"; uri = "/services" },
    @{ name = "rooms"; uri = "/rooms" },
    @{ name = "menu"; uri = "/menu" },
    @{ name = "booking"; uri = "/booking" },
    @{ name = "privacy-policy"; uri = "/privacy-policy" },
    @{ name = "cookie-policy"; uri = "/cookie-policy" },
    @{ name = "terms-of-service"; uri = "/terms-of-service" },
    @{ name = "404"; uri = "/404" }
)

$env:WEB_ROOT = $staticWebRoot
$env:APP_URL = $AppUrl

foreach ($page in $pages) {
    $env:REQUEST_URI = $page.uri
    $outFile = Join-Path $staticDir ("{0}.html" -f $page.name)
    $errFile = Join-Path $env:TEMP ("sidai_static_{0}.err" -f $page.name)

    & $php ("public/{0}.php" -f $page.name) 1> $outFile 2> $errFile
    if ($LASTEXITCODE -ne 0) {
        $err = if (Test-Path $errFile) { Get-Content -Raw $errFile } else { "" }
        throw "Failed rendering $($page.name).php`n$err"
    }
}

$cleanupFiles = @("rooms","menu","booking","404")
foreach ($fileBase in $cleanupFiles) {
    $filePath = Join-Path $staticDir ("{0}.html" -f $fileBase)
    if (-not (Test-Path $filePath)) {
        continue
    }

    $content = Get-Content -Raw $filePath
    $doctypeIndex = $content.IndexOf("<!doctype html>")
    if ($doctypeIndex -gt 0) {
        $content = $content.Substring($doctypeIndex)
    }

    $content = [regex]::Replace($content, "</body>\s*</html>\s*</body>\s*</html>\s*$", "</body>`r`n</html>`r`n", [System.Text.RegularExpressions.RegexOptions]::IgnoreCase)

    Set-Content -Path $filePath -Value $content -NoNewline -Encoding UTF8
}

$htmlFiles = Get-ChildItem -Path $staticDir -Filter "*.html" -File
foreach ($file in $htmlFiles) {
    $content = Get-Content -Raw $file.FullName
    $content = $content.Replace("/services.php", "/services")
    $content = $content.Replace("/booking.php", "/booking")
    $content = $content.Replace("/rooms.php", "/rooms")
    $content = $content.Replace("/menu.php", "/menu")
    $content = $content.Replace("/about.php", "/about")
    $content = $content.Replace($staticWebRoot, "")
    $content = $content.Replace('action="/api/contact-submit.php"', 'action="#" data-static-disabled="true"')
    $content = $content.Replace('action="/api/booking-submit.php"', 'action="#" data-static-disabled="true"')

    if ($content.Contains('data-static-disabled="true"') -and -not $content.Contains("Form submissions are disabled on this temporary static deployment.")) {
        $content = $content -replace "</body>", @'
<script>
(() => {
    const forms = document.querySelectorAll('form[data-static-disabled="true"]');
    forms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            alert('Form submissions are disabled on this temporary static deployment.');
        });
    });
})();
</script>
</body>
'@
    }

    Set-Content -Path $file.FullName -Value $content -NoNewline -Encoding UTF8
}

Write-Output "Static export completed in $staticDir"
