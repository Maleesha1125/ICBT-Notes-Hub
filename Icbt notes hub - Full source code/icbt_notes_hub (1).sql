-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2025 at 07:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `icbt_notes_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `author` varchar(100) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('available','unavailable') DEFAULT 'available',
  `download_count` int(11) DEFAULT 0,
  `department_id` int(11) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `content_type` enum('lecture_notes','link','quiz','notice') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `reviewed_by` int(11) DEFAULT NULL,
  `review_date` timestamp NULL DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `download_count` int(11) DEFAULT 0,
  `department_id` int(11) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `title`, `description`, `category`, `content_type`, `file_path`, `file_size`, `uploaded_by`, `upload_date`, `status`, `reviewed_by`, `review_date`, `review_notes`, `download_count`, `department_id`, `program_id`, `module_id`) VALUES
(1, 'Maths Unit', 'There is a document for Maths part of CT module.', '', 'lecture_notes', 'uploads/689639457ae20_Math1.1.pdf', NULL, 5, '2025-08-08 17:52:05', 'rejected', 18, '2025-08-12 11:43:29', 'Ms.Waruni Darshika - Information Technology', 0, 1, 2, 1),
(2, 'Maths Part', 'There is a document for Maths part of CT module.', '', '', 'uploads/68963a5fa6491_Math1.1.pdf', NULL, 5, '2025-08-08 17:56:47', 'pending', 18, '2025-08-12 11:34:13', 'Ms.Waruni Darshika - Information Technology', 0, 1, 2, 1),
(3, 'Fundamentals of Digital Computers', 'Fundamentals of Digital Computers - part 2', '', '', 'C:\\xampp\\htdocs\\ICBT  Notes Hub\\public/../uploads/1_Fundamentals of Digital Computers ( Edited).pdf', NULL, 18, '2025-08-12 02:56:17', 'pending', 18, '2025-08-12 04:25:19', 'Ms.Waruni Darshika - Information Technology', 0, NULL, NULL, 4),
(4, 'Laws, Regulactions and Acts in Computing', 'Get idea about Laws, Regulactions and Acts in Computing', '', '', 'C:\\xampp\\htdocs\\Icbt notes hub\\public/../uploads/Laws, Regulactions and Acts in Computing (2) (1).docx', NULL, 18, '2025-08-15 17:14:02', 'rejected', 18, '2025-08-17 10:55:12', 'Ms.Waruni Darshika - Information Technology', 0, NULL, NULL, 1),
(5, 'PHP Programming ', 'PHP Programming document', '', 'lecture_notes', 'uploads/68a1978e48af9_Advance.pptx', NULL, 5, '2025-08-17 08:49:18', 'pending', NULL, NULL, NULL, 0, 1, 2, 6),
(6, 'Object-Oriented Programming in Java', 'Follow this notes and update your knowledge', '', '', 'C:\\xampp\\htdocs\\Icbt notes hub\\public/../uploads/POJ UNIT - 2.pdf', NULL, 18, '2025-08-24 05:35:14', 'pending', NULL, NULL, NULL, 0, NULL, NULL, 3),
(7, ' Introduction to Java Programming, Data Types, and Control Statements', 'Refer this document about last lecture', '', '', 'C:\\xampp\\htdocs\\Icbt notes hub\\public/../uploads/POJ UNIT - 1.pdf', NULL, 68, '2025-08-24 13:53:30', 'pending', NULL, NULL, NULL, 0, NULL, NULL, 3),
(8, 'Arrays - Single and Multidimensional ', 'Document about arrays', '', 'lecture_notes', 'uploads/68acb0dfad1f8_POJ UNIT - 5.pdf', NULL, 5, '2025-08-25 18:52:15', 'pending', NULL, NULL, NULL, 0, 1, 2, 3),
(9, 'Arrays - Single and Multidimensional ', 'Document about arrays got it', '', '', 'uploads/68acfb91553ba_POJ UNIT - 5.pdf', NULL, 5, '2025-08-26 00:10:57', 'rejected', 18, '2025-08-26 00:52:58', 'Ms.Waruni Darshika - Information Technology', 0, 1, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `created_at`) VALUES
(1, 'Information Technology', 'IT', '2025-08-05 13:49:32'),
(3, 'Engineering & Construction', 'ENG', '2025-08-05 13:49:32'),
(4, 'Science', 'SCI', '2025-08-05 13:49:32'),
(5, 'Languages', 'LANG', '2025-08-05 13:49:32'),
(6, 'Law', 'LAW', '2025-08-05 13:49:32'),
(7, 'Business Management', 'BM', '2025-08-09 12:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(11) NOT NULL,
  `content_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `download_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `feedback` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `module_id` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','hidden') DEFAULT 'active',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `name`, `batch`, `department_id`, `program_id`, `feedback`, `rating`, `module_id`, `date`, `status`, `user_id`) VALUES
(4, '', NULL, NULL, NULL, 'As an ICBT student, I am very happy to use this website. It is very useful for all the modules.\r\nThank you', 4, 0, '2025-08-17 18:10:56', 'active', 5),
(6, '', NULL, NULL, NULL, 'Very useful web site. Much needed for our uni studies', 5, 0, '2025-08-19 10:12:50', 'active', 21),
(12, '', NULL, NULL, NULL, 'Excellent web application. Specially this quiz system is very useful because we haven\'t a system to do our program related quizzes and view our score on same time. That quiz system is my favorite part of this web application. Thank you for giving a valuable system for us.', 5, 0, '2025-08-25 18:31:26', 'active', 69);

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `program_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `program_id`, `name`, `code`, `description`) VALUES
(1, 2, 'COMPUTATIONAL THINKING', NULL, 'An introduction to fundamental concepts of computational thinking.'),
(2, 2, 'EXPLORE', NULL, 'Exploration of various topics related to software engineering.'),
(3, 2, 'PROGRAMMING (JAVA)', NULL, 'Fundamentals of programming using the Java language.'),
(4, 2, 'Architectures & Operating Systems (AOS)', NULL, 'Study of computer architecture and operating systems.'),
(6, 2, 'Web Design and Database (WDD)', NULL, 'Introduction to web design and database management.'),
(7, 38, 'Introduction to Marketing', 'BSP4064', 'Basic Unit of Marketing');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `created_at`) VALUES
(1, 5, 'New Notification', 'A new quiz titled **Java Practice Test** has been added for your module: **PROGRAMMING (JAVA)**.', 'info', 1, '2025-08-13 08:14:56'),
(2, 9, 'New Notification', 'A new quiz titled **Java Practice Test** has been added for your module: **PROGRAMMING (JAVA)**.', 'info', 1, '2025-08-13 08:14:56'),
(3, 10, 'New Notification', 'A new quiz titled **Java Practice Test** has been added for your module: **PROGRAMMING (JAVA)**.', 'info', 0, '2025-08-13 08:14:56'),
(4, 12, 'New Notification', 'A new quiz titled **Java Practice Test** has been added for your module: **PROGRAMMING (JAVA)**.', 'info', 0, '2025-08-13 08:14:56'),
(5, 5, 'New Notification', 'A new quiz titled **Java Basics and Identifiers** has been added for your module: **PROGRAMMING (JAVA)**.', 'info', 1, '2025-08-13 08:16:28'),
(6, 9, 'New Notification', 'A new quiz titled **Java Basics and Identifiers** has been added for your module: **PROGRAMMING (JAVA)**.', 'info', 1, '2025-08-13 08:16:28'),
(7, 10, 'New Notification', 'A new quiz titled **Java Basics and Identifiers** has been added for your module: **PROGRAMMING (JAVA)**.', 'info', 0, '2025-08-13 08:16:28'),
(8, 12, 'New Notification', 'A new quiz titled **Java Basics and Identifiers** has been added for your module: **PROGRAMMING (JAVA)**.', 'info', 0, '2025-08-13 08:16:28'),
(9, 5, 'New Quiz', 'A new quiz titled \'Java Basics and Identifiers\' has been added to your module.', 'info', 1, '2025-08-13 08:36:47'),
(10, 9, 'New Quiz', 'A new quiz titled \'Java Basics and Identifiers\' has been added to your module.', 'info', 1, '2025-08-13 08:36:47'),
(11, 10, 'New Quiz', 'A new quiz titled \'Java Basics and Identifiers\' has been added to your module.', 'info', 0, '2025-08-13 08:36:47'),
(12, 12, 'New Quiz', 'A new quiz titled \'Java Basics and Identifiers\' has been added to your module.', 'info', 0, '2025-08-13 08:36:47'),
(13, 5, 'New Notification', 'A new quiz titled **Harvard Referencing** has been added for your module: **EXPLORE**.', 'info', 1, '2025-08-14 15:13:28'),
(14, 9, 'New Notification', 'A new quiz titled **Harvard Referencing** has been added for your module: **EXPLORE**.', 'info', 1, '2025-08-14 15:13:28'),
(15, 10, 'New Notification', 'A new quiz titled **Harvard Referencing** has been added for your module: **EXPLORE**.', 'info', 0, '2025-08-14 15:13:28'),
(16, 12, 'New Notification', 'A new quiz titled **Harvard Referencing** has been added for your module: **EXPLORE**.', 'info', 0, '2025-08-14 15:13:28'),
(17, 5, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 1, '2025-08-14 15:54:31'),
(18, 9, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 1, '2025-08-14 15:54:31'),
(19, 10, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 0, '2025-08-14 15:54:31'),
(20, 12, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 0, '2025-08-14 15:54:31'),
(21, 5, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 1, '2025-08-14 16:06:57'),
(22, 9, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 1, '2025-08-14 16:06:57'),
(23, 10, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 0, '2025-08-14 16:06:57'),
(24, 12, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 0, '2025-08-14 16:06:57'),
(25, 5, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 1, '2025-08-14 16:07:48'),
(26, 9, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 1, '2025-08-14 16:07:48'),
(27, 10, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 0, '2025-08-14 16:07:48'),
(28, 12, 'New Quiz', 'A new quiz titled \'Harvard Referencing\' has been added to your module.', 'info', 0, '2025-08-14 16:07:48'),
(29, 5, 'New Notification', 'A new quiz titled **Simple quiz about  attributes** has been added for your module: **Web Design and Database (WDD)**.', 'info', 1, '2025-08-23 10:18:16'),
(30, 9, 'New Notification', 'A new quiz titled **Simple quiz about  attributes** has been added for your module: **Web Design and Database (WDD)**.', 'info', 0, '2025-08-23 10:18:16'),
(31, 10, 'New Notification', 'A new quiz titled **Simple quiz about  attributes** has been added for your module: **Web Design and Database (WDD)**.', 'info', 0, '2025-08-23 10:18:16'),
(32, 12, 'New Notification', 'A new quiz titled **Simple quiz about  attributes** has been added for your module: **Web Design and Database (WDD)**.', 'info', 0, '2025-08-23 10:18:16'),
(33, 21, 'New Notification', 'A new quiz titled **Simple quiz about  attributes** has been added for your module: **Web Design and Database (WDD)**.', 'info', 0, '2025-08-23 10:18:16'),
(34, 32, 'New Notification', 'A new quiz titled **Simple quiz about  attributes** has been added for your module: **Web Design and Database (WDD)**.', 'info', 0, '2025-08-23 10:18:16'),
(35, 65, 'New Notification', 'A new quiz titled **Simple quiz about  attributes** has been added for your module: **Web Design and Database (WDD)**.', 'info', 1, '2025-08-23 10:18:16'),
(36, 5, 'New Quiz', 'A new quiz titled \'Simple quiz about  attributes\' has been added to your module.', 'info', 1, '2025-08-23 10:21:16'),
(37, 9, 'New Quiz', 'A new quiz titled \'Simple quiz about  attributes\' has been added to your module.', 'info', 0, '2025-08-23 10:21:16'),
(38, 10, 'New Quiz', 'A new quiz titled \'Simple quiz about  attributes\' has been added to your module.', 'info', 0, '2025-08-23 10:21:16'),
(39, 12, 'New Quiz', 'A new quiz titled \'Simple quiz about  attributes\' has been added to your module.', 'info', 0, '2025-08-23 10:21:16'),
(40, 21, 'New Quiz', 'A new quiz titled \'Simple quiz about  attributes\' has been added to your module.', 'info', 0, '2025-08-23 10:21:16'),
(41, 32, 'New Quiz', 'A new quiz titled \'Simple quiz about  attributes\' has been added to your module.', 'info', 0, '2025-08-23 10:21:16'),
(42, 65, 'New Quiz', 'A new quiz titled \'Simple quiz about  attributes\' has been added to your module.', 'info', 1, '2025-08-23 10:21:16'),
(43, 5, 'New Notification', 'A new quiz titled **Quiz || ** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:23:47'),
(44, 9, 'New Notification', 'A new quiz titled **Quiz || ** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:23:47'),
(45, 10, 'New Notification', 'A new quiz titled **Quiz || ** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:23:47'),
(46, 12, 'New Notification', 'A new quiz titled **Quiz || ** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:23:47'),
(47, 21, 'New Notification', 'A new quiz titled **Quiz || ** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:23:47'),
(48, 32, 'New Notification', 'A new quiz titled **Quiz || ** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:23:47'),
(49, 65, 'New Notification', 'A new quiz titled **Quiz || ** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:23:47'),
(50, 5, 'New Quiz', 'A new quiz titled \'Quiz || \' has been added to your module.', 'info', 0, '2025-08-26 02:25:33'),
(51, 9, 'New Quiz', 'A new quiz titled \'Quiz || \' has been added to your module.', 'info', 0, '2025-08-26 02:25:33'),
(52, 10, 'New Quiz', 'A new quiz titled \'Quiz || \' has been added to your module.', 'info', 0, '2025-08-26 02:25:33'),
(53, 12, 'New Quiz', 'A new quiz titled \'Quiz || \' has been added to your module.', 'info', 0, '2025-08-26 02:25:33'),
(54, 21, 'New Quiz', 'A new quiz titled \'Quiz || \' has been added to your module.', 'info', 0, '2025-08-26 02:25:33'),
(55, 32, 'New Quiz', 'A new quiz titled \'Quiz || \' has been added to your module.', 'info', 0, '2025-08-26 02:25:33'),
(56, 65, 'New Quiz', 'A new quiz titled \'Quiz || \' has been added to your module.', 'info', 0, '2025-08-26 02:25:33'),
(57, 5, 'New Notification', 'A new quiz titled **xgcvb** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:31:13'),
(58, 9, 'New Notification', 'A new quiz titled **xgcvb** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:31:13'),
(59, 10, 'New Notification', 'A new quiz titled **xgcvb** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:31:13'),
(60, 12, 'New Notification', 'A new quiz titled **xgcvb** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:31:13'),
(61, 21, 'New Notification', 'A new quiz titled **xgcvb** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:31:13'),
(62, 32, 'New Notification', 'A new quiz titled **xgcvb** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:31:13'),
(63, 65, 'New Notification', 'A new quiz titled **xgcvb** has been added for your module: **COMPUTATIONAL THINKING**.', 'info', 0, '2025-08-26 02:31:13');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `degree_type` enum('diploma','higher_diploma','bachelor','master') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `department_id`, `name`, `duration`, `degree_type`, `created_at`) VALUES
(1, 1, 'BSc (Hons) Data Science', '3 Years', 'bachelor', '2025-08-05 13:49:32'),
(2, 1, 'BSc (Hons) Software Engineering', '3 Years', 'bachelor', '2025-08-05 13:49:32'),
(3, 1, 'BSc (Hons) Information Technology', '3 Years', 'bachelor', '2025-08-05 13:49:32'),
(4, 1, 'BSc (Hons) Business Information Systems', '3 Years', 'bachelor', '2025-08-05 13:49:32'),
(5, 1, 'Bachelor of Science (Honours) in Software Engineering', '4 Years', 'bachelor', '2025-08-05 13:49:32'),
(6, 1, 'Bachelor of Science (Honours) Information Technology in Data Science', '4 Years', 'bachelor', '2025-08-05 13:49:32'),
(7, 1, 'BSc (Hons) Network Systems Engineering', '3 Years', 'bachelor', '2025-08-05 13:49:32'),
(8, 1, 'BSc (Hons) in Information Technology', '3 Years', 'bachelor', '2025-08-05 13:49:32'),
(9, 1, 'Higher Diploma in Computing and Software Engineering', '2 Years', 'higher_diploma', '2025-08-05 13:49:32'),
(10, 1, 'Higher Diploma in Network Technology & Cyber Security', '2 Years', 'higher_diploma', '2025-08-05 13:49:32'),
(17, 3, 'Professional Diploma in Quantity Surveying', '2 Years', 'diploma', '2025-08-05 13:49:32'),
(18, 3, 'Higher Diploma in Automotive Engineering', '2 Years', 'higher_diploma', '2025-08-05 13:49:32'),
(19, 3, 'Higher Diploma in Quantity Surveying', '2 Years', 'higher_diploma', '2025-08-05 13:49:32'),
(20, 3, 'Higher Diploma in Mechanical Engineering', '2 Years', 'higher_diploma', '2025-08-05 13:49:32'),
(21, 3, 'Higher Diploma in Electrical & Electronic Engineering', '2 Years', 'higher_diploma', '2025-08-05 13:49:32'),
(22, 3, 'Higher Diploma in Biomedical Engineering', '2 Years', 'higher_diploma', '2025-08-05 13:49:32'),
(24, 3, 'BEng (Hons) Automotive Engineering', '4 Years', 'bachelor', '2025-08-05 13:49:32'),
(25, 3, 'BEng (Hons) Biomedical Engineering', '4 Years', 'bachelor', '2025-08-05 13:49:32'),
(26, 3, 'BSc (Hons) Civil Engineering', '4 Years', 'bachelor', '2025-08-05 13:49:32'),
(27, 3, 'BEng (Hons) Electronic & Electrical Engineering', '4 Years', 'bachelor', '2025-08-05 13:49:32'),
(28, 3, 'BEng (Hons) Mechanical Engineering', '4 Years', 'bachelor', '2025-08-05 13:49:32'),
(29, 4, 'Higher Diploma in Psychology', '2 Years', 'higher_diploma', '2025-08-05 13:49:32'),
(30, 4, 'Higher Diploma in Biomedical Science', '2 Years', 'higher_diploma', '2025-08-05 13:49:32'),
(31, 4, 'BSc (Hons) in Nursing', '4 Years', 'bachelor', '2025-08-05 13:49:32'),
(32, 4, 'BSc (Hons) in Psychology', '3 Years', 'bachelor', '2025-08-05 13:49:32'),
(33, 4, 'BSc (Hons) in Biomedical Science', '3 Years', 'bachelor', '2025-08-05 13:49:32'),
(34, 5, 'Higher Diploma in English', '2 Years', 'higher_diploma', '2025-08-05 13:49:32'),
(36, 6, 'LLB (Hons) LAW', '3 Years', 'bachelor', '2025-08-05 13:49:32'),
(37, 5, 'BA (Hons) in English', '3', '', '2025-08-19 08:02:26'),
(38, 7, 'Bachelor of Business Management (Hons) – BBM', '4', '', '2025-08-19 09:14:44'),
(39, 3, 'Higher Diploma in Civil Engineering', '2', '', '2025-08-19 09:25:27'),
(40, 5, 'Certificate in Japanese Language', '1', '', '2025-08-23 10:34:47');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `module_id`, `lecturer_id`, `title`, `description`, `duration`, `created_at`) VALUES
(1, 1, 18, 'Computational Thinking Test Questions', '', 15, '2025-08-13 02:26:56'),
(5, 3, 18, 'Java Basics and Identifiers', '', 30, '2025-08-13 08:16:28'),
(6, 2, 18, 'Harvard Referencing', 'How Well Do You Know Harvard Referencing?', 20, '2025-08-14 15:13:28'),
(7, 6, 68, 'Simple quiz about  attributes', 'Do this quiz and improve knowledge about attributes.', 5, '2025-08-23 10:18:16'),
(8, 1, 18, 'Quiz || ', 'Do this and improve knowledge.', 5, '2025-08-26 02:23:47');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_options`
--

CREATE TABLE `quiz_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_options`
--

INSERT INTO `quiz_options` (`id`, `question_id`, `option_text`, `is_correct`) VALUES
(13, 4, 'public static void Main(String[] args)', 0),
(14, 4, 'public void main(String args)', 0),
(15, 4, 'public static void main(String[] args)', 1),
(16, 4, 'private static void main(String args[])', 0),
(17, 5, '_myVar', 0),
(18, 5, '2ndVariable', 1),
(19, 5, '$amount', 0),
(20, 5, 'myVariable123', 0),
(21, 6, '(Smith and Jones, 2020)', 1),
(22, 6, '(Smith, Jones, 2020)', 0),
(23, 6, 'Smith and Jones (2020)', 0),
(24, 6, '(Smith & Jones 2020)', 0),
(25, 7, 'In footnotes on each page', 0),
(26, 7, 'Within the introduction section', 0),
(27, 7, 'At the beginning of the document, before the abstract', 0),
(28, 7, 'At the end of the document, after the main text and before any appendices', 1),
(29, 8, 'Italicized', 1),
(30, 8, 'Underlined', 0),
(31, 8, 'In quotation marks', 0),
(32, 8, 'Bold', 0),
(33, 9, '(Smith, Jones & Brown, 2020)', 0),
(34, 9, '(Smith et al., 2020)', 1),
(35, 9, '(Smith and others, 2020)', 0),
(36, 9, '(Smith, Jones et al., 2020)', 0),
(37, 10, 'No details', 0),
(38, 10, 'No date', 1),
(39, 10, 'New edition', 0),
(40, 10, 'Not determined', 0),
(41, 11, 'No details', 0),
(42, 11, 'No date', 1),
(43, 11, 'New edition', 0),
(44, 11, 'Not determined', 0),
(45, 12, 'Decomposition, pattern recognition, abstraction, algorithm, evaluation', 1),
(46, 12, 'Analysis, synthesis, evaluation, implementation, testing', 0),
(47, 12, 'Identification, categorization, abstraction, execution, review', 0),
(48, 12, 'Decomposition, integration, abstraction, simulation, evaluation', 0),
(49, 13, 'Attributes can only be optional.', 0),
(50, 13, 'Domains are not related to attributes.', 0),
(51, 13, 'Attributes can be simple or composite', 1),
(52, 13, 'Primary keys identify sets of possible values for attributes.', 0),
(53, 14, 'They are implemented in RDBMS.', 0),
(54, 14, 'They should not be implemented in RDBMS.', 1),
(55, 14, 'They are made up of more than one attribute', 0),
(56, 14, 'They can only have one value', 0),
(57, 15, 'To perform logical operations', 0),
(58, 15, 'To represent algorithms visually', 1),
(59, 15, 'To execute Python programs', 0),
(60, 15, 'To store data types', 0);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice') NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question_text`, `question_type`, `points`) VALUES
(4, 5, 'What is the correct way to declare a Java main method?', 'multiple_choice', 2),
(5, 5, 'Which of the following is NOT a valid Java identifier?', 'multiple_choice', 2),
(6, 6, 'What is the general format for an in-text citation for a source with two authors in Harvard referencing?', 'multiple_choice', 2),
(7, 6, 'Where should the reference list be placed in a Harvard-style academic paper?', 'multiple_choice', 2),
(8, 6, 'How should book titles appear in a reference list when using Harvard referencing?', 'multiple_choice', 2),
(9, 6, 'When citing a source with three or more authors in Harvard referencing, which format is correct?', 'multiple_choice', 2),
(10, 6, 'What does \'n.d.\' stand for when used in place of a year in a Harvard reference?', 'multiple_choice', 2),
(11, 6, 'What does \'n.d.\' stand for when used in place of a year in a Harvard reference?', 'multiple_choice', 2),
(12, 1, 'What are the five key steps involved in computational thinking?', 'multiple_choice', 2),
(13, 7, 'Which of the following statements is true about attributes?', 'multiple_choice', 2),
(14, 7, 'Which of the following is true about multi-valued attributes?', 'multiple_choice', 2),
(15, 8, 'What is the purpose of flowcharts in computational thinking and programming?', 'multiple_choice', 2);

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `id` int(11) NOT NULL,
  `submission_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_option_id` int(11) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `score_awarded` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`id`, `submission_id`, `question_id`, `selected_option_id`, `is_correct`, `score_awarded`) VALUES
