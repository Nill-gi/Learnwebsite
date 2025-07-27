-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2025 at 03:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `video_learning`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'پایتون');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `video_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `video_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 2, 2, 'عالی', '2025-07-11 19:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `level` enum('beginner','intermediate','advanced') DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `category_id`, `level`, `thumbnail`, `created_by`, `created_at`) VALUES
(2, 'پایتون مقدماتی', 'مقدمات پایتون\r\n16 جلسه آموزشی\r\nآشنایی و آموزش اولیه پایتون', 1, 'beginner', '6870f3344c0cc.jpg', 1, '2025-07-11 11:19:16'),
(3, 'پایتون پیشرفته', 'آموزش پایتون پیشرفته\r\nآشنایی با عملگرها و موارد مهم پایتون', 1, 'advanced', '68729f78449ab.jpg', 1, '2025-07-12 17:46:32');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `video_id` int(11) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`) VALUES
(1, 'fatemehghobodi@gmail.com', 'f68e4766dd14387a658a554b20f7fe4d6aea5604f8270885a6f5319472df7094', '2025-07-18 16:23:22'),
(2, 'fatemehghobodi@gmail.com', 'ada41473a34d649f401e21fdca2893a85ca128ba7007c363c7ed9ffcc8a10f73', '2025-07-18 16:30:13'),
(3, 'fatemehghobodi@gmail.com', '394aacd2447247a5831cc71da97bd88b6d0436894b291b0805b0ebde15a5db51', '2025-07-18 16:30:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin_main','admin_sub') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'learnpro', 'learnpro@gmail.com', '$2y$10$Sw/JkwDmlB6DvYVM3lgmIO4ntoBoeTl2yT0s8GyfknJ1mvMh/KiAa', 'admin_main', '2025-07-11 11:10:58'),
(2, 'ghobadi', 'ghobadi@gmail.com', '$2y$10$6fM.APvvXnYrH.4g2.Zd.ezSPtMxPtz5xpNmEHT3XBBprEx/aZ/S6', 'user', '2025-07-11 11:38:24'),
(3, 'admin2', 'admin2@gmail.com', '$2y$10$hqR4EtGB2rLLg10UGCIrmeo6NhvfFdTe/g9AJniJsqDGBwF0hM6ca', 'admin_sub', '2025-07-11 19:39:02'),
(4, 'fatemeh', 'fatemehghobodi@gmail.com', '123456', 'user', '2025-07-18 13:22:58');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `filepath` varchar(255) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `course_id`, `title`, `description`, `filepath`, `duration`, `created_at`) VALUES
(2, 2, 'E00.introduction - (معرفی دوره)', 'معرفی دوره‌ها', '6871054a53e83.mp4', 9, '2025-07-11 12:36:26'),
(4, 2, 'E01.what is programming - (برنامه نویسی چیست؟)', '(برنامه نویسی چیست؟)', '68729bdfe91bf.mp4', 7, '2025-07-12 17:31:11'),
(5, 3, 'E01.comment docstring - (کامنت و داک استرینگ)', '(کامنت و داک استرینگ)', '6872a099820c3.mp4', 15, '2025-07-12 17:51:21'),
(6, 3, 'E02.indentation - (تو رفتگی)', '(تو رفتگی)', '6872a0ba93c46.mp4', 11, '2025-07-12 17:51:54'),
(7, 3, 'E03.input output - (آشنایی با ورودی و خروجی)', '(آشنایی با ورودی و خروجی)', '6872a10665991.mp4', 21, '2025-07-12 17:53:10'),
(8, 3, 'E04.keywords - (کلمات کلیدی)', '(کلمات کلیدی)', '6872a14fd7e19.mp4', 7, '2025-07-12 17:54:23'),
(9, 3, 'E05.arithmetic operators - (عملگرهای حسابی)', '(عملگرهای حسابی)', '6872a179e2fa1.mp4', 9, '2025-07-12 17:55:05'),
(10, 3, 'E06.comparison operators - (عملگرهای مقایسه)', '(عملگرهای مقایسه)', '6872a1b173494.mp4', 8, '2025-07-12 17:56:01'),
(11, 3, 'E07.assignment operators - (عملگرهای انتساب)', '(عملگرهای انتساب)', '6872a1f083b17.mp4', 6, '2025-07-12 17:57:04'),
(12, 2, 'E02.programming language Levels - (زبان برنامه نویسی سطح بالا، پایین و میانی)', '(زبان برنامه نویسی سطح بالا، پایین و میانی)', '6872a34ba0849.mp4', 10, '2025-07-12 18:02:51'),
(13, 2, 'E03.pro - (زبان های همه منظوره و خاص منظوره)', '(زبان های همه منظوره و خاص منظوره)', '6872a38715351.mp4', 8, '2025-07-12 18:03:51');

-- --------------------------------------------------------

--
-- Table structure for table `video_views`
--

CREATE TABLE `video_views` (
  `id` int(11) NOT NULL,
  `video_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `video_views`
--

INSERT INTO `video_views` (`id`, `video_id`, `user_id`, `viewed_at`) VALUES
(2, 2, 1, '2025-07-11 12:56:25'),
(3, 2, 2, '2025-07-11 19:36:56'),
(4, 2, 2, '2025-07-11 19:37:01'),
(5, 2, 2, '2025-07-11 19:37:01'),
(12, 2, 1, '2025-07-12 17:28:15'),
(13, 2, 1, '2025-07-12 17:28:54'),
(14, 8, 1, '2025-07-12 18:03:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`video_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `video_views`
--
ALTER TABLE `video_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `video_views`
--
ALTER TABLE `video_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `video_views`
--
ALTER TABLE `video_views`
  ADD CONSTRAINT `video_views_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `video_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
