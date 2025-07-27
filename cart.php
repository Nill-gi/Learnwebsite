<?php
require 'includes/db.php';
require 'includes/auth.php';
require 'includes/header.php';

require_login();

$course_id = $_GET['id'] ?? null;
if (!$course_id || !is_numeric($course_id)) {
    die('دوره نامعتبر است.');
}

// دریافت اطلاعات دوره
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course || $course['level'] !== 'advanced') {
    die('فقط دوره‌های پیشرفته نیاز به خرید دارند.');
}

// شبیه‌سازی پرداخت
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['unlocked_courses'][] = $course_id;
    echo '<p style="color:green;">✅ پرداخت با موفقیت انجام شد. اکنون می‌توانید دوره را مشاهده کنید.</p>';
    echo '<a href="course.php?id=' . $course_id . '">🎓 بازگشت به دوره</a>';
    require 'includes/footer.php';
    exit;
}
?>

<h2>🛒 سبد خرید</h2>
<p>شما در حال خرید دوره: <strong><?= htmlspecialchars($course['title']) ?></strong></p>
<p>قیمت: <strong>3,000,000 تومان</strong> </p>

<form method="post">
    <button type="submit">پرداخت</button>
</form>

<?php require 'includes/footer.php'; ?>
