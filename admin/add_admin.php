<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/header.php';

require_admin_main(); // فقط مدیر اصلی

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$email || !$password) {
        $errors[] = 'همه فیلدها الزامی هستند.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'ایمیل نامعتبر است.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'رمز باید حداقل ۶ کاراکتر باشد.';
    }

    // بررسی تکراری نبودن
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'این نام کاربری یا ایمیل قبلاً ثبت شده است.';
        }
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin_sub')");
        $stmt->execute([$username, $email, $hashed]);
        $success = true;
    }
}
?>

<h2>👤 افزودن ادمین فرعی</h2>

<?php if ($success): ?>
    <p style="color: green;">✅ ادمین فرعی با موفقیت ثبت شد.</p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
    <label>نام کاربری:
        <input type="text" name="username" required>
    </label><br><br>

    <label>ایمیل:
        <input type="email" name="email" required>
    </label><br><br>

    <label>رمز عبور:
        <input type="password" name="password" required>
    </label><br><br>

    <button type="submit">ثبت ادمین فرعی</button>
</form>

<?php require '../includes/footer.php'; ?>
