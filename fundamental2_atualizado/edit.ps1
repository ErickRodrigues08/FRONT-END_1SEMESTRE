$content = [System.IO.File]::ReadAllText('fundamental2/index.php', [System.Text.Encoding]::UTF8)
$start = $content.IndexOf('            <!-- MÓDULO I -->')
$end = $content.IndexOf('            <!-- MÓDULO II -->')
if ($start -ge 0 -and $end -ge 0) {
    $newContent = $content.Substring(0, $start) + $content.Substring($end)
    $newContent = $newContent.Replace('<div class="tab-pane fade" id="mod2" role="tabpanel">', '<div class="tab-pane fade show active" id="mod1" role="tabpanel">')
    $newContent = $newContent.Replace('<!-- /mod2 -->', '<!-- /mod1 -->')
    [System.IO.File]::WriteAllText('fundamental2/index.php', $newContent, [System.Text.Encoding]::UTF8)
    Write-Host 'Success'
} else {
    Write-Host 'Not found'
}
