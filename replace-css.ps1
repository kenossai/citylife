param(
    [string]$FilePath = "public\assets\css\citylife.css",
    [string]$OldText = "cleenhearts",
    [string]$NewText = "citylife"
)

# Check if file exists
if (Test-Path $FilePath) {
    Write-Host "Processing CSS file: $FilePath"

    # Read content
    $content = Get-Content $FilePath -Raw

    # Check if file contains the old text
    if ($content -match $OldText) {
        Write-Host "  - Replacing $OldText with $NewText"

        # Replace all instances
        $newContent = $content -replace $OldText, $NewText

        # Write back to file
        Set-Content -Path $FilePath -Value $newContent -NoNewline

        Write-Host "  - Updated successfully"
    } else {
        Write-Host "  - No replacements needed"
    }
} else {
    Write-Host "File not found: $FilePath"
}

Write-Host "CSS replacement complete!"
