<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/header.php';

require_admin_main(); // فقط مدیر اصلی

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// حذف دسته
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_categories.php");
    exit;
}
?>

<h2>📁 مدیریت دسته‌بندی‌ها</h2>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; margin-top: 20px;">
    <tr style="background: #f3f3f3;">
        <th>نام دسته</th>
        <th>عملیات</th>
    </tr>
    <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?= htmlspecialchars($cat['name']) ?></td>
            <td>
                <a href="" onclick="return confirm('حذف شود؟')">🗑 حذف</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require '../includes/footer.php'; ?>
