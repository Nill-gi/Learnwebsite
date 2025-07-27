<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/locale.php'; 
require '../includes/header.php';

require_admin_sub(); // فقط ادمین‌ها
?>

<link rel="stylesheet" href="../assets/css/style.css">

<div class="dashboard">
    <h2>📊 داشبورد مدیریت</h2>

    <div class="card-grid">

        <!-- قابل استفاده برای همه ادمین‌ها -->
        <div class="card">
            <a href="add_course.php">➕ افزودن دوره</a>
        </div>
        <div class="card">
            <a href="add_video.php">➕ افزودن ویدیو</a>
        </div>
        <div class="card">
            <a href="add_category.php">➕ افزودن دسته‌بندی</a>
        </div>

        <!-- فقط برای ادمین اصلی -->
        <?php if (is_admin_main()): ?>
            <div class="card">
                <a href="manage_courses.php">🎓 مدیریت دوره‌ها</a>
            </div>
		    <div class="card">
            <a href="manage_videos.php">🎓 مدیریت ویدیوها</a>
            </div>
            <div class="card">
                <a href="manage_categories.php">📁 مدیریت دسته‌بندی‌ها</a>
            </div>
            <div class="card">
                <a href="add_admin.php">👤 افزودن ادمین فرعی</a>
            </div>
            <div class="card">
                <a href="manage_users.php">⚙️ مدیریت نقش‌ها</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require '../includes/footer.php'; ?>
