<?php
// تبدیل تاریخ میلادی به شمسی (با ساعت)
function gregorian_to_jalali($gy, $gm, $gd) {
    $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    $jy = ($gy <= 1600) ? 0 : 979;
    $gy -= ($gy <= 1600) ? 621 : 1600;
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
    $days = (365 * $gy) + (int)(($gy2 + 3) / 4) - (int)(($gy2 + 99) / 100) + (int)(($gy2 + 399) / 400) - 80 + $gd + $g_d_m[$gm - 1];
    $jy += 33 * (int)($days / 12053);
    $days %= 12053;
    $jy += 4 * (int)($days / 1461);
    $days %= 1461;
    if ($days > 365) {
        $jy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }
    $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
    $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
    return [$jy, $jm, $jd];
}

// تابع اصلی نمایش شمسی با زمان
function convertGregorianToJalali($datetime) {
    $timestamp = strtotime($datetime);
    $gy = date('Y', $timestamp);
    $gm = date('m', $timestamp);
    $gd = date('d', $timestamp);
    $h = date('H', $timestamp);
    $i = date('i', $timestamp);
    list($jy, $jm, $jd) = gregorian_to_jalali($gy, $gm, $gd);
    return sprintf("%04d/%02d/%02d %02d:%02d", $jy, $jm, $jd, $h, $i);
}

// تبدیل اعداد انگلیسی به فارسی
function fa_number($input) {
    $en = ['0','1','2','3','4','5','6','7','8','9'];
    $fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    return str_replace($en, $fa, $input);
}

// ترجمه نقش کاربران
function translate_role($role) {
    return match ($role) {
        'admin_main' => 'مدیر اصلی',
        'admin_sub'  => 'مدیر فرعی',
        'user'       => 'کاربر',
        default      => 'نامشخص'
    };
}

// ترجمه سطح دوره
function translate_level($level) {
    return match ($level) {
        'beginner'     => 'مبتدی',
        'intermediate' => 'متوسط',
        'advanced'     => 'پیشرفته',
        default        => 'نامشخص'
    };
}

// ترجمه وضعیت‌ها (مثلاً آینده توسعه)
function translate_status($status) {
    return match ($status) {
        'published' => 'منتشرشده',
        'draft'     => 'پیش‌نویس',
        default     => 'نامشخص'
    };
}
