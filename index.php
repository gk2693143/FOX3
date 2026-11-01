<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (empty($_GET['get'])) {
    http_response_code(400);
    exit("Missing get parameter");
}

$headers = [
    'kayo' => [
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36",
        "dazn-token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6InV1aWRfMSJ9.eyJwYXRocyI6WyIvb3V0L3YxLzc2MWY0MjJiYjkzZWRiN2IvMGNmNTgxMjg0OWZmNGFiZWFlYjI4NzA4YWUyYTYzYTgiXSwiZXhjIjpbXSwiaGVhZGVycyI6WyJ1c2VyLWFnZW50Il0sImNvIjp0cnVlLCJpcCI6ZmFsc2UsImFzbiI6WyIzOTYzNTYiXSwic3NpZCI6IjE3NzIyNzY5IiwicHJvdG8iOiJkYXNoIiwiaW50c2lnIjoiOFBBZ0szcGFaalh5LXZ5a05FS0FqOTFmc3I5VGp6LUJVcll0cU5fY3Z0YyIsImlhdCI6MTc5MzUwMzk0MSwiZXhwIjoxNzkzNTkwMzQxfQ.ghDNKzp4z3JYdrLOqY3YAFY5rTnw8tlQe6SxatNOuK0",
        "Accept: /",
        "Referer: https://kayosports.com.au/",
        "Origin: https://kayosports.com.au",            
    ],
];


$path = $_GET['get'];

$targetUrl = "https://" . $path;

$params = $_GET;
unset($params['get']);

$query = http_build_query($params);

if ($query !== '') {
    $targetUrl .= (strpos($targetUrl, '?') === false ? '?' : '&') . $query;
}

$ch = curl_init($targetUrl);

$requestHeaders = $headers['kayo'];

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_HTTPPROXYTUNNEL => true,
    CURLOPT_PROXY => "92.112.165.16:6468",
    CURLOPT_PROXYUSERPWD => "wfatxrls:9yn2hvo6e68p",

    CURLOPT_HTTPHEADER => $requestHeaders,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false
]);

$data = curl_exec($ch);

if ($data === false) {
    http_response_code(502);
    echo "cURL error:\n";
    echo curl_error($ch);
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$info = curl_getinfo($ch);


if ($httpCode !== 200) {
    http_response_code($httpCode);
    exit("HTTP $httpCode | CURL Info: " . print_r($info, true) . "Change User-Agent or use proxy");
}

if ($contentType) {
    header("Content-Type: $contentType");
}

echo $data;
