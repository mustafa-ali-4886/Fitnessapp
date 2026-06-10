$files = Get-ChildItem -Path . -Include *.html, *.php -Recurse -File
$jsTag = '<script src="mobile-nav.js"></script>'

foreach ($file in $files) {
    if ($file.FullName -match "tcpdf|node_modules|PHPMailer") { continue }
    
    $content = Get-Content $file.FullName -Raw -Encoding UTF8
    
    if (($content -match "</body>") -and ($content -notmatch 'mobile-nav\.js')) {
        $content = $content -replace '(</body>)', "    $jsTag`n`$1"
        Set-Content -Path $file.FullName -Value $content -Encoding UTF8
    }
}
