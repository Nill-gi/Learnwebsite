<?php
require 'includes/db.php';
require 'includes/header.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // اعتبارسنجی اولیه
    if (!$username || !$email || !$password) {
        $errors[] = 'تمام فیلدها الزامی هستند.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'ایمیل معتبر نیست.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'رمز عبور باید حداقل ۶ کاراکتر باشد.';
    }

    // بررسی تکراری نبودن
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'نام کاربری یا ایمیل قبلاً ثبت شده.';
        }
    }

    // درج در دیتابیس
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed]);
        $success = true;
    }
}
?>

<h2>ثبت‌نام</h2>

<?php if ($success): ?>
    <p style="color: green;">ثبت‌نام با موفقیت انجام شد. <a href="login.php">ورود</a></p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
    <label>نام کاربری:
        <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </label><br><br>
    <label>ایمیل:
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </label><br><br>
    <label>رمز عبور:
        <input type="password" name="password">
    </label><br><br>
    <button type="submit">ثبت‌نام</button>
</form>

    <link rel="stylesheet" href="assets/css/style.css">
<?php require 'includes/footer.php'; ?>
