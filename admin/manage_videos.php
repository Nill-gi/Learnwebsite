<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/locale.php'; // 🟢 اتصال به توابع شمسی و فارسی
require '../includes/header.php';

require_admin_main(); // فقط مدیر اصلی

// گرفتن لیست ویدیوها
$stmt = $pdo->query("
    SELECT v.*, c.title AS course_title
    FROM videos v
    LEFT JOIN courses c ON v.course_id = c.id
    ORDER BY v.created_at DESC
");
$videos = $stmt->fetchAll();
?>

<h2>🎥 مدیریت ویدیوها</h2>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; margin-top: 20px;">
    <tr style="background-color: #f3f3f3;">
        <th>عنوان</th>
        <th>دوره</th>
        <th>مدت (دقیقه)</th>
        <th>تاریخ ثبت</th>
        <th>عملیات</th>
    </tr>

    <?php foreach ($videos as $video): ?>
        <tr>
            <td><?= htmlspecialchars($video['title']) ?></td>
            <td><?= htmlspecialchars($video['course_title']) ?></td>
            <td><?= fa_number($video['duration']) ?></td>
            <td><?= fa_number(convertGregorianToJalali($video['created_at'])) ?></td>
            <td>
                <a href="edit_video.php?id=<?= $video['id'] ?>">✏️ ویرایش</a> |
                <a href="delete_video.php?id=<?= $video['id'] ?>" onclick="return confirm('آیا از حذف مطمئن هستید؟')">🗑 حذف</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require '../includes/footer.php'; ?>
