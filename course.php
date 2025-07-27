<?php
require 'includes/db.php';
require 'includes/auth.php';
require 'includes/locale.php'; // 📌 اضافه شد
require 'includes/header.php';

$course_id = $_GET['id'] ?? null;
if (!$course_id || !is_numeric($course_id)) {
    die('دوره نامعتبر است.');
}

// اطلاعات دوره
$stmt = $pdo->prepare("
    SELECT c.*, cat.name AS category_name
    FROM courses c
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE c.id = ?
");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    die('دوره یافت نشد.');
}

// بررسی نیاز به پرداخت
$needs_payment = ($course['level'] === 'advanced');
$unlocked = isset($_SESSION['unlocked_courses']) && in_array($course_id, $_SESSION['unlocked_courses']);

// اگر پیشرفته است و هنوز نخریده:
if ($needs_payment && !$unlocked) {
    echo "<h2>" . htmlspecialchars($course['title']) . "</h2>";
    echo "<p><strong>دسته‌بندی:</strong> " . htmlspecialchars($course['category_name']) . "</p>";
    echo "<p><strong>سطح:</strong> " . translate_level($course['level']) . " (نیاز به خرید دارد)</p>";
    echo "<p style='color: red;'>برای دسترسی به این دوره باید ابتدا آن را خریداری کنید.</p>";
    echo '<a href="cart.php?id=' . $course_id . '" class="buy-button">🛒 افزودن به سبد خرید</a>';
    require 'includes/footer.php';
    exit;
}

// دریافت لیست ویدیوها
$stmt = $pdo->prepare("SELECT * FROM videos WHERE course_id = ? ORDER BY id ASC");
$stmt->execute([$course_id]);
$videos = $stmt->fetchAll();
?>

<h2><?= htmlspecialchars($course['title']) ?></h2>
<p><strong>دسته‌بندی:</strong> <?= htmlspecialchars($course['category_name']) ?></p>
<p><strong>سطح:</strong> <?= translate_level($course['level']) ?></p>

<?php if ($course['thumbnail']): ?>
    <img src="uploads/thumbnails/<?= htmlspecialchars($course['thumbnail']) ?>" alt="Course thumbnail" style="width:300px; margin: 15px 0; border-radius: 8px;">
<?php endif; ?>

<p style="margin-bottom: 30px;"><?= nl2br(htmlspecialchars($course['description'])) ?></p>

<hr>
<h3>🎥 ویدیوهای دوره</h3>

<?php if (!$videos): ?>
    <p>ویدیویی برای این دوره ثبت نشده است.</p>
<?php else: ?>
    <ul>
        <?php foreach ($videos as $video): ?>
            <li>
                <a href="video.php?id=<?= $video['id'] ?>">
                    <?= htmlspecialchars($video['title']) ?> (<?= fa_number($video['duration']) ?> دقیقه)
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<hr>
<h3>💬 نظرات دوره</h3>

<?php
// ثبت نظر دوره
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in()) {
    $comment = trim($_POST['course_comment'] ?? '');
    if ($comment !== '') {
        $stmt = $pdo->prepare("INSERT INTO comments (course_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$course_id, $_SESSION['user']['id'], $comment]);
        header("Location: course.php?id=" . $course_id);
        exit;
    }
}

// گرفتن نظرات دوره از جدول comments
$stmt = $pdo->prepare("
    SELECT c.comment, c.created_at, u.username
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.id = ? AND c.video_id IS NULL
    ORDER BY c.created_at DESC
");
$stmt->execute([$course_id]);
$comments = $stmt->fetchAll();
?>

<?php if (is_logged_in()): ?>
    <form method="post" style="margin-bottom: 20px;">
        <textarea name="course_comment" required placeholder="نظر خود را درباره‌ی این دوره بنویسید..." rows="3" style="width: 100%;"></textarea><br>
        <button type="submit">ارسال نظر</button>
    </form>
<?php else: ?>
    <p>برای ارسال نظر <a href="login.php">وارد شوید</a>.</p>
<?php endif; ?>

<?php if ($comments): ?>
    <ul style="margin-top: 20px;">
        <?php foreach ($comments as $c): ?>
            <li style="margin-bottom: 15px;">
                <strong><?= htmlspecialchars($c['username']) ?>:</strong><br>
                <?= nl2br(htmlspecialchars($c['comment'])) ?><br>
                <span style="font-size: 0.8em; color: gray;">
                    <?= fa_number(convertGregorianToJalali($c['created_at'])) ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>هنوز نظری برای این دوره ثبت نشده است.</p>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
