<?php
$log = 'storage/logs/laravel.log';
if (!file_exists($log)) {
    echo "Log file does not exist.\n";
    exit;
}
$content = file_get_contents($log);
$entries = explode("\n[", $content);
$last = end($entries);
echo "[" . $last;
