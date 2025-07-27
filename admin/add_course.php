<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/locale.php'; 
require '../includes/header.php';

require_admin_sub();

$errors = [];
$success = false;

// خواندن دسته‌بندی‌ها برای فرم
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $level = $_POST['level'] ?? '';
    $cat_id = $_POST['category'] ?? '';
    $user_id = $_SESSION['user']['id'];
    $thumbnail = $_FILES['thumbnail'] ?? null;

    if (!$title || !$desc || !$level || !$cat_id) {
        $errors[] = 'تمام فیلدها الزامی هستند.';
    }

    // آپلود کاور
    if ($thumbnail && $thumbnail['error'] === 0) {
        $ext = pathinfo($thumbnail['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $path = '../uploads/thumbnails/' . $filename;
        move_uploaded_file($thumbnail['tmp_name'], $path);
    } else {
        $filename = null;
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO courses (title, description, category_id, level, thumbnail, created_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $desc, $cat_id, $level, $filename, $user_id]);
        $success = true;
    }
}
?>

<h2>➕ افزودن دوره جدید</h2>

<?php if ($success): ?>
    <p style="color: green;">✅ دوره با موفقیت ثبت شد.</p>
<?php endif; ?>

<?php if ($errors): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>عنوان دوره:
        <input type="text" name="title" required>
    </label><br><br>

    <label>توضیحات:
        <textarea name="description" required></textarea>
    </label><br><br>

    <label>سطح دوره:
        <select name="level" required>
            <option value="beginner">مبتدی</option>
            <option value="intermediate">متوسط</option>
            <option value="advanced">پیشرفته</option>
        </select>
    </label><br><br>

    <label>دسته‌بندی:
        <select name="category" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>تصویر کاور:
        <input type="file" name="thumbnail" accept="image/*">
    </label><br><br>

    <button type="submit">ثبت دوره</button>
</form>

<?php require '../includes/footer.php'; ?>
