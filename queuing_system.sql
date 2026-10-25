-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2026 at 08:15 AM
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
-- Database: `queuing_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-heart@tcc|::1', 'i:1;', 1792767300),
('laravel-cache-heart@tcc|::1:timer', 'i:1792767300;', 1792767300),
('laravel-cache-junelyn@tcc.om|::1', 'i:1;', 1792768046),
('laravel-cache-junelyn@tcc.om|::1:timer', 'i:1792768046;', 1792768046),
('laravel-cache-maria@tcc.com|::1', 'i:1;', 1792416150),
('laravel-cache-maria@tcc.com|::1:timer', 'i:1792416150;', 1792416150);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `window_number` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`, `window_number`, `created_at`, `updated_at`) VALUES
(1, 'BSIT', 'BS information technology', '01', '2026-10-19 06:10:41', '2026-10-19 06:10:41'),
(2, 'BSBA', 'BS bussines ads', '02', '2026-10-19 07:27:48', '2026-10-19 07:27:48'),
(3, 'BSCRIM', 'Criminology', '03', '2026-10-21 22:51:29', '2026-10-21 22:51:29');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_28_082746_create_queues_table', 2),
(5, '2026_10_19_055321_add_window_number_to_queues_table', 3),
(6, '2026_10_19_060420_create_courses_table', 4),
(7, '2026_10_19_061448_create_staff_table', 4),
(8, '2026_10_19_064238_add_window_to_users_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queues`
--

CREATE TABLE `queues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue_number` varchar(255) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `transaction` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'waiting',
  `window_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `queues`
--

INSERT INTO `queues` (`id`, `queue_number`, `student_name`, `student_id`, `transaction`, `status`, `window_number`, `created_at`, `updated_at`) VALUES
(1, 'A-101', 'joel capin', '098765', 'Enrollment Validation, Document Request, Registration Concern, Records Correction [Window 01]', 'skipped', NULL, '2026-10-18 21:57:26', '2026-10-19 05:14:17'),
(2, 'A-102', 'kyle guigue', '0987665', 'Enrollment Validation, Document Request, Registration Concern, Records Correction', 'skipped', '01', '2026-10-19 06:14:01', '2026-10-19 06:18:01'),
(3, 'A-103', 'kyle guigue', '098761', 'Enrollment Validation, Document Request, Registration Concern, Records Correction', 'completed', '01', '2026-10-19 06:43:41', '2026-10-19 06:48:16'),
(4, 'A-104', 'karl lagrada', '0088778', 'Enrollment Validation, Registration Concern', 'completed', '01', '2026-10-19 06:47:37', '2026-10-19 06:51:56'),
(5, 'A-105', 'joel capin', '01829831', 'Enrollment Validation', 'skipped', '01', '2026-10-19 06:47:57', '2026-10-19 06:53:27'),
(6, 'A-106', 'karl lagrada', '0989657', 'Enrollment Validation, Document Request, Registration Concern, Records Correction', 'completed', '01', '2026-10-19 06:54:06', '2026-10-19 06:57:42'),
(7, 'A-107', 'kyle guigue', '0961213', 'Enrollment Validation', 'completed', '01', '2026-10-19 06:54:40', '2026-10-19 06:59:25'),
(8, 'A-108', 'heart nadunza', '098765', 'Enrollment Validation, Registration Concern', 'completed', '01', '2026-10-19 06:55:05', '2026-10-19 07:03:44'),
(9, 'A-109', 'junelyn sagayno', '09615', 'Records Correction', 'completed', '01', '2026-10-19 06:55:34', '2026-10-19 07:06:42'),
(10, 'A-110', 'joel capin', '90788678', 'Enrollment Validation', 'completed', '01', '2026-10-19 07:06:36', '2026-10-19 07:12:24'),
(11, 'A-111', 'joel capin', '123456', 'Enrollment Validation, Registration Concern', 'completed', '01', '2026-10-19 07:12:18', '2026-10-19 07:16:25'),
(12, 'A-112', 'joel capin', '976645654', 'Registration Concern', 'skipped', '01', '2026-10-19 07:16:17', '2026-10-19 07:17:12'),
(13, 'A-113', 'joel capin', '897857', 'Registration Concern, Other Concern', 'completed', '01', '2026-10-19 07:17:38', '2026-10-19 07:20:38'),
(14, 'A-114', 'joel capin', '028389', 'Enrollment Validation, Registration Concern', 'completed', '01', '2026-10-19 07:20:33', '2026-10-19 07:24:51'),
(15, 'A-115', 'karl lagrada', '998879', 'Enrollment Validation, Registration Concern', 'completed', '01', '2026-10-19 07:22:42', '2026-10-21 22:55:34'),
(16, 'A-116', 'kyle guigue', '098775665', 'Enrollment Validation, Registration Concern', 'completed', '02', '2026-10-19 07:28:30', '2026-10-21 22:48:44'),
(17, 'A-117', 'joel capin', '090980', 'Enrollment Validation, Document Request, Registration Concern', 'completed', '01', '2026-10-19 07:55:43', '2026-10-21 23:02:32'),
(18, 'A-101', 'joel capin', '093991', 'Enrollment Validation, Registration Concern', 'waiting', '03', '2026-10-21 22:53:59', '2026-10-21 22:53:59'),
(19, 'A-102', 'joel capin', '123445', 'Registration Concern, Other Concern', 'completed', '01', '2026-10-21 22:58:56', '2026-10-21 23:03:07'),
(20, 'A-103', 'karl lagrada', '6432345', 'Registration Concern, Records Correction, Other Concern', 'completed', '01', '2026-10-21 22:59:26', '2026-10-21 23:04:09'),
(21, 'A-104', 'junelyn sagayno', '243532', 'Enrollment Validation, Registration Concern', 'completed', '01', '2026-10-21 23:00:03', '2026-10-21 23:04:25'),
(22, 'A-105', 'kyle guigue', '09878', 'Enrollment Validation, Registration Concern, Other Concern', 'completed', '01', '2026-10-21 23:00:35', '2026-10-21 23:04:36'),
(23, 'A-106', 'heart nadunza', '098776', 'Registration Concern, Records Correction, Other Concern', 'completed', '01', '2026-10-21 23:01:07', '2026-10-21 23:04:55'),
(24, 'A-107', 'junelyn sagayno', '099391', 'Enrollment Validation, Registration Concern, Other Concern', 'completed', '02', '2026-10-21 23:08:30', '2026-10-23 06:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9HwGso76Sa9MVe6f3ffmuKzMNxCHBpnFIhPcQDGi', 10, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTmNkaHZOWENQY1BoUk92dXg0YlVjNEZDdmIwUEFPTXdnOFBjZ2hyQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9sb2NhbGhvc3QvcXVldWluZ19zeXN0ZW0vcHVibGljL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTA7fQ==', 1792763819),
('a7k0wtXKG5np6mN2UlLhyJUzf7kfxNlYC5vHEv7l', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiN09DWDJLZ0tPUG9GV0xtTzJWZ3ZOUUVWMDROeWdDc3RNV1FRb21CUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdGFmZiI7czo1OiJyb3V0ZSI7czoxNToic3RhZmYuZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MTp7aTowO3M6NjoiZXJyb3JzIjt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMDtzOjY6ImVycm9ycyI7TzozMToiSWxsdW1pbmF0ZVxTdXBwb3J0XFZpZXdFcnJvckJhZyI6MTp7czo3OiIAKgBiYWdzIjthOjE6e3M6NzoiZGVmYXVsdCI7TzoyOToiSWxsdW1pbmF0ZVxTdXBwb3J0XE1lc3NhZ2VCYWciOjI6e3M6MTE6IgAqAG1lc3NhZ2VzIjthOjE6e3M6NToiZW1haWwiO2E6MTp7aTowO3M6NTA6Ik5vIHdpbmRvdyBhc3NpZ25lZCB0byB0aGlzIGFjY291bnQuIENvbnRhY3QgQWRtaW4uIjt9fXM6OToiACoAZm9ybWF0IjtzOjg6IjptZXNzYWdlIjt9fX19', 1792765819),
('AI7cuOw86nEGfDw7e14I3eTmRquAqiErmq3q0AsQ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibkNoNEwyc0pEZDBpcXI1bHVpT2s0UDFOdHc4TzNQek52aUNqWXN3NSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hY2NvdW50cyI7czo1OiJyb3V0ZSI7czoxNDoiYWRtaW4uYWNjb3VudHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1792910700),
('C3MYqQLrjFqgTwLDpuz0pPswEQZeQpZ3n7NMM67d', 10, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ2NORUZ1WDhGaElRdTM4MmFwZFk5cG5TOWtaYXQ1ZHRqTkRidVZvWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9sb2NhbGhvc3QvcXVldWluZ19zeXN0ZW0vcHVibGljL3N0YWZmIjtzOjU6InJvdXRlIjtzOjE1OiJzdGFmZi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMDt9', 1792768097),
('dy7GNVn01reHWL4d1KvueSCa2XUWBhRqlAJv8Y9f', 10, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQTdUQVJLTUx0QzdmeU9MUHg3U1ZibGtDdTZhNjFVYmlVVkJRME1pQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9sb2NhbGhvc3QvcXVldWluZ19zeXN0ZW0vcHVibGljL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTA7fQ==', 1792761471),
('fZiIcUfhFE8Kd8m6J3EbQRpxNjU01m6PoifSDIVD', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOHNEbWxsUTgzd29MSGZEa3JKTUE1UHJ0RTYyZERwUXlPd3kyTTBhdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdGFmZiI7czo1OiJyb3V0ZSI7czoxNToic3RhZmYuZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MTp7aTowO3M6NjoiZXJyb3JzIjt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMDtzOjY6ImVycm9ycyI7TzozMToiSWxsdW1pbmF0ZVxTdXBwb3J0XFZpZXdFcnJvckJhZyI6MTp7czo3OiIAKgBiYWdzIjthOjE6e3M6NzoiZGVmYXVsdCI7TzoyOToiSWxsdW1pbmF0ZVxTdXBwb3J0XE1lc3NhZ2VCYWciOjI6e3M6MTE6IgAqAG1lc3NhZ2VzIjthOjE6e3M6NToiZW1haWwiO2E6MTp7aTowO3M6NTA6Ik5vIHdpbmRvdyBhc3NpZ25lZCB0byB0aGlzIGFjY291bnQuIENvbnRhY3QgQWRtaW4uIjt9fXM6OToiACoAZm9ybWF0IjtzOjg6IjptZXNzYWdlIjt9fX19', 1792765113),
('l1rj6XTANqVv6ppWRZBoK8r02kXsQK9xspIFqU4N', 14, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU3JHTnJSZ1AwVHRQTUlxaDVwbDEzVnVoZTk1bHhCYzk1cHNET0kzdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9sb2NhbGhvc3QvcXVldWluZ19zeXN0ZW0vcHVibGljL3N0YWZmIjtzOjU6InJvdXRlIjtzOjE1OiJzdGFmZi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxNDt9', 1792767834),
('VaMsjbiKghEy1wuKvLPJVR8msAA0Eqrq7btydHwI', 14, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic09ZYXhvMW9RNjdrRTdHYUE4blF3aktERHZxMTJuREtBTnA5OHNPSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly9sb2NhbGhvc3QvcXVldWluZ19zeXN0ZW0vcHVibGljL2Rpc3BsYXkiO3M6NToicm91dGUiO3M6NzoiZGlzcGxheSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE0O30=', 1792768544);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `window_number` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `window_number` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `window_number`, `is_admin`) VALUES
(1, 'Admin', 'admin@tcc.com', NULL, '$2y$12$NkqmSapxBilgI31a.NoqlefzUiDWJTBLvGRr0936YIW0oyR4YkJJG', NULL, '2026-10-19 04:48:50', '2026-10-19 05:23:53', NULL, 1),
(10, 'Joel Capin', 'joel@tcc.com', NULL, '$2y$12$SWbtSLXSoBzlBaraxLdgSeVh1TA/OGWl5O2aFmT9oDOc7sXrZllbW', NULL, '2026-10-23 05:02:27', '2026-10-23 06:42:34', '05', 0),
(11, 'junelyn', 'junelyn@tcc.com', NULL, '$2y$12$vJthk1fLSmu5V1.ylJZBPe8.Vw85HhbzEt7Icgtj9CVA8oco5QrpS', NULL, '2026-10-23 05:03:22', '2026-10-23 06:41:50', '02', 0),
(12, 'karl', 'karl@tcc.com', NULL, '$2y$12$h/jll6Qkjt0IJ4qDX32ncuQTZaYjY6CWoKaKl9fdpjMwZ9i6uFV2u', NULL, '2026-10-23 05:03:44', '2026-10-23 06:41:49', '01', 0),
(14, 'heart', 'heart@tcc.com', NULL, '$2y$12$0psOrWIXC2zi6uR76moAeeUEzvpGgREstQxBgoP.cesygkPu7KjAe', NULL, '2026-10-23 06:52:59', '2026-10-23 07:02:59', '04', 0),
(15, 'kyle', 'kyle@tcc.com', NULL, '$2y$12$5lFzxpJQ.TdIfkpjhXCqR.bgkmCcCqDdXadcWR7W4PiV0zKgLQima', NULL, '2026-10-23 06:53:36', '2026-10-23 07:02:56', '03', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `queues`
--
ALTER TABLE `queues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `queues`
--
ALTER TABLE `queues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