(7, 4, 4, 13, 0, 0),
(8, 4, 5, 18, 1, 2),
(9, 5, 6, 21, 1, 2),
(10, 5, 7, 27, 0, 0),
(11, 5, 8, 29, 1, 2),
(12, 5, 9, 34, 1, 2),
(13, 6, 4, 15, 1, 2),
(14, 6, 5, 18, 1, 2),
(15, 7, 6, 21, 1, 2),
(16, 7, 7, 28, 1, 2),
(17, 7, 8, 29, 1, 2),
(18, 7, 9, 34, 1, 2),
(19, 7, 10, 37, 0, 0),
(20, 7, 11, 42, 1, 2),
(21, 8, 12, 45, 1, 2),
(22, 9, 13, 51, 1, 2),
(23, 9, 14, 54, 1, 2),
(24, 10, 13, 51, 1, 2),
(25, 10, 14, 53, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_submissions`
--

CREATE TABLE `student_submissions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `total_score` int(11) DEFAULT 0,
  `submission_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_submissions`
--

INSERT INTO `student_submissions` (`id`, `quiz_id`, `student_id`, `total_score`, `submission_time`) VALUES
(1, 1, 5, 0, '2025-08-13 09:06:08'),
(4, 5, 5, 0, '2025-08-14 14:31:01'),
(5, 6, 5, 0, '2025-08-14 15:56:26'),
(6, 5, 32, 0, '2025-08-19 12:11:15'),
(7, 6, 32, 0, '2025-08-19 12:12:27'),
(8, 1, 32, 0, '2025-08-19 12:20:04'),
(9, 7, 65, 0, '2025-08-23 10:28:06'),
(10, 7, 5, 0, '2025-09-23 05:06:29');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'site_name', 'ICBT Notes Hub', '2025-08-05 13:49:33'),
(2, 'max_file_size', '10485760', '2025-08-05 13:49:33'),
(3, 'allowed_file_types', 'pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png', '2025-08-05 13:49:33'),
(4, 'auto_approve_lecturers', '0', '2025-08-05 13:49:33'),
(5, 'email_notifications', '1', '2025-08-05 13:49:33'),
(6, 'maintenance_mode', '0', '2025-08-05 13:49:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','lecturer','admin') NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `first_login` tinyint(1) NOT NULL DEFAULT 0,
  `registration_method` varchar(20) NOT NULL DEFAULT 'self'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `department_id`, `program_id`, `batch`, `student_id`, `created_at`, `status`, `firstname`, `lastname`, `mobile`, `first_login`, `registration_method`) VALUES
