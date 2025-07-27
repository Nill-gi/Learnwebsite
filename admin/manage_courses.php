<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/locale.php'; 
require '../includes/header.php';

require_admin_main(); // فقط مدیر اصلی

$courses = $pdo->query("
    SELECT c.*, cat.name AS category_name
    FROM courses c
    LEFT JOIN categories cat ON c.category_id = cat.id
    ORDER BY c.created_at DESC
")->fetchAll();
?>

<h2>📚 مدیریت دوره‌ها</h2>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; margin-top: 20px;">
    <tr style="background: #f3f3f3;">
        <th>عنوان</th>
        <th>دسته</th>
        <th>سطح</th>
        <th>عملیات</th>
    </tr>
    <?php foreach ($courses as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['title']) ?></td>
            <td><?= htmlspecialchars($c['category_name']) ?></td>
            <td><?= translate_level($c['level']) ?></td>
            <td>
                <a href="edit_course.php?id=<?= $c['id'] ?>">✏️ ویرایش</a> |
                <a href="delete_course.php?id=<?= $c['id'] ?>" onclick="return confirm('حذف شود؟')">🗑 حذف</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require '../includes/footer.php'; ?>
