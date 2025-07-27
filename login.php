<?php
require 'includes/db.php';
require 'includes/header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $errors[] = 'ایمیل و رمز عبور الزامی هستند.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // ورود موفق
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];
            header("Location: index.php");
            exit;
        } else {
            $errors[] = 'ایمیل یا رمز نادرست است.';
        }
    }
}
?>

<h2>ورود به حساب</h2>

<?php if (!empty($errors)): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
    <label>ایمیل:
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </label><br><br>
    <label>رمز عبور:
        <input type="password" name="password">
    </label><br><br>

    <button type="submit">ورود</button>
    <a href="forgot_password.php" style="margin-right: 15px; text-decoration: none; vertical-align: middle;">
        <button type="button">فراموشی رمز عبور</button>
    </a>
</form>

<link rel="stylesheet" href="assets/css/style.css">
<?php require 'includes/footer.php'; ?>
