<?php
$startUrl = 'http://127.0.0.1:8000';
$visited = [];
$queue = [$startUrl];
$broken = [];
$limit = 150; // Limit to 150 pages to avoid infinite loops

echo "Starting crawler on $startUrl ...\n\n";

while (!empty($queue) && count($visited) < $limit) {
    $url = array_shift($queue);
    if (isset($visited[$url])) continue;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'BrokenLinkChecker/1.0');
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    
    $visited[$url] = $code;
    
    if ($code >= 400 || $code == 0) {
        $broken[] = ['url' => $url, 'code' => $code == 0 ? 'Failed' : $code];
        echo "[BROKEN] $url (HTTP $code)\n";
    } else {
        // echo "[OK $code] $url\n";
        if ($html && strpos($contentType, 'text/html') !== false) {
            $dom = new DOMDocument;
            @$dom->loadHTML($html);
            foreach ($dom->getElementsByTagName('a') as $node) {
                $href = $node->getAttribute('href');
                if (empty($href) || strpos($href, 'javascript:') === 0 || strpos($href, 'mailto:') === 0 || strpos($href, 'tel:') === 0) continue;
                
                // Parse absolute local url
                if (strpos($href, $startUrl) === 0) {
                    $nextUrl = explode('#', $href)[0];
                    if (!isset($visited[$nextUrl]) && !in_array($nextUrl, $queue) && !str_contains($nextUrl, '.pdf')) {
                        $queue[] = $nextUrl;
                    }
                }
                // Parse relative local url
                elseif (strpos($href, '/') === 0 && strpos($href, '//') !== 0) {
                    $nextUrl = $startUrl . explode('#', $href)[0];
                    if (!isset($visited[$nextUrl]) && !in_array($nextUrl, $queue) && !str_contains($nextUrl, '.pdf')) {
                        $queue[] = $nextUrl;
                    }
                }
            }
        }
    }
}

echo "\n--- Summary ---\n";
echo "Total Links Checked: " . count($visited) . "\n";
echo "Broken Links Found: " . count($broken) . "\n\n";

if (count($broken) > 0) {
    echo "Broken Links Details:\n";
    foreach ($broken as $b) {
        echo "- " . $b['url'] . " (HTTP " . $b['code'] . ")\n";
    }
} else {
    echo "Great! No broken links (404) were found in the checked pages.\n";
}