(1, 'IcbtAdmin', NULL, '$2y$10$/lxex6SehvIJKho5rVVKdO7kPtysKFm9v2GYq.M6q.bdrJ7teRnbC', 'admin', NULL, NULL, NULL, NULL, '2025-08-09 03:34:17', 'active', 'Admin', 'User', '', 0, 'self'),
(2, 'lecturer1', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecturer', 1, NULL, NULL, NULL, '2025-08-05 13:49:33', 'active', '', '', '', 0, 'self'),
(3, 'student1', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 1, NULL, NULL, NULL, '2025-08-05 13:49:33', 'active', '', '', '', 0, 'self'),
(4, 'chanumis', NULL, '$2y$10$G2gUP3CkGw4UQpzywylItOSRUPlo7x15LPVJIJ9au/eke8vSkEqUK', 'student', 1, 8, NULL, NULL, '2025-08-06 17:00:41', 'inactive', 'Chanumi', 'Sasindi', '0728569874', 0, 'self'),
(5, 'maleeshan', NULL, '$2y$10$fnXzAOCiEU/dsSoR0DEyF.q5953mq83nOYxrza00mh/uZHlKJcaSa', 'student', 1, 2, NULL, NULL, '2025-08-06 17:01:52', 'active', 'Maleesha', 'Nethmini', '0715896478', 0, 'self'),
(6, 'njayaweera', NULL, '$2y$10$jW2vRrsHoCBImYDFbpd4yOrdiojIWQJQIQYem5U0mBwJ4ggwcKw6m', 'student', 5, 34, 'Batch 12', NULL, '2025-08-07 17:37:04', 'active', 'Nethma', 'Jayaweera', '0758648934', 0, 'self'),
(7, 'bmanudini', NULL, '$2y$10$Me4JDq2bxAVKQLX2rihMk.uE/qEdHJesFqVr8qS8GgpbG4Afcfy1a', 'student', NULL, NULL, 'Batch 77', NULL, '2025-08-07 17:49:42', 'active', 'Bihanga', 'Manudini', '0728575964', 0, 'self'),
(8, 'DinudiH', NULL, '$2y$10$LqYcg4xkbDqZwZsdyUMYpeE6XP6tK/yL7lwakyMEN2/ptnIvi1clG', 'student', 4, 29, 'Batch 12', NULL, '2025-08-07 17:53:40', 'active', 'Dinudi', 'Hesanya', '0782569724', 1, 'self'),
(9, 'imandin', NULL, '$2y$10$GTgXa7VsCCoCp68UQ30Y6uk0LXjJuFhfm/G6/ObXdABw09D/ALZ.e', 'student', 1, 2, 'Batch 77', NULL, '2025-08-08 05:45:52', 'active', 'Imandi', 'Nimthara', '757896341', 1, 'self'),
(10, 'sitharab', NULL, '$2y$10$XHLHF8XDsLp8x5h02wCqWusu6AlCok1OVYHdW9pwiwjuRNYpOfIvm', 'student', 1, 2, 'Batch 77', NULL, '2025-08-08 05:51:51', 'active', 'Sithara', 'Basnayake', '785632418', 0, 'self'),
(11, 'thinayat', NULL, '$2y$10$wYcjHw8rWiifqo10aSdI9uxg7Q8X9nvPClxoZwM3WgiSINPtbz1KO', 'student', NULL, NULL, 'Batch 111', NULL, '2025-08-08 05:55:00', 'active', 'Thinaya', 'Thasheli', '781593674', 0, 'self'),
(12, 'rusiruw', NULL, '$2y$10$Zq8YarytVQ/H7hPnwbrjfO1PP06Z4G9dTs0h4oXtUoK.M3TraTsim', 'student', 1, 2, 'Batch 06', NULL, '2025-08-08 07:18:28', 'active', 'Rusiru', 'Weerawardhana', '75894125', 1, 'self'),
(15, 'VimangaP', 'vimangaP.icbt@gmail.com', '$2y$10$w14jxjZigJVPp9hzSMcD4.z2FA9LC9T9Exl7iOn7D2izwYEAB/sV.', 'lecturer', 1, 2, NULL, NULL, '2025-08-10 16:03:56', 'active', 'Mr.Vimanga', 'Perera', '758267594', 1, 'self'),
(17, 'BihangaJ', 'BihangaM.icbt@gmail.com', '$2y$10$.fJuJx1njsNQyqVzoZtF4espl0SK5LwqwcJJ1FtR8DNRp98bes1cy', 'lecturer', 1, 2, NULL, NULL, '2025-08-11 05:42:15', 'active', 'Ms.Bihanga ', 'Jayasiri', '712587456', 1, 'admin'),
(18, 'WaruniD', 'warunid.icbt@gmail.com', '$2y$10$kzbNK.59zCUUae/YNZaDN.dC9eHOeR9Lnw9TLOnu1swsUp0Usf9yG', 'lecturer', 1, 2, NULL, NULL, '2025-08-11 06:38:35', 'active', 'Ms.Waruni', 'Darshika', '758413296', 0, 'admin'),
(19, 'MalithP', 'malithp1010.icbt@gmail.com', '$2y$10$kpE3orlJaBi7x57xrNz26e/x9GUD3OI5vzYbcpiLxC4bwlvxGh1ka', 'lecturer', 3, 27, NULL, NULL, '2025-08-18 11:19:43', 'active', 'Mr.Malith', 'Prageeth', '715985214', 0, 'admin'),
(20, 'NilanthiS', 'nilanthis05.icbt@gmail.com', '$2y$10$7OIadVBIXCdrvI4O.eMNCekuB4A5xooD.IjB5DQ4idIg3CkRszaaa', 'lecturer', 5, NULL, NULL, NULL, '2025-08-18 11:37:23', 'active', 'Mrs.Nilanthi', 'Subasinghe', '758369845', 0, 'admin'),
(21, 'SandeepaniA', 'sandi159abc@gmail.com', '$2y$10$Xh1sTShdrulNuiOwzuJy/OpXCPTrmNYqEjia3GXLY6gg6DXpyuNT6', 'student', 1, 2, NULL, NULL, '2025-08-18 11:48:21', 'active', 'Sandeepani', 'Abeyrathne', '758254726', 0, 'admin'),
(22, 'NadunJ', 'nadunj1.icbt@gmail.com', '$2y$10$aSv/I/zprVIlTHNFNdZblOY343X67Brkk88NbYcsO7kz9/Tx2bTAK', 'lecturer', 6, 36, NULL, NULL, '2025-08-18 12:12:57', 'active', 'Nadun', 'Jayarathne', '752836974', 0, 'admin'),
(23, 'CrismiU', 'crismi159udana@gmail.com', '$2y$10$An/SsgXCw8Q2TqOAV6SLXOj/786bJtNrt9c3E28M5oUTEmv.PF4sa', 'student', 3, 25, NULL, NULL, '2025-08-18 12:14:30', 'active', 'Crismi', 'Udana', '752589634', 0, 'admin'),
(29, 'nipunis', NULL, '$2y$10$LzimQRrYEo8NoFTg9x7zZe6cwbTW0mvjXUEICCoIQ7UxyV.E.wdSW', 'student', 7, 38, '0', NULL, '2025-08-19 11:36:06', 'active', 'Nipuni', 'Shashipraba', '0751596324', 0, 'self'),
(30, 'nayomis', NULL, '$2y$10$8Eh8A2Wh6ufyF93FGvOnjOUuhGSSS99hsfng37/VTtY4wYNC6EeRC', 'student', 7, 38, '0', NULL, '2025-08-19 11:37:00', 'active', 'Nayomi', 'Senadhipathi', '0758963147', 0, 'self'),
(31, 'teshanid', NULL, '$2y$10$y62GstG63LPHwNqBBnZ/q.d78u.X66lJOgXeTdIeforC.gQjOnSnq', 'student', 7, 38, '0', NULL, '2025-08-19 11:42:09', 'active', 'Teshani', 'Devindi', '0758964123', 0, 'self'),
(32, 'nadund', NULL, '$2y$10$7dSs4YG0dAzmgmq6Dok.6.A/QwxEBvoTFRHFMgHHOTxhkEZpq50pa', 'student', 1, 2, '0', NULL, '2025-08-19 12:10:07', 'active', 'Nadun', 'Devinda', '0758236974', 1, 'self'),
(33, 'UviniM', 'uvini123madara@gmail.com', '$2y$10$.Gk1jInhMixRyHHuh4CWEuVGr56JBE0sJkaQyV4NCRvXQ.wzs8xAe', 'student', 3, 22, NULL, NULL, '2025-08-22 11:32:11', 'active', 'Uvini', 'Madara', '751269872', 0, 'admin'),
(34, 'KavinduC', 'kavindu1010chiran@gmail.com', '$2y$10$x6dOeJWHTFzyXBYGd3N8beyJPEtUmzYU4LDgYada1jJiGwmKZEA.S', 'student', 7, 38, NULL, NULL, '2025-08-22 11:47:09', 'active', 'Kavindu', 'Chiran', '758236974', 0, 'admin'),
(35, 'ThivonaO', 'thivona159ona@gmail.com', '$2y$10$KtpuoI9SI8YPJJOwZ4ulu.5OZxIgx/q54BGq1pEOOUKEfuwCu1HZG', 'student', 3, 22, NULL, NULL, '2025-08-22 15:42:40', 'active', 'Thivona', 'Onadi', '758269874', 1, 'admin'),
(36, 'RavinduL', 'ravindu1111lak@gmail.com', '$2y$10$V11gtYNfO7kX/7pvtvaiXOK3oK3nKDuE7.J4OoYJPa2qT1IWVra72', 'student', 1, 9, NULL, NULL, '2025-08-22 16:26:42', 'active', 'Ravindu', 'Lakshan', '758964125', 0, 'admin'),
(37, 'EmmaT', 'emma123ab@gmail.com', '$2y$10$Y3mRy9b/3qdtj8.c5ZiWhOfUzqcGyqo1l8vlmFtZVqi.RW29qa9y.', 'student', 5, 37, NULL, NULL, '2025-08-22 17:22:59', 'active', 'Emma', 'Thilakarathne', '712589632', 1, 'admin'),
(38, 'tashinis', NULL, '$2y$10$njOJ.n0wzONnj9Bac33ZHe.RVMDZj9oXKahmW6J0KbTL981PFkiku', 'student', 1, 8, '0', NULL, '2025-08-22 17:25:05', 'active', 'Tashini', 'Shawana', '0752589641', 0, 'self'),
(39, 'tharakid', NULL, '$2y$10$LOeNoBLaLMEu1ojEePrxdO.JZvWM5NXl8ScIjxap9EiYLkAcYv/8.', 'student', 7, 38, '0', NULL, '2025-08-22 17:31:38', 'active', 'Tharaki', 'Dissanayake', '0758963247', 0, 'self'),
(40, 'senurin', NULL, '$2y$10$lo9l/IL1CcKPBi2REXe2yOP8ZDUBtI6.QoeaFlArRAnIoeOWK6Hia', 'student', 1, 3, '0', NULL, '2025-08-22 17:36:14', 'active', 'Senuri', 'Nathaliya', '0718523698', 0, 'self'),
(41, 'IsuriN', 'isuri12nakandala@gmail.com', '$2y$10$XpMJYIc0nTvc3ceueBy1rekzeU1WRqqfN0.WyW1XbGsT0873p4jGO', 'student', 1, 8, NULL, NULL, '2025-08-22 17:39:39', 'active', 'Isuri', 'Nakandala', '7258964125', 1, 'admin'),
(42, 'RashmiP', 'rashmi123p@gmail.com', '$2y$10$zmrn/OUnc98GYJPMw8CP4.ftSG3uBPHps0/T1l2c8AhIpvdmZXCI2', 'student', 3, 18, NULL, NULL, '2025-08-23 01:38:56', 'active', 'Rashmi', 'Perera', '752859631', 0, 'admin'),
(43, 'ManjariS', 'manji123sew@gmail.com', '$2y$10$t/OwAyzFmQIKCGOJZQotku0y4G5XS0vjBdg5cq3GivZdn6oWgkP7y', 'student', 3, 25, NULL, NULL, '2025-08-23 01:53:19', 'active', 'Manjari', 'Sewmini', '712589634', 0, 'admin'),
(44, 'DewniP', 'dewni12p@gmail.com', '$2y$10$3jDl03JGDWEKSKUUW5rzP.rJAuPnHbF5Dy801mY0kfdkDE6Ep.jgG', 'student', 1, 3, NULL, NULL, '2025-08-23 02:05:33', 'active', 'Dewni', 'Prashansa', '752589634', 0, 'admin'),
(45, 'NaduniJ', 'nadu159jay@gmail.com', '$2y$10$GnGs2swMd/Ic/SOG5P2NmOr/5z/4YpiHerqZ9ML.c/Wp5OPuL2zH6', 'student', 7, 38, NULL, NULL, '2025-08-23 02:21:24', 'active', 'Naduni', 'Jayasinghe', '712589632', 1, 'admin'),
(59, 'MaleeshaP', 'maleesha123p@gmail.com', '$2y$10$a8llP25NP7vnyS5bhs4HpuQbjLHYXUJ3Uv/Unun4VropsiDOYQoX.', 'student', 1, 3, NULL, NULL, '2025-08-23 06:45:10', 'active', 'Maleesha', 'Pathum', '725841369', 1, 'admin'),
(64, 'imashin', NULL, '$2y$10$GXaLHo9AncGqE7LK2lmw7uJORoTC/Pb7ChJCPoTx4uHjIGbtwEBTG', 'student', 7, 38, '0', NULL, '2025-08-23 09:58:49', 'active', 'Imashi', 'Nethmini', '0751236957', 0, 'self'),
(65, 'OShadiU', 'oshadi164udagedara@gmail.com', '$2y$10$oeV7JoU0ZVVw19dPxrBmiuY6AwwhlqPfl9qhuBxduT/ppPnsVXiwe', 'student', 1, 2, NULL, NULL, '2025-08-23 10:02:11', 'active', 'Oshadi', 'Udagedara', '751428574', 0, 'admin'),
(66, 'AmaliJ', 'amali158jay@gmail.com', '$2y$10$NLTzHw9kRF1RTGTUGo4ORO3rL0DSUhuN2jzGnS7yhOqf1eTGiVxqi', 'student', 1, 4, NULL, NULL, '2025-08-23 10:05:20', 'active', 'Amali', 'Jayawardhana', '752148963', 0, 'admin'),
(67, 'DilkiJ', 'dilki11j@gmail.com', '$2y$10$kMk83g6R2JJFpS9kc3YVQO9ApaBYn8T50U/mCcULW69E5ZEI5.PD.', 'lecturer', 1, 8, NULL, NULL, '2025-08-23 10:07:46', 'active', 'Ms. Dilki ', 'Jayawardhana', '751423654', 0, 'admin'),
(68, 'SachithraJ', 'sachi1123jay@gmail.com', '$2y$10$9j0OZ5UXqOYCaoOd2fygQu5Unejd4tIabwUPLUKCDaJOJiG/MkXK.', 'lecturer', 1, 2, NULL, NULL, '2025-08-23 10:11:13', 'active', 'Ms.Sachithra', 'Jayawardhana', '772584136', 0, 'admin'),
(69, 'ovinis', NULL, '$2y$10$pxI8hfbuYVHC2K8J0JldAuba15IREkmA0orP7k40KTSa4FgjjKioy', 'student', 3, 18, '0', NULL, '2025-08-25 17:06:07', 'active', 'Ovini', 'Sandeepani', '0758964127', 0, 'self'),
(70, 'ChathuR', 'chathu159r@gmail.com', '$2y$10$hQilGTc0hKWBz30pRN644eotB7lsaHCgZwBQts8sJXZz7DNIqWcRm', 'student', 3, 26, NULL, NULL, '2025-08-26 04:13:28', 'inactive', 'Chathu', 'Rajapaksha', '758241364', 1, 'admin'),
(71, 'NaduniW', 'naduni123wiicbt@gmail.com', '$2y$10$kEtTbwndtxvSQJrMYJdvQOwSLLDb8dCWAbLP.euZwIHPxe2ZvS3Zm', 'lecturer', 1, 2, NULL, NULL, '2025-08-30 18:55:46', 'active', 'Ms. Naduni ', 'Wickramasinghe', '775478247', 0, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_modules`
--

CREATE TABLE `user_modules` (
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content_id` (`content_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `quiz_options`
--
ALTER TABLE `quiz_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_id` (`submission_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `selected_option_id` (`selected_option_id`);

--
-- Indexes for table `student_submissions`
--
ALTER TABLE `student_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `user_modules`
--
ALTER TABLE `user_modules`
  ADD PRIMARY KEY (`user_id`,`module_id`),
  ADD KEY `fk_user_modules_module` (`module_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `quiz_options`
--
ALTER TABLE `quiz_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `student_submissions`
--
ALTER TABLE `student_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `books_ibfk_3` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `content_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `content_ibfk_2` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `content_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `content_ibfk_4` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `content_ibfk_5` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `downloads`
--
ALTER TABLE `downloads`
  ADD CONSTRAINT `downloads_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `downloads_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `downloads_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `feedbacks_ibfk_3` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `links_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `links_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `links_ibfk_3` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`),
  ADD CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `quiz_options`
--
ALTER TABLE `quiz_options`
  ADD CONSTRAINT `quiz_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `student_submissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_ibfk_3` FOREIGN KEY (`selected_option_id`) REFERENCES `quiz_options` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `student_submissions`
--
ALTER TABLE `student_submissions`
  ADD CONSTRAINT `student_submissions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`),
  ADD CONSTRAINT `student_submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_modules`
--
ALTER TABLE `user_modules`
  ADD CONSTRAINT `fk_user_modules_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_modules_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
