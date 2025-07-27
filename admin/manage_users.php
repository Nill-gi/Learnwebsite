<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/locale.php'; 
require '../includes/header.php';

require_admin_main(); // فقط مدیر اصلی

$success = null;

// تغییر نقش کاربر
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    $valid_roles = ['user', 'admin_sub', 'admin_main'];
    if (in_array($new_role, $valid_roles)) {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$new_role, $user_id]);
        $success = true;
    } else {
        $success = false;
    }
}

// دریافت لیست کاربران
$users = $pdo->query("SELECT id, username, email, role FROM users ORDER BY id DESC")->fetchAll();
?>

<h2>⚙️ مدیریت نقش کاربران</h2>

<?php if ($success === true): ?>
    <p style="color: green;">✅ نقش با موفقیت تغییر کرد.</p>
<?php elseif ($success === false): ?>
    <p style="color: red;">❌ نقش نامعتبر انتخاب شده است.</p>
<?php endif; ?>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-top: 20px;">
    <tr style="background: #f3f3f3;">
        <th>شناسه</th>
        <th>نام کاربری</th>
        <th>ایمیل</th>
        <th>نقش فعلی</th>
        <th>تغییر نقش</th>
    </tr>

    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= fa_number($user['id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= translate_role($user['role']) ?></td>
            <td>
                <form method="post" style="display: flex; gap: 10px;">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <select name="role">
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>کاربر</option>
                        <option value="admin_sub" <?= $user['role'] === 'admin_sub' ? 'selected' : '' ?>>ادمین فرعی</option>
                        <option value="admin_main" <?= $user['role'] === 'admin_main' ? 'selected' : '' ?>>ادمین اصلی</option>
                    </select>
                    <button type="submit">ذخیره</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require '../includes/footer.php'; ?>
