<?php
require 'includes/db.php';
require 'includes/auth.php';
require 'includes/locale.php'; 
require 'includes/header.php';

$video_id = $_GET['id'] ?? null;
if (!$video_id || !is_numeric($video_id)) {
    die('ویدیو نامعتبر است.');
}

// گرفتن ویدیو همراه با اطلاعات دوره‌اش
$stmt = $pdo->prepare("
    SELECT v.*, c.id AS course_id, c.title AS course_title, c.level
    FROM videos v
    LEFT JOIN courses c ON v.course_id = c.id
    WHERE v.id = ?
");
$stmt->execute([$video_id]);
$video = $stmt->fetch();

if (!$video) {
    die('ویدیو یافت نشد.');
}

// تابعی برای گرفتن فقط نام فایل ویدیو
function mapRealFileName($filepath) {
    return basename($filepath); // استخراج فقط نام فایل
}

$real_filename = mapRealFileName($video['filepath']);

// بررسی سطح دوره
$needs_payment = ($video['level'] === 'advanced');
$course_id = $video['course_id'];
$unlocked = isset($_SESSION['unlocked_courses']) && in_array($course_id, $_SESSION['unlocked_courses']);

?>

<h2><?= htmlspecialchars($video['title']) ?></h2>
<p><strong>دوره:</strong> <?= htmlspecialchars($video['course_title']) ?></p>
<p><strong>مدت زمان:</strong> <?= fa_number($video['duration']) ?> دقیقه</p>

<?php
// اگر کاربر وارد نشده باشد، اجازه نمایش ویدیو را نمی‌دهیم
if (!is_logged_in()) {
    echo '<div style="border: 1px solid red; padding: 15px; background: #fff0f0; margin: 20px 0;">
        <strong>⚠️ برای مشاهده ویدیو باید وارد حساب کاربری شوید یا ثبت‌نام کنید.</strong><br><br>
        <a href="login.php" class="btn">ورود به حساب</a> |
        <a href="register.php" class="btn">ثبت‌نام</a>
    </div>';
    require 'includes/footer.php';
    exit;
}

// اگر دوره advanced هست و هنوز نخریده
if ($needs_payment && !$unlocked) {
    echo "<h2>🔒 دسترسی محدود</h2>";
    echo "<p>این ویدیو مربوط به دوره پیشرفته است و برای مشاهده باید آن را خریداری کنید.</p>";
    echo '<a href="cart.php?id=' . $course_id . '" class="buy-button">🛒 خرید این دوره</a>';
    require 'includes/footer.php';
    exit;
}

// ثبت بازدید
$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("INSERT INTO video_views (video_id, user_id) VALUES (?, ?)");
$stmt->execute([$video_id, $user_id]);

// گرفتن تعداد بازدیدها
$stmt = $pdo->prepare("SELECT COUNT(*) FROM video_views WHERE video_id = ?");
$stmt->execute([$video_id]);
$views = $stmt->fetchColumn();
?>

<p><strong>تعداد بازدید:</strong> <?= fa_number($views) ?></p>

<!-- پخش ویدیو -->
<video width="640" controls style="max-width: 100%; margin: 20px 0;">
    <source src="stream.php?file=<?= urlencode($real_filename) ?>" type="video/mp4">
    مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
</video>

<p><?= nl2br(htmlspecialchars($video['description'])) ?></p>

<hr>

<?php
// ارسال نظر
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = trim($_POST['comment'] ?? '');
    if ($comment !== '') {
        $stmt = $pdo->prepare("INSERT INTO comments (video_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$video_id, $user_id, $comment]);
        header("Location: video.php?id=" . $video_id);
        exit;
    }
}

// گرفتن نظرات
$stmt = $pdo->prepare("
    SELECT c.comment, c.created_at, u.username
    FROM comments c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.video_id = ?
    ORDER BY c.created_at DESC
");
$stmt->execute([$video_id]);
$comments = $stmt->fetchAll();
?>

<h3>💬 نظرات کاربران</h3>

<form method="post">
    <textarea name="comment" required placeholder="نظر خود را بنویسید..." rows="3" style="width: 100%;"></textarea><br>
    <button type="submit">ارسال نظر</button>
</form>

<?php if ($comments): ?>
    <ul style="margin-top: 20px;">
        <?php foreach ($comments as $c): ?>
            <li style="margin-bottom: 10px;">
                <strong><?= htmlspecialchars($c['username']) ?>:</strong><br>
                <?= nl2br(htmlspecialchars($c['comment'])) ?><br>
                <div style="font-size: 0.8em; color: gray;">
                    <?= fa_number(convertGregorianToJalali($c['created_at'])) ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>هنوز نظری ثبت نشده.</p>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
