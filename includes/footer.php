<?php
require_once __DIR__ . '/locale.php'; // ✅ اتصال به توابع فارسی‌ساز
?>
</main>

<footer>
    <div style="text-align: center;">
        <p>تمامی حقوق این سایت محفوظ است &copy; <?= fa_number(substr(convertGregorianToJalali(date('Y-m-d')), 0, 4)) ?> - سایت آموزش برنامه‌نویسی</p>
    </div>
</footer>

</body>
</html>
