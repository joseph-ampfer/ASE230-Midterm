-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 03:17 AM
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
-- Database: `u_collab`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Comments for posts, allowing nested comments.';

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `parent_id`, `comment`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, NULL, 'I think this is very cool.', '2024-11-11 22:26:05', '2024-11-11 22:26:05', NULL),
(3, 1, 3, 1, 'I agree Jackson!', '2024-11-11 22:26:37', '2024-11-11 22:26:37', NULL),
(4, 1, 1, NULL, 'Go check out my new post as well!', '2024-11-11 22:27:08', '2024-11-11 22:27:08', NULL),
(23, 24, 7, NULL, 'hello', '2024-12-09 21:15:04', '2024-12-09 21:15:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `looking_for`
--

CREATE TABLE `looking_for` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `role` varchar(254) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='What role the post needs for the project. When a post is created, role will be inserted';

--
-- Dumping data for table `looking_for`
--

INSERT INTO `looking_for` (`id`, `post_id`, `role`, `created_at`, `updated_at`) VALUES
(55, 1, 'Data Scientist', '2024-12-09 17:55:51', '2024-12-09 17:55:51'),
(56, 1, 'Machine Learning Specialist', '2024-12-09 17:55:51', '2024-12-09 17:55:51'),
(57, 1, 'UI Designer', '2024-12-09 17:55:51', '2024-12-09 17:55:51'),
(58, 2, 'Machine Learning Engineer', '2024-12-09 17:56:19', '2024-12-09 17:56:19'),
(59, 2, 'AI Specialist', '2024-12-09 17:56:19', '2024-12-09 17:56:19'),
(60, 2, 'Data Scientist', '2024-12-09 17:56:19', '2024-12-09 17:56:19'),
(61, 10, 'Front-End Developer', '2024-12-09 18:42:31', '2024-12-09 18:42:31'),
(62, 10, 'Back-End Developer', '2024-12-09 18:42:31', '2024-12-09 18:42:31'),
(63, 10, 'Full Stack Developer', '2024-12-09 18:42:31', '2024-12-09 18:42:31'),
(64, 11, 'Game Developer', '2024-12-09 18:44:12', '2024-12-09 18:44:12'),
(76, 23, 'Back-End Developer', '2024-12-09 19:40:38', '2024-12-09 19:40:38'),
(77, 24, 'Game Developer', '2024-12-09 20:48:45', '2024-12-09 20:48:45');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(254) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(2000) DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Posts made by users to show the projects/startups they are working on';

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `description`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Rocket Ship to the Moon', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ante sem, molestie eget pulvinar in, aliquet at nulla. Mauris porttitor urna id tempor auctor. Nam quis mi non diam fermentum aliquet. Vestibulum dictum convallis feugiat. Ut libero libero, fringilla eu neque ut, posuere consequat massa. In facilisis enim eget massa pellentesque, non accumsan mi condimentum. Aenean in ligula in est placerat faucibus. Curabitur rutrum nunc at neque consequat porta. Pellentesque dignissim pharetra mauris at imperdiet. Cras facilisis laoreet porttitor. Praesent aliquet, libero a fermentum eleifend, nulla nunc luctus diam, in faucibus nisl justo ac risus. Etiam est arcu, ullamcorper vitae diam non, rutrum feugiat nulla. Vestibulum ac laoreet diam.\\r\\n\\r\\nPhasellus eu enim lorem. Integer luctus luctus nisl a bibendum. Integer eu purus vulputate, dignissim sem vitae, rhoncus erat. Sed euismod dapibus ligula eget consectetur. Aliquam auctor laoreet elit vel convallis. Etiam sodales lacus non cursus vulputate. Quisque vel tempus enim, at rhoncus ligula. Vivamus quis vulputate dui. Donec molestie velit odio. Vestibulum sed odio porttitor, imperdiet enim eu, mollis augue. Interdum et malesuada fames ac ante ipsum primis in faucibus. Fusce venenatis elementum ipsum ut consequat. Nullam ex turpis, elementum sed scelerisque eget, elementum non lacus. Phasellus ut augue ac sem blandit pharetra.\\r\\n\\r\\nVivamus vitae mi pellentesque, aliquet nunc id, ultricies leo. Vestibulum ut quam vel mi dapibus mollis. Nunc lobortis posuere ultricies. Maecenas in dapibus lacus. Etiam nec urna congue, finibus mauris ut, dignissim turpis. Fusce pellentesque pellentesque nisi id condimentum. Proin risus lorem, iaculis at sem et, convallis dictum risus. Proin tincidunt dui urna, in tincidunt metus euismod eu. Morbi vitae tristique mi. Proin orci diam, pretium id erat ut, varius vehicula erat. In euismod vel tellus a interdum. Suspendisse urna metus, sollicitudin sit amet lacus eget, porta semper nibh.                                                                                                                                                                                            ', '.\\/assets\\/images\\/blog\\/670a960f63770.jpeg', 'published', '2024-11-11 22:21:53', '2024-11-11 22:21:53', NULL),
(2, 2, 'AI Blogging System', 'Had an insightful discussion on AI in education today!', 'assets\\/images\\/blog\\/2.jpg', 'published', '2024-11-11 22:21:53', '2024-11-11 22:21:53', NULL),
(10, 3, 'Post on someone elses account', 'Trying this new feature', './assets/images/blog/1733787751.jpg', 'published', '2024-12-09 18:42:31', '2024-12-09 18:42:31', NULL),
(11, 3, 'Another Emily John post', 'Trying it again\r\n\r\nwith spaces.', './assets/images/blog/1733787852.png', 'published', '2024-12-09 18:44:12', '2024-12-09 18:44:12', NULL),
(23, 4, 'ddsf', 'sdf', './assets/images/blog/1733791238.JPG', 'published', '2024-12-09 19:40:38', '2024-12-09 19:40:38', NULL),
(24, 4, 'New test', 'sdaf', './assets/images/blog/1733795325.png', 'published', '2024-12-09 20:48:45', '2024-12-09 20:48:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_categories`
--

CREATE TABLE `post_categories` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `category` varchar(254) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Each post''s categories. When a post is created, the categories will be inserted.';

--
-- Dumping data for table `post_categories`
--

INSERT INTO `post_categories` (`id`, `post_id`, `category`, `created_at`, `updated_at`) VALUES
(63, 1, 'Engineering', '2024-12-09 17:55:51', '2024-12-09 17:55:51'),
(64, 1, 'Robotics', '2024-12-09 17:55:51', '2024-12-09 17:55:51'),
(65, 2, 'Web Development', '2024-12-09 17:56:19', '2024-12-09 17:56:19'),
(66, 2, 'Mobile Apps', '2024-12-09 17:56:19', '2024-12-09 17:56:19'),
(67, 2, 'Game Development', '2024-12-09 17:56:19', '2024-12-09 17:56:19'),
(68, 10, 'Mobile Apps', '2024-12-09 18:42:31', '2024-12-09 18:42:31'),
(69, 10, 'Game Development', '2024-12-09 18:42:31', '2024-12-09 18:42:31'),
(70, 10, 'Data Science', '2024-12-09 18:42:31', '2024-12-09 18:42:31'),
(71, 11, 'AI Projects', '2024-12-09 18:44:12', '2024-12-09 18:44:12'),
(83, 23, 'Data Science', '2024-12-09 19:40:38', '2024-12-09 19:40:38'),
(84, 24, 'Mobile Apps', '2024-12-09 20:48:45', '2024-12-09 20:48:45'),
(85, 24, 'Game Development', '2024-12-09 20:48:45', '2024-12-09 20:48:45');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Likes for posts, keeping track of who liked the post.';

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(1, 1, 1, '2024-11-11 22:23:34'),
(2, 1, 2, '2024-11-11 22:23:34'),
(3, 1, 3, '2024-11-11 22:23:34'),
(5, 2, 4, '2024-12-09 17:15:51'),
(6, 23, 4, '2024-12-09 20:41:05'),
(7, 11, 4, '2024-12-09 20:41:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password_hash` char(60) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `picture` varchar(2000) DEFAULT './assets/images/profile_icon.png',
  `major` varchar(64) DEFAULT NULL,
  `short_bio` text DEFAULT NULL,
  `social_link` varchar(100) DEFAULT NULL,
  `recovery_code` char(60) DEFAULT NULL,
  `recovery_code_created_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isAdmin` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='User accounts with authentication and profile info';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `firstname`, `lastname`, `picture`, `major`, `short_bio`, `social_link`, `recovery_code`, `recovery_code_created_at`, `created_at`, `updated_at`, `isAdmin`) VALUES
(1, 'jampfer@gmail.com', 'asdfasdfasdfasdf', 'Joey', 'Ampfer', './assets/images/profile_pictures/1733709310.jpg', NULL, 'Hello world.', 'linkedin.com/joey', NULL, '2024-12-08 20:55:10', '2024-11-11 22:16:44', '2024-12-08 20:55:10', 0),
(2, 'jackson@nku.edu', 'asdfasdfasdfasdf', 'Jackson', 'Simon', './assets/images/profile_pictures/1733709232.jpg', NULL, '', '', NULL, '2024-12-08 20:53:52', '2024-11-11 22:16:44', '2024-12-08 20:53:52', 0),
(3, 'emily@nku.edu', 'asdfasdfasdfasdf', 'Emily', 'John', './assets/images/profile_icon.png', 'Biology', 'I like bio.', 'www.emily.com', NULL, '2024-12-04 19:16:45', '2024-11-11 22:17:41', '2024-12-04 19:16:45', 0),
(4, 'josephampfer@gmail.com', '$2y$10$G7Qqicrd..IMwIH91dz/o.gc3iiJupQHu2bLuD7Bnl3BUNAE4st66', 'Joseph', 'Ampfer', './assets/images/profile_pictures/1733790966.webp', 'Applied Software Engineering', 'This is my short biobb', 'https://github.com/joseph-ampfer/ASE230-Midterm/', NULL, '2024-12-09 19:36:06', '2024-11-26 11:42:40', '2024-12-09 19:36:06', 1),
(5, 'test@gmail.com', '$2y$10$TX9kPaRZGMykX2BJOxPlQOKYJluDAItrgO9yH.X1gP5Urouie1bvm', 'Joseph', 'Ampfer', './assets/images/profile_icon.png', NULL, NULL, NULL, NULL, '2024-12-04 19:16:45', '2024-12-03 09:50:10', '2024-12-04 19:16:45', 0),
(6, 'notadmin@gmail.com', '$2y$10$bkXat3I3.1cSplPRQrjtaONVkWHvNO2CRW.DzA2t7MZ9pLeNFx2P2', 'test', 'notAdmin', './assets/images/profile_pictures/1733708918.jpg', 'Nursing', 'work plssakfjsadkjlfaadsjadsfksadfjksadsf', 'workkkkk', NULL, '2024-12-08 20:48:38', '2024-12-06 20:05:21', '2024-12-08 20:48:38', 0),
(7, 'caporusso@gmail.com', '$2y$10$I1RxDq4n4zSY35pVgt3CoOgjhwheXvRh4A8CXxR.c4ZJ6vr4pF3ji', 'Nicholas', 'Caporusso', './assets/images/profile_icon.png', NULL, NULL, NULL, NULL, NULL, '2024-12-09 20:58:12', '2024-12-09 20:58:12', 0),
(8, 'd@gmail.com', '$2y$10$XLWOwmoEyxBxvQLBAg.LGuIcLGSPZJGzE/N/hTxsNCaOIX/DeNmeK', 'd', 'd', './assets/images/profile_icon.png', NULL, NULL, NULL, NULL, NULL, '2024-12-09 21:13:59', '2024-12-09 21:13:59', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `looking_for`
--
ALTER TABLE `looking_for`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_categories`
--
ALTER TABLE `post_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_id_2` (`post_id`,`user_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `looking_for`
--
ALTER TABLE `looking_for`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `post_categories`
--
ALTER TABLE `post_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`);

--
-- Constraints for table `looking_for`
--
ALTER TABLE `looking_for`
  ADD CONSTRAINT `looking_for_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `post_categories`
--
ALTER TABLE `post_categories`
  ADD CONSTRAINT `post_categories_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
