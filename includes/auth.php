<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function is_logged_in() {
    return isset($_SESSION['user']);
}

function is_admin_main() {
    return is_logged_in() && $_SESSION['user']['role'] === 'admin_main';
}

function is_admin_sub() {
    return is_logged_in() && in_array($_SESSION['user']['role'], ['admin_main', 'admin_sub']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /login.php');
        exit;
    }
}

function require_admin_main() {
    if (!is_admin_main()) {
        http_response_code(403);
        exit('⛔ دسترسی غیرمجاز');
    }
}

function require_admin_sub() {
    if (!is_admin_sub()) {
        http_response_code(403);
        exit('⛔ فقط مدیران اجازه ورود دارند');
    }
}
