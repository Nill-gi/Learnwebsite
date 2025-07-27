<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/locale.php'; // ✅ اتصال به فایل فارسی‌ساز

// اطلاعات کاربر
$user = $_SESSION['user'] ?? null;
$userRole = $user['role'] ?? null;
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سایت آموزش برنامه‌نویسی</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/Learn-web/assets/css/style.css">
</head>
<body>
<header>
    <h1>🎓 سایت آموزش برنامه‌نویسی</h1>
    
    <!-- ✅ نمایش تاریخ شمسی امروز -->
    <div style="font-size: 0.9em; color: #eee; margin-top: 5px;">
        امروز: <?= fa_number(convertGregorianToJalali(date('Y-m-d H:i'))) ?>
    </div>

    <nav>
        <a href="/Learn-web/index.php">خانه</a>

        <?php if ($userRole === 'user'): ?>
            <a href="/Learn-web/user/dashboard.php">👤 پنل کاربری</a>
            <a href="/Learn-web/cart.php">🛒 سبد خرید</a>
        <?php endif; ?>

        <?php if ($userRole === 'admin_main' || $userRole === 'admin_sub'): ?>
            <a href="/Learn-web/admin/dashboard.php">🛠 پنل مدیریت</a>
        <?php endif; ?>

        <?php if ($user): ?>
            <a href="/Learn-web/logout.php">
                🚪 خروج (<?= htmlspecialchars($user['username']) ?> - <?= translate_role($userRole) ?>)
            </a>
        <?php else: ?>
            <a href="/Learn-web/login.php">ورود</a>
            <a href="/Learn-web/register.php">ثبت‌نام</a>
        <?php endif; ?>
		
		<a href="/Learn-web/about.php">درباره ما</a>
		<a href="/Learn-web/contact.php">ارتباط با ما</a>
    </nav>
</header>

<main>
