CREATE DATABASE IF NOT EXISTS `bookstore` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `bookstore`;

-- MySQL dump for a bookstore database
-- Host: localhost    Database: bookstore_management
-- ------------------------------------------------------
-- Server version	8.0.33

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Table structure for `categories` (for books)

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
CREATE TABLE `categories` (
  `id_category` INT NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- Insert categories (fiction, non-fiction, etc.)
LOCK TABLES `categories` WRITE;
INSERT INTO `categories` VALUES 
(1, 'Fiction'),
(2, 'Non-fiction'),
(3, 'Science Fiction'),
(4, 'Fantasy'),
(5, 'Biography'),
(6, 'Self-help'),
(7, 'Romance'),
(8, 'Mystery'),
(9, 'Historical Fiction'),
(10, 'Children\'s Books');
UNLOCK TABLES;

-- Table structure for `books`

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
CREATE TABLE `books` (
  `id_book` INT NOT NULL AUTO_INCREMENT,
  `id_category` INT NOT NULL,
  `book_title` VARCHAR(100) NOT NULL,
  `book_description` TEXT NOT NULL,
  `book_price` DECIMAL(6,2) NOT NULL,
  `book_image` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id_book`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- Insert book data (example books with prices, categories, etc.)
LOCK TABLES `books` WRITE;
INSERT INTO `books` VALUES 
(1, 1, 'The Great Adventure', 'An epic tale of love and loss.', 19.99, '../assets/img/books/the_great_adventure.jpg'),
(2, 2, 'Becoming a Better You', 'A self-help guide for personal growth.', 14.99, '../assets/img/books/becoming_a_better_you.jpg'),
(3, 3, 'The Future of Humanity', 'Explore the possibilities of life in space.', 24.99, '../assets/img/books/the_future_of_humanity.jpg'),
(4, 4, 'Dragons of the Ancient World', 'A thrilling fantasy filled with mythical creatures.', 22.99, '../assets/img/books/dragons_of_the_ancient_world.jpg'),
(5, 5, 'The Life of Albert Einstein', 'A biography of one of the greatest minds in history.', 29.99, '../assets/img/books/the_life_of_albert_einstein.jpg'),
(6, 6, 'The Power of Positive Thinking', 'A guide to transforming your mindset.', 18.99, '../assets/img/books/the_power_of_positive_thinking.jpg'),
(7, 7, 'Love in the Time of Fireflies', 'A romantic novel set in a small town.', 15.99, '../assets/img/books/love_in_the_time_of_fireflies.jpg'),
(8, 8, 'The Mysterious Island', 'A gripping mystery full of twists and turns.', 17.99, '../assets/img/books/the_mysterious_island.jpg'),
(9, 9, 'The Past Lives of History', 'A historical account of significant past events.', 19.99, '../assets/img/books/the_past_lives_of_history.jpg'),
(10, 10, 'The Wonderful World of Animals', 'A fun and educational book for children.', 12.99, '../assets/img/books/the_wonderful_world_of_animals.jpg');
UNLOCK TABLES;

-- Table structure for `authors` (for books' authors)

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
CREATE TABLE `authors` (
  `id_author` INT NOT NULL AUTO_INCREMENT,
  `id_book` INT NOT NULL,
  `author_name` VARCHAR(100) NOT NULL,
  `author_image` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_author`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- Insert author data (Example authors)
LOCK TABLES `authors` WRITE;
INSERT INTO `authors` VALUES 
(1, 1, 'John Smith', '../assets/img/authors/john_smith.jpg'),
(2, 2, 'Jane Doe', '../assets/img/authors/jane_doe.jpg'),
(3, 3, 'Richard Harris', '../assets/img/authors/richard_harris.jpg'),
(4, 4, 'J.K. Rowling', '../assets/img/authors/jk_rowling.jpg'),
(5, 5, 'Isaac Newton', '../assets/img/authors/isaac_newton.jpg'),
(6, 6, 'Norman Vincent Peale', '../assets/img/authors/norman_vincent_peale.jpg'),
(7, 7, 'Susan Collins', '../assets/img/authors/susan_collins.jpg'),
(8, 8, 'Agatha Christie', '../assets/img/authors/agatha_christie.jpg'),
(9, 9, 'William Shakespeare', '../assets/img/authors/william_shakespeare.jpg'),
(10, 10, 'Dr. Seuss', '../assets/img/authors/dr_seuss.jpg');
UNLOCK TABLES;

-- Final adjustments
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-22 13:29:04
