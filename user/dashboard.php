<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/locale.php'; 
require '../includes/header.php';

require_login();
$user_id = $_SESSION['user']['id'];

// لیست دوره‌های خریداری‌شده (از SESSION)
$unlocked_courses = $_SESSION['unlocked_courses'] ?? [];

// گرفتن اطلاعات دوره‌های خریداری‌شده
$purchased_courses = [];
if (!empty($unlocked_courses)) {
    $in = implode(',', array_fill(0, count($unlocked_courses), '?'));
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id IN ($in)");
    $stmt->execute($unlocked_courses);
    $purchased_courses = $stmt->fetchAll();
}

// لیست دوره‌هایی که کاربر دیده (video_views → videos → courses)
$stmt = $pdo->prepare("
    SELECT DISTINCT c.id, c.title
    FROM video_views vv
    JOIN videos v ON vv.video_id = v.id
    JOIN courses c ON v.course_id = c.id
    WHERE vv.user_id = ?
");
$stmt->execute([$user_id]);
$watched_courses = $stmt->fetchAll();

// گرفتن نظرات کاربر (فقط روی ویدیوها فعلاً)
$stmt = $pdo->prepare("
    SELECT c.comment, c.created_at, v.title AS video_title
    FROM comments c
    JOIN videos v ON c.video_id = v.id
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
");
$stmt->execute([$user_id]);
$comments = $stmt->fetchAll();
?>

<link rel="stylesheet" href="/Learn-web/assets/css/style.css">

<div class="dashboard">
    <h2>👤 پنل کاربری <?= htmlspecialchars($_SESSION['user']['username']) ?></h2>

    <div class="card-grid">

        <div class="card">
            <h3>📦 دوره‌های خریداری‌شده</h3>
            <?php if ($purchased_courses): ?>
                <ul>
                    <?php foreach ($purchased_courses as $course): ?>
                        <li>
                            <a href="/Learn-web/course.php?id=<?= $course['id'] ?>">
                                <?= htmlspecialchars($course['title']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>دوره‌ای خریداری نکردید.</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3>👁 دوره‌های دیده‌شده</h3>
            <?php if ($watched_courses): ?>
                <ul>
                    <?php foreach ($watched_courses as $course): ?>
                        <li>
                            <a href="/Learn-web/course.php?id=<?= $course['id'] ?>">
                                <?= htmlspecialchars($course['title']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>تا حالا دوره‌ای تماشا نکردید.</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3>💬 نظرات من</h3>
            <?php if ($comments): ?>
                <ul style="font-size: 0.9em;">
                    <?php foreach ($comments as $c): ?>
                        <li>
                            <strong><?= htmlspecialchars($c['video_title']) ?>:</strong><br>
                            <?= nl2br(htmlspecialchars($c['comment'])) ?><br>
                            <span style="color: gray; font-size: 0.8em;">
                                <?= fa_number(convertGregorianToJalali($c['created_at'])) ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>هنوز نظری ثبت نکردید.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require '../includes/footer.php'; ?>
