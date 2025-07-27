<?php
require 'includes/db.php';
require 'includes/header.php';

$token = $_GET['token'] ?? '';
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $new_pass = $_POST['password'] ?? '';

    // بررسی معتبر بودن توکن و انقضا
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if ($reset) {
        // هش کردن رمز جدید
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);

        // تغییر رمز کاربر
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed, $reset['email']]);

        // حذف توکن از جدول پس از استفاده
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$reset['email']]);

        $success = 'رمز عبور با موفقیت تغییر یافت.';
    } else {
        $error = 'توکن معتبر نیست یا منقضی شده.';
    }
}
?>

<h2>تغییر رمز عبور</h2>

<?php if ($success): ?>
    <p style="color: green"><?= $success ?></p>
<?php elseif ($error): ?>
    <p style="color: red"><?= $error ?></p>
<?php else: ?>
    <form method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="password" name="password" required placeholder="رمز جدید" style="width: 100%; max-width: 300px;">
        <br><br>
        <button type="submit">تغییر رمز</button>
    </form>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
