<?php
require 'includes/db.php';
require 'includes/header.php';

require 'includes/PHPMailer/PHPMailer.php';
require 'includes/PHPMailer/SMTP.php';
require 'includes/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // اعتبار ۱ ساعت

        // جدول password_resets باید در دیتابیس باشه
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expires]);

        $reset_link = "http://localhost/Learn-web/reset_password.php?token=$token";

        // ارسال ایمیل
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'fatemehghobodi@gmail.com'; // ایمیل واقعی
            $mail->Password   = 'ggabuxkmlsqabfrr';          // App password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('fatemehghobodi@gmail.com', 'سایت آموزش برنامه‌نویسی');
            $mail->addAddress($email);

            $mail->Subject = 'بازیابی رمز عبور';
            $mail->Body    = "سلام {$user['username']}!\n\nبرای بازیابی رمز عبور، روی لینک زیر کلیک کن:\n\n$reset_link\n\nاین لینک تا یک ساعت معتبر است.";

            $mail->send();
            $success = 'لینک بازیابی رمز عبور به ایمیل شما ارسال شد.';
        } catch (Exception $e) {
            $error = 'خطا در ارسال ایمیل: ' . $mail->ErrorInfo;
        }
    } else {
        $error = 'کاربری با این ایمیل یافت نشد.';
    }
}
?>

<h2>فراموشی رمز عبور</h2>
<form method="post">
    <input type="email" name="email" required placeholder="ایمیل خود را وارد کنید" style="width: 100%; max-width: 300px;">
    <br><br>
    <button type="submit">ارسال لینک بازیابی</button>
</form>

<?php if ($success): ?>
    <p style="color: green;"><?= $success ?></p>
<?php elseif ($error): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
