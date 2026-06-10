$files = Get-ChildItem -Path . -Include *.html, *.php -Recurse -File
$cssTag = '<link rel="stylesheet" href="responsive-overrides.css">'

foreach ($file in $files) {
    if ($file.FullName -match "tcpdf|node_modules|PHPMailer") { continue }
    
    $content = Get-Content $file.FullName -Raw -Encoding UTF8
    
    if (($content -match "<head.*?>") -and ($content -notmatch 'responsive-overrides\.css')) {
        $content = $content -replace '(<head[^>]*>)', "`$1`n    $cssTag"
        Set-Content -Path $file.FullName -Value $content -Encoding UTF8
    }
}
