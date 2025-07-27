<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/locale.php'; 
require '../includes/header.php';

require_admin_main(); // فقط مدیر اصلی

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die('دوره نامعتبر است.');
}

// دریافت اطلاعات دوره
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch();

if (!$course) {
    die('دوره یافت نشد.');
}

// گرفتن دسته‌بندی‌ها
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// ویرایش
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $level = $_POST['level'];
    $cat_id = $_POST['category'];

    $stmt = $pdo->prepare("UPDATE courses SET title = ?, description = ?, level = ?, category_id = ? WHERE id = ?");
    $stmt->execute([$title, $desc, $level, $cat_id, $id]);

    header("Location: manage_courses.php");
    exit;
}
?>

<h2>✏️ ویرایش دوره</h2>

<form method="post">
    <label>عنوان:
        <input type="text" name="title" value="<?= htmlspecialchars($course['title']) ?>" required>
    </label><br><br>

    <label>توضیحات:
        <textarea name="description" required><?= htmlspecialchars($course['description']) ?></textarea>
    </label><br><br>

    <label>سطح:
        <select name="level">
            <option value="beginner" <?= $course['level'] == 'beginner' ? 'selected' : '' ?>>مبتدی</option>
            <option value="intermediate" <?= $course['level'] == 'intermediate' ? 'selected' : '' ?>>متوسط</option>
            <option value="advanced" <?= $course['level'] == 'advanced' ? 'selected' : '' ?>>پیشرفته</option>
        </select>
    </label><br><br>

    <label>دسته‌بندی:
        <select name="category">
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $course['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <button type="submit">ذخیره تغییرات</button>
</form>

<?php require '../includes/footer.php'; ?>
