<?php
require 'includes/auth.php';
require_login(); // فقط کاربران لاگین‌کرده

$filename = $_GET['file'] ?? '';
$filename = basename($filename); // جلوگیری از مسیرهای ناامن
$path = __DIR__ . '/uploads/videos/' . $filename;

if (!file_exists($path)) {
    http_response_code(404);
    exit('فایل یافت نشد.');
}

$filesize = filesize($path);
$mime = 'video/mp4';

// پشتیبانی از درخواست HTTP Range برای پخش ویدیو (بافرینگ، جلو و عقب زدن ویدیو)
$start = 0;
$length = $filesize;
$end = $filesize - 1;

if (isset($_SERVER['HTTP_RANGE'])) {
    $range = $_SERVER['HTTP_RANGE'];
    if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
        $start = intval($matches[1]);
        if ($matches[2] !== '') {
            $end = intval($matches[2]);
        }
        $length = $end - $start + 1;

        header('HTTP/1.1 206 Partial Content');
        header("Content-Range: bytes $start-$end/$filesize");
    }
} else {
    header('HTTP/1.1 200 OK');
}

header("Content-Type: $mime");
header("Content-Length: $length");
header('Accept-Ranges: bytes');
header('Content-Disposition: inline; filename="' . $filename . '"');

// باز کردن فایل برای خواندن
$fp = fopen($path, 'rb');
if (!$fp) {
    http_response_code(500);
    exit('خطا در باز کردن فایل.');
}

// حرکت به نقطه شروع (اگر درخواست Range بوده)
fseek($fp, $start);

// ارسال داده‌ها به مرورگر
$bufferSize = 1024 * 8; // 8KB
$bytesSent = 0;

while (!feof($fp) && $bytesSent < $length) {
    $readSize = min($bufferSize, $length - $bytesSent);
    $buffer = fread($fp, $readSize);
    echo $buffer;
    flush();
    $bytesSent += strlen($buffer);
}

fclose($fp);
exit;
