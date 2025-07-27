<?php
require 'includes/db.php';
require 'includes/locale.php';
require 'includes/header.php';

// گرفتن دسته‌بندی‌ها برای فیلتر
$all_categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// دریافت پارامترهای جستجو
$search = $_GET['search'] ?? '';
$cat = $_GET['category'] ?? '';
$level = $_GET['level'] ?? '';

// ساخت کوئری فیلترشده
$sql = "
    SELECT c.*, cat.name AS category_name
    FROM courses c
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE 1
";
$params = [];

if ($search) {
    $sql .= " AND c.title LIKE ?";
    $params[] = "%$search%";
}

if ($cat) {
    $sql .= " AND c.category_id = ?";
    $params[] = $cat;
}

if ($level) {
    $sql .= " AND c.level = ?";
    $params[] = $level;
}

$sql .= " ORDER BY c.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();
?>

<h2>🎓 دوره‌های آموزشی</h2>

<form method="get" style="margin-bottom: 30px;">
    <input type="text" name="search" placeholder="جستجو بر اساس عنوان..." value="<?= htmlspecialchars($search) ?>">

    <select name="category">
        <option value="">دسته‌بندی</option>
        <?php foreach ($all_categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $cat == $c['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="level">
        <option value="">سطح</option>
        <option value="beginner" <?= $level == 'beginner' ? 'selected' : '' ?>>مبتدی</option>
        <option value="intermediate" <?= $level == 'intermediate' ? 'selected' : '' ?>>متوسط</option>
        <option value="advanced" <?= $level == 'advanced' ? 'selected' : '' ?>>پیشرفته</option>
    </select>

    <button type="submit">جستجو</button>
</form>

<?php if (!$courses): ?>
    <p>هیچ دوره‌ای یافت نشد.</p>
<?php endif; ?>

<div class="course-list">
    <?php foreach ($courses as $course): ?>
        <div class="course-card">
            <?php if ($course['thumbnail']): ?>
                <img src="uploads/thumbnails/<?= htmlspecialchars($course['thumbnail']) ?>" alt="Course Image">
            <?php endif; ?>
            <h3><?= htmlspecialchars($course['title']) ?></h3>
            <p>دسته‌بندی: <?= htmlspecialchars($course['category_name']) ?></p>
            <p>سطح: <?= translate_level($course['level']) ?></p>
            <p><a href="course.php?id=<?= $course['id'] ?>">🎥 مشاهده دوره</a></p>
        </div>
    <?php endforeach; ?>
</div>

<link rel="stylesheet" href="assets/css/style.css">
<?php require 'includes/footer.php'; ?>
