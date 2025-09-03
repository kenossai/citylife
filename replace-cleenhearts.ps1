param(
    [string]$Path = ".",
    [string]$OldText = "cleenhearts",
    [string]$NewText = "citylife"
)

# Get all blade.php files recursively
$files = Get-ChildItem -Path $Path -Filter "*.blade.php" -Recurse

Write-Host "Found $($files.Count) blade.php files"

foreach ($file in $files) {
    Write-Host "Processing: $($file.FullName)"

    # Read content
    $content = Get-Content $file.FullName -Raw

    # Check if file contains the old text
    if ($content -match $OldText) {
        Write-Host "  - Replacing $OldText with $NewText"

        # Replace all instances
        $newContent = $content -replace $OldText, $NewText

        # Write back to file
        Set-Content -Path $file.FullName -Value $newContent -NoNewline

        Write-Host "  - Updated successfully"
    } else {
        Write-Host "  - No replacements needed"
    }
}

Write-Host "Replacement complete!"
