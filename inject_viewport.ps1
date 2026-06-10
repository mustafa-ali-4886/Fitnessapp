$files = Get-ChildItem -Path . -Include *.html, *.php -Recurse -File
$viewportTag = '<meta name="viewport" content="width=device-width, initial-scale=1.0">'

foreach ($file in $files) {
    # Skip non-frontend PHP files and vendor directories
    if ($file.FullName -match "tcpdf|node_modules|get_plan\.php|db\.php|PHPMailer") { continue }
    
    $content = Get-Content $file.FullName -Raw -Encoding UTF8
    
    # If it's a frontend file with a <head> but no viewport tag
    if (($content -match "<head.*?>") -and ($content -notmatch 'name="viewport"')) {
        # Insert viewport after <head>
        $content = $content -replace '(<head[^>]*>)', "`$1`n    $viewportTag"
        Set-Content -Path $file.FullName -Value $content -Encoding UTF8
        Write-Output "Injected viewport into $($file.Name)"
    }
}
