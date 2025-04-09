-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2025 at 08:06 PM
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
-- Database: `ld`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(1, 'J.K. Rowling'),
(2, 'George Orwell'),
(3, 'J.R.R. Tolkien'),
(4, 'F. Scott Fitzgerald'),
(5, 'Harper Lee'),
(6, 'Mark Twain'),
(7, 'Jane Austen'),
(8, 'Stephen King'),
(9, 'Agatha Christie'),
(10, 'C.S. Lewis');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author_id`, `genre_id`, `cover_image`) VALUES
(1, 'Harry Potter and the Philosopher\'s Stone', 1, 1, 'https://res.cloudinary.com/bloomsbury-atlas/image/upload/w_360,c_scale,dpr_1.5/jackets/9781408855652.jpg'),
(2, '1984', 2, 2, 'https://i0.wp.com/www.printmag.com/wp-content/uploads/2017/01/2a34d8_463ff497abfa4bca9b96d696e911746fmv2.jpeg?resize=500%2C769&quality=89&ssl=1'),
(3, 'The Hobbit', 3, 1, 'https://i.ebayimg.com/images/g/HW4AAOSwYDZgjoaO/s-l400.jpg'),
(4, 'The Great Gatsby', 4, 3, 'https://static01.nyt.com/images/2013/04/26/business/Gatsbyjp/Gatsbyjp-superJumbo.jpg'),
(5, 'To Kill a Mockingbird', 5, 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQy8v2jeU2XCCtYwUnV29QoVYBtAFrUNRYFvQ&s'),
(6, 'Adventures of Huckleberry Finn', 6, 3, 'https://cdn2.penguin.com.au/covers/400/9780099572978.jpg'),
(7, 'Pride and Prejudice', 7, 3, 'https://cdn.kobo.com/book-images/08ba5a67-f48d-420e-be8e-6de7a73b7d85/353/569/90/False/pride-prejudice-13.jpg'),
(8, 'The Shining', 8, 4, 'https://m.media-amazon.com/images/I/81CuEX3W9UL._AC_UF1000,1000_QL80_.jpg'),
(9, 'Murder on the Orient Express', 9, 4, 'https://i.pinimg.com/originals/df/53/c9/df53c9f1ac45321580db8173b9976ba0.png'),
(10, 'The Chronicles of Narnia', 10, 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8vg_DqcuniVrH3UxVhvSIT7EJ4OiVBvBLjA&s'),
(11, 'loren as a gangsta', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `genre_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `genre_name`) VALUES
(1, 'Fantasy'),
(2, 'Dystopian'),
(3, 'Classic'),
(4, 'Mystery'),
(5, 'Thriller');

-- --------------------------------------------------------

--
-- Table structure for table `intruders`
--

CREATE TABLE `intruders` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `attempt_count` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `intruders`
--

INSERT INTO `intruders` (`id`, `username`, `attempt_time`, `attempt_count`) VALUES
(1, 'lkjoas', '2025-02-23 19:18:02', 2),
(3, 'opop23', '2025-04-09 15:17:40', 2),
(4, 'sdsa', '2025-04-09 15:34:45', 1),
(5, 'sdsadsadsa', '2025-04-09 15:34:48', 1),
(6, 's', '2025-04-09 16:07:19', 1),
(7, 'sda', '2025-04-09 16:14:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `otp`, `otp_expiry`) VALUES
(1, 'loren', '$2y$10$Wz8/au5sCbd04DDPrHL8buo3uZHqysoXewDxEwajAdkhyaC9rcniq', '2024-11-03 22:41:56', NULL, NULL),
(2, 'opop', '$2y$10$iv8foOsrG0UwoY1hzHiYmeNIAa287OSw0ok1YEaosIhYmIlzVylyq', '2025-02-24 03:10:27', '991837', '2025-04-09 19:14:41'),
(3, 'mama', '$2y$10$TUnE8DG3XWqFpY7JAbhZzOa1q.BwJcv6SqgLwXgTUVSrSTNn7YlPK', '2025-04-09 23:34:15', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `intruders`
--
ALTER TABLE `intruders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `intruders`
--
ALTER TABLE `intruders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
