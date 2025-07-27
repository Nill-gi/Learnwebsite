<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/header.php';

require_admin_main();

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        $success = true;
    }
}
?>

<h2>افزودن دسته‌بندی جدید</h2>

<?php if ($success): ?>
    <p style="color: green;">✅ دسته‌بندی با موفقیت افزوده شد.</p>
<?php endif; ?>

<form method="post">
    <label>نام دسته‌بندی:
        <input type="text" name="name" required>
    </label>
    <button type="submit">ثبت</button>
</form>

<?php require '../includes/footer.php'; ?>
