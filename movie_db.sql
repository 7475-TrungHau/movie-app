-- --------------------------------------------------------
-- Máy chủ:                      127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Phiên bản:           12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for movie_db
CREATE DATABASE IF NOT EXISTS `movie_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `movie_db`;

-- Dumping structure for table movie_db.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table movie_db.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table movie_db.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_url` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.categories: ~3 rows (approximately)
DELETE FROM `categories`;
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `thumbnail_url`, `created_at`, `updated_at`) VALUES
	(1, 'Movie', 'movie', NULL, NULL, '2025-04-03 11:33:49', '2025-04-04 05:59:25'),
	(2, 'Anime', 'anime', NULL, NULL, '2025-04-03 11:33:50', '2025-04-03 11:33:53'),
	(3, 'Series', 'series', NULL, NULL, '2025-04-03 11:34:11', '2025-04-03 11:34:12');

-- Dumping structure for table movie_db.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `movie_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `root_id` bigint unsigned DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_movie_id_foreign` (`movie_id`),
  KEY `comments_parent_id_foreign` (`parent_id`),
  KEY `comments_root_id_foreign` (`root_id`),
  CONSTRAINT `comments_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `comments_root_id_foreign` FOREIGN KEY (`root_id`) REFERENCES `comments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.comments: ~0 rows (approximately)
DELETE FROM `comments`;

-- Dumping structure for table movie_db.episodes
CREATE TABLE IF NOT EXISTS `episodes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `movie_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_date` datetime DEFAULT NULL,
  `episode_number` int unsigned NOT NULL,
  `thumbnail_url` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `episodes_movie_id_foreign` (`movie_id`),
  CONSTRAINT `episodes_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.episodes: ~105 rows (approximately)
DELETE FROM `episodes`;
INSERT INTO `episodes` (`id`, `movie_id`, `title`, `description`, `video_url`, `release_date`, `episode_number`, `thumbnail_url`, `slug`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Tập 01', 'Tập 1. Một khởi đầu đầy kịch tính tại trường học khi virus lạ bắt đầu lan rộng.', 'https://s4.phim1280.tv/20250325/15U0OSx5/index.m3u8', '2025-04-06 08:30:23', 1, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-01', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(2, 1, 'Tập 02', 'Tập 2. Các học sinh phải đối mặt với sự thật kinh hoàng và tìm cách sinh tồn.', 'https://s4.phim1280.tv/20250325/L13mtaK3/index.m3u8', '2025-04-06 08:30:23', 2, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-02', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(3, 1, 'Tập 03', 'Tập 3. Sự hỗn loạn gia tăng, những quyết định khó khăn được đưa ra.', 'https://s4.phim1280.tv/20250325/xqyp5Z1I/index.m3u8', '2025-04-06 08:30:23', 3, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-03', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(4, 1, 'Tập 04', 'Tập 4. Mất mát và hy vọng đan xen khi nhóm tìm đường thoát.', 'https://s4.phim1280.tv/20250325/urYLPIR6/index.m3u8', '2025-04-06 08:30:23', 4, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-04', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(5, 1, 'Tập 05', 'Tập 5. Những mối nguy hiểm mới xuất hiện từ cả bên trong lẫn bên ngoài.', 'https://s4.phim1280.tv/20250325/PzPUQ6vI/index.m3u8', '2025-04-06 08:30:23', 5, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-05', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(6, 1, 'Tập 06', 'Tập 6. Kế hoạch được vạch ra, nhưng liệu có thành công?', 'https://s4.phim1280.tv/20250325/BqradtcC/index.m3u8', '2025-04-06 08:30:23', 6, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-06', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(7, 1, 'Tập 07', 'Tập 7. Đối mặt với tình huống ngặt nghèo, tình bạn bị thử thách.', 'https://s4.phim1280.tv/20250325/NOt6t0Kl/index.m3u8', '2025-04-06 08:30:23', 7, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-07', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(8, 1, 'Tập 08', 'Tập 8. Phát hiện quan trọng và những hy sinh đau đớn.', 'https://s4.phim1280.tv/20250325/7lkLmHTd/index.m3u8', '2025-04-06 08:30:23', 8, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-08', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(9, 1, 'Tập 09', 'Tập 9. Cuộc chiến sinh tồn ngày càng khốc liệt hơn.', 'https://s4.phim1280.tv/20250325/CX7skR5r/index.m3u8', '2025-04-06 08:30:23', 9, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-09', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(10, 1, 'Tập 10', 'Tập 10. Niềm tin lung lay và những bí mật được hé lộ.', 'https://s4.phim1280.tv/20250325/BceIVv5Y/index.m3u8', '2025-04-06 08:30:23', 10, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-10', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(11, 1, 'Tập 11', 'Tập 11. Chuẩn bị cho trận chiến cuối cùng.', 'https://s4.phim1280.tv/20250325/maF3oplG/index.m3u8', '2025-04-06 08:30:23', 11, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-11', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(12, 1, 'Tập 12', 'Tập 12. Cái kết cho cuộc chiến tại trường học và tương lai nào đang chờ đợi?', 'https://s4.phim1280.tv/20250325/YbkatJrM/index.m3u8', '2025-04-06 08:30:23', 12, 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 'tap-12', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(13, 2, 'Full', 'Xem phim Batman Ninja Đối Đầu Liên Minh Yakuza (Full).', 'https://s4.phim1280.tv/20250328/XzEQJhgt/index.m3u8', '2025-04-06 08:30:23', 1, 'https://phimimg.com/upload/vod/20250328-1/7e743bf2f82e6a4383f9add3ac5fdec0.jpg', 'full', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(14, 3, 'Full', 'Xem phim Nhà Tù Shawshank (Full).', 'https://s2.phim1280.tv/20231019/SRNpBmu7/index.m3u8', '2025-04-06 08:30:23', 1, 'https://phimimg.com/upload/vod/20231018-1/e31eee0d4db3c8a1b194e76a36ca26b9.jpg', 'full', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(15, 4, 'Full', 'Xem phim Kẻ Đánh Cắp Giấc Mơ (Full).', 'https://s5.phim1280.tv/20240911/qTL6xlyr/index.m3u8', '2025-04-06 08:30:23', 1, 'https://phimimg.com/upload/vod/20231018-1/ea2205d6cdedccf1251422eb4fa85f6d.jpg', 'full', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(16, 5, 'Full', 'Xem phim Bố Già (Full).', 'https://s2.phim1280.tv/20231017/sO7PyfCM/index.m3u8', '2025-04-06 08:30:23', 1, 'https://phimimg.com/upload/vod/20231014-1/8274cbb886030eda232520b329dc2bbb.jpg', 'full', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(17, 6, 'Full', 'Xem phim Chuyện Tào Lao (Full).', 'https://s3.phim1280.tv/20240618/VbXKq0OU/index.m3u8', '2025-04-06 08:30:23', 1, 'https://phimimg.com/upload/vod/20231019-1/d8cb231f36481392329c958e2204eb66.jpg', 'full', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(18, 7, 'Full', 'Xem phim Cuộc Đời Forrest Gump (Full).', 'https://s2.phim1280.tv/20230923/MxarzCw0/index.m3u8', '2025-04-06 08:30:23', 1, 'https://phimimg.com/upload/vod/20230923-1/496cbec2d5df42fa0c816a7a9ee12206.jpg', 'full', '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(19, 8, 'Full', 'Xem phim Kỵ Sĩ Bóng Đêm (Full).', 'https://s2.phim1280.tv/20240120/Cy7c7Mg8/index.m3u8', '2025-04-06 08:30:26', 1, 'https://phimimg.com/upload/vod/20240104-1/b2748e456090a1c43b246c014b60fa1a.jpg', 'full', '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(20, 9, 'Full', 'Xem phim Sàn Đấu Sinh Tử (Full).', 'https://s1.phim1280.tv/20231017/YDJU9iz0/index.m3u8', '2025-04-06 08:30:26', 1, 'https://phimimg.com/upload/vod/20231014-1/11c145a6361f8e063d653eaee6f592dd.jpg', 'full', '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(21, 10, 'Full', 'Xem phim Ma Trận (Full).', 'https://s5.phim1280.tv/20240911/Fg8j0dZu/index.m3u8', '2025-04-06 08:30:26', 1, 'https://phimimg.com/upload/vod/20231014-1/19e1af28cf91bac65637b7089ac3a6a6.jpg', 'full', '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(22, 11, 'Full', 'Xem phim Ký Sinh Trùng (Full).', 'https://s4.phim1280.tv/20250223/FEEhXOjr/index.m3u8', '2025-04-06 08:30:26', 1, 'https://phimimg.com/upload/vod/20250223-1/197144573d8adb78f386f0ab58682903.jpg', 'full', '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(23, 12, 'Full', 'Xem phim Hố Đen Tử Thần (Full).', 'https://s4.phim1280.tv/20250302/eUGu2byt/index.m3u8', '2025-04-06 08:30:26', 1, 'https://phimimg.com/upload/vod/20250302-1/6012710a981edf1b6209bdb7fa82d1a6.jpg', 'full', '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(24, 13, 'Full', 'Xem phim Võ Sĩ Giác Đấu (Full).', 'https://s1.phim1280.tv/20231017/SkHyXmPv/index.m3u8', '2025-04-06 08:30:26', 1, 'https://phimimg.com/upload/vod/20231014-1/64d398a360904eaf83a9a5b98e599849.jpg', 'full', '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(25, 14, 'Full', 'Xem phim Chúa Tể Của Những Chiếc Nhẫn: Sự Trở Lại Của Nhà Vua (Full).', 'https://s4.phim1280.tv/20250219/j2qdu6qi/index.m3u8', '2025-04-06 08:30:30', 1, 'https://phimimg.com/upload/vod/20250219-1/55705980dbf57659fd445f17e7024ad2.jpg', 'full', '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(26, 15, 'Full', 'Xem phim Titanic (Full).', 'https://s2.phim1280.tv/20231017/20MEMVbZ/index.m3u8', '2025-04-06 08:30:30', 1, 'https://phimimg.com/upload/vod/20231014-1/1a68d864d144b4f3d8d2da38cf41f33b.jpg', 'full', '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(27, 16, 'Full', 'Xem phim Sự Im Lặng Của Bầy Cừu (Full).', 'https://s1.phim1280.tv/20231201/gp2Qvj7D/index.m3u8', '2025-04-06 08:30:30', 1, 'https://phimimg.com/upload/vod/20231123-1/712e6c865714ad0ca495981aa11ed3b0.jpg', 'full', '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(28, 17, 'Full', 'Xem phim Giải Cứu Binh Nhì Ryan (Full).', 'https://s1.phim1280.tv/20231017/YqTHKOJY/index.m3u8', '2025-04-06 08:30:30', 1, 'https://phimimg.com/upload/vod/20231014-1/874adf6e586960b9bde6af4989e19681.jpg', 'full', '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(29, 18, 'Full', 'Xem phim Quản Giáo Và Tử Tù (Full).', 'https://s2.phim1280.tv/20240219/SAOE0IDo/index.m3u8', '2025-04-06 08:30:30', 1, 'https://phimimg.com/upload/vod/20240207-1/3bca0630fd93caade43f0e838c80b6ca.jpg', 'full', '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(30, 19, 'Full', 'Xem phim Mufasa: Vua Sư Tử (Full).', 'https://s4.phim1280.tv/20250105/EDLpgU3M/index.m3u8', '2025-04-06 08:30:30', 1, 'https://phimimg.com/upload/vod/20250105-1/abea2fe4af2530fa1a668f51a1bc9758.jpg', 'full', '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(31, 20, 'Full', 'Xem phim Ảo Thuật Gia Đấu Trí (Full).', 'https://s1.phim1280.tv/20231021/VSPcYDVn/index.m3u8', '2025-04-06 08:30:30', 1, 'https://phimimg.com/upload/vod/20231020-1/033f14d49221c32831cb50b655dafee3.jpg', 'full', '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(32, 21, 'Full', 'Xem phim Avengers: Hồi Kết (Full).', 'https://s2.phim1280.tv/20231019/3g1f5kg4/index.m3u8', '2025-04-06 08:30:30', 1, 'https://phimimg.com/upload/vod/20231018-1/3541f605986d7911e5c8d38db5b06447.jpg', 'full', '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(33, 22, 'Tập 01', 'Tập 1. Eddard Stark nhận lời mời đến King\'s Landing, trong khi nhà Targaryen mưu đồ ở Essos.', 'https://s3.phim1280.tv/20240730/up9im6mI/index.m3u8', '2025-04-06 08:30:33', 1, 'https://phimimg.com/upload/vod/20240730-1/8be14e4426c02652dcec30254e2988f0.jpg', 'tap-01', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(34, 22, 'Tập 02', 'Tập 2. Catelyn điều tra vụ ám sát Bran, Jon Snow đến Bức Tường.', 'https://s3.phim1280.tv/20240730/R2SyGTNq/index.m3u8', '2025-04-06 08:30:33', 2, 'https://phimimg.com/upload/vod/20240730-1/8be14e4426c02652dcec30254e2988f0.jpg', 'tap-02', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(35, 22, 'Tập 03', 'Tập 3. Ned tìm hiểu về cái chết của người tiền nhiệm, Daenerys đối mặt với cuộc sống mới.', 'https://s3.phim1280.tv/20240730/2eBgsGJW/index.m3u8', '2025-04-06 08:30:33', 3, 'https://phimimg.com/upload/vod/20240730-1/8be14e4426c02652dcec30254e2988f0.jpg', 'tap-03', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(36, 22, 'Tập 04', 'Tập 4. Tyrion bị bắt giữ tại Eyrie, Viserys mất kiên nhẫn.', 'https://s3.phim1280.tv/20240730/7ddwXmgA/index.m3u8', '2025-04-06 08:30:33', 4, 'https://phimimg.com/upload/vod/20240730-1/8be14e4426c02652dcec30254e2988f0.jpg', 'tap-04', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(37, 22, 'Tập 05', 'Tập 5. Ned đối đầu với Jaime Lannister, Robert đi săn.', 'https://s3.phim1280.tv/20240730/9cKDoVho/index.m3u8', '2025-04-06 08:30:33', 5, 'https://phimimg.com/upload/vod/20240730-1/8be14e4426c02652dcec30254e2988f0.jpg', 'tap-05', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(38, 22, 'Tập 06', 'Tập 6. Viserys đòi vương miện, Ned đưa ra quyết định quan trọng.', 'https://s3.phim1280.tv/20240730/P1VoLCaB/index.m3u8', '2025-04-06 08:30:33', 6, 'https://phimimg.com/upload/vod/20240730-1/8be14e4426c02652dcec30254e2988f0.jpg', 'tap-06', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(39, 22, 'Tập 07', 'Tập 7. Robert hấp hối, Ned cố gắng bảo vệ người thừa kế.', 'https://s3.phim1280.tv/20240730/RQVZrgTI/index.m3u8', '2025-04-06 08:30:33', 7, 'https://phimimg.com/upload/vod/20240730-1/8be14e4426c02652dcec30254e2988f0.jpg', 'tap-07', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(40, 22, 'Tập 08', 'Tập 8. Nhà Stark gặp nguy hiểm, Jon đối mặt với thử thách ở phương Bắc.', 'https://s3.phim1280.tv/20240730/Dge9Wl3v/index.m3u8', '2025-04-06 08:30:33', 8, 'https://phimimg.com/upload/vod/20240730-1/8be14e4426c02652dcec30254e2988f0.jpg', 'tap-08', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(41, 22, 'Tập 09', 'Tập 9. Ned đối mặt với số phận, Robb tập hợp quân đội.', 'https://s3.phim1280.tv/20240730/VDbfDFTW/index.m3u8', '2025-04-06 08:30:33', 9, 'https://phimimg.com/upload/vod/20240730-1/8be14e4426c02652dcec30254e2988f0.jpg', 'tap-09', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(42, 22, 'Tập 10', 'Tập 10. Hậu quả từ cái chết của Ned lan rộng, Daenerys bước vào lửa.', 'https://s3.phim1280.tv/20240730/cQ9gGD9W/index.m3u8', '2025-04-06 08:30:33', 10, 'https://phimimg.com/upload/vod/20240730-1/8be14e4426c02652dcec30254e2988f0.jpg', 'tap-10', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(43, 23, 'Tập 01', 'Tập 1. Tyrion đến King\'s Landing, Stannis tuyên bố quyền thừa kế.', 'https://s3.phim1280.tv/20240730/6w0Hm1Xg/index.m3u8', '2025-04-06 08:30:33', 1, 'https://phimimg.com/upload/vod/20240730-1/bc8c1ee26261c9fa192d253088f23ed2.jpg', 'tap-01', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(44, 23, 'Tập 02', 'Tập 2. Theon Greyjoy trở về Quần đảo Sắt, Arya gặp những người bạn mới.', 'https://s3.phim1280.tv/20240730/wHGRtOO3/index.m3u8', '2025-04-06 08:30:33', 2, 'https://phimimg.com/upload/vod/20240730-1/bc8c1ee26261c9fa192d253088f23ed2.jpg', 'tap-02', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(45, 23, 'Tập 03', 'Tập 3. Tyrion lập kế hoạch phòng thủ, Catelyn tìm kiếm đồng minh.', 'https://s3.phim1280.tv/20240730/FMtLM4ER/index.m3u8', '2025-04-06 08:30:33', 3, 'https://phimimg.com/upload/vod/20240730-1/bc8c1ee26261c9fa192d253088f23ed2.jpg', 'tap-03', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(46, 23, 'Tập 04', 'Tập 4. Melisandre sinh ra bóng tối, Joffrey trừng phạt Sansa.', 'https://s3.phim1280.tv/20240730/zCJhS54v/index.m3u8', '2025-04-06 08:30:33', 4, 'https://phimimg.com/upload/vod/20240730-1/bc8c1ee26261c9fa192d253088f23ed2.jpg', 'tap-04', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(47, 23, 'Tập 05', 'Tập 5. Renly Baratheon đối mặt với số phận, Daenerys đến Qarth.', 'https://s3.phim1280.tv/20240730/2rX9shZP/index.m3u8', '2025-04-06 08:30:33', 5, 'https://phimimg.com/upload/vod/20240730-1/bc8c1ee26261c9fa192d253088f23ed2.jpg', 'tap-05', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(48, 23, 'Tập 06', 'Tập 6. Theon chiếm Winterfell, Jon bị lạc ngoài Bức Tường.', 'https://s3.phim1280.tv/20240730/IHpfFgfM/index.m3u8', '2025-04-06 08:30:33', 6, 'https://phimimg.com/upload/vod/20240730-1/bc8c1ee26261c9fa192d253088f23ed2.jpg', 'tap-06', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(49, 23, 'Tập 07', 'Tập 7. Jaime và Brienne bắt đầu hành trình, Cersei nghi ngờ Tyrion.', 'https://s3.phim1280.tv/20240730/KWquoCq4/index.m3u8', '2025-04-06 08:30:33', 7, 'https://phimimg.com/upload/vod/20240730-1/bc8c1ee26261c9fa192d253088f23ed2.jpg', 'tap-07', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(50, 23, 'Tập 08', 'Tập 8. Theon đưa ra quyết định khó khăn, Stannis tiến đến King\'s Landing.', 'https://s3.phim1280.tv/20240730/p3FtR5yD/index.m3u8', '2025-04-06 08:30:33', 8, 'https://phimimg.com/upload/vod/20240730-1/bc8c1ee26261c9fa192d253088f23ed2.jpg', 'tap-08', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(51, 23, 'Tập 09', 'Tập 9. Trận chiến Vịnh Blackwater dữ dội.', 'https://s3.phim1280.tv/20240730/dVgM3fFv/index.m3u8', '2025-04-06 08:30:33', 9, 'https://phimimg.com/upload/vod/20240730-1/bc8c1ee26261c9fa192d253088f23ed2.jpg', 'tap-09', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(52, 23, 'Tập 10', 'Tập 10. Hậu quả của trận chiến, Daenerys vào Ngôi nhà Bất tử.', 'https://s3.phim1280.tv/20240730/UQUvwKyy/index.m3u8', '2025-04-06 08:30:33', 10, 'https://phimimg.com/upload/vod/20240730-1/bc8c1ee26261c9fa192d253088f23ed2.jpg', 'tap-10', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(53, 24, 'Tập 01', 'Tập 1. Jon gặp Mance Rayder, Daenerys đến Astapor.', 'https://s3.phim1280.tv/20240730/HXSU5mQ1/index.m3u8', '2025-04-06 08:30:33', 1, 'https://phimimg.com/upload/vod/20240730-1/1f9108b2b2df89e9d6922c5803faa810.jpg', 'tap-01', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(54, 24, 'Tập 02', 'Tập 2. Jaime và Brienne đối mặt nguy hiểm, Bran gặp những người bạn mới.', 'https://s3.phim1280.tv/20240730/OgnudGhC/index.m3u8', '2025-04-06 08:30:33', 2, 'https://phimimg.com/upload/vod/20240730-1/1f9108b2b2df89e9d6922c5803faa810.jpg', 'tap-02', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(55, 24, 'Tập 03', 'Tập 3. Daenerys xem xét lời đề nghị, Tyrion nhận nhiệm vụ mới.', 'https://s3.phim1280.tv/20240730/r7z5Idij/index.m3u8', '2025-04-06 08:30:33', 3, 'https://phimimg.com/upload/vod/20240730-1/1f9108b2b2df89e9d6922c5803faa810.jpg', 'tap-03', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(56, 24, 'Tập 04', 'Tập 4. Daenerys giải phóng Unsullied, Đội Gác Đêm đối mặt với binh biến.', 'https://s3.phim1280.tv/20240730/tVxiPxIm/index.m3u8', '2025-04-06 08:30:33', 4, 'https://phimimg.com/upload/vod/20240730-1/1f9108b2b2df89e9d6922c5803faa810.jpg', 'tap-04', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(57, 24, 'Tập 05', 'Tập 5. Jon và Ygritte leo Tường Thành, Robb Stark đưa ra quyết định sai lầm.', 'https://s3.phim1280.tv/20240730/f15uyCsJ/index.m3u8', '2025-04-06 08:30:33', 5, 'https://phimimg.com/upload/vod/20240730-1/1f9108b2b2df89e9d6922c5803faa810.jpg', 'tap-05', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(58, 24, 'Tập 06', 'Tập 6. Tyrion và Sansa kết hôn, Melisandre tìm Gendry.', 'https://s3.phim1280.tv/20240730/DxV5qk6Z/index.m3u8', '2025-04-06 08:30:33', 6, 'https://phimimg.com/upload/vod/20240730-1/1f9108b2b2df89e9d6922c5803faa810.jpg', 'tap-06', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(59, 24, 'Tập 07', 'Tập 7. Daenerys đến Yunkai, Jon đối mặt với hậu quả.', 'https://s3.phim1280.tv/20240730/pRRC7tDM/index.m3u8', '2025-04-06 08:30:33', 7, 'https://phimimg.com/upload/vod/20240730-1/1f9108b2b2df89e9d6922c5803faa810.jpg', 'tap-07', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(60, 24, 'Tập 08', 'Tập 8. Samwell đối mặt Bóng Trắng, Daenerys gặp một đồng minh mới.', 'https://s3.phim1280.tv/20240730/m2no3uoX/index.m3u8', '2025-04-06 08:30:33', 8, 'https://phimimg.com/upload/vod/20240730-1/1f9108b2b2df89e9d6922c5803faa810.jpg', 'tap-08', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(61, 24, 'Tập 09', 'Tập 9. Đám cưới Đỏ bi thảm diễn ra tại nhà Frey.', 'https://s3.phim1280.tv/20240730/ZJpJP0Lb/index.m3u8', '2025-04-06 08:30:33', 9, 'https://phimimg.com/upload/vod/20240730-1/1f9108b2b2df89e9d6922c5803faa810.jpg', 'tap-09', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(62, 24, 'Tập 10', 'Tập 10. Các nhân vật đối mặt với hậu quả của Đám cưới Đỏ, Daenerys được tung hô.', 'https://s3.phim1280.tv/20240730/kclMXTtZ/index.m3u8', '2025-04-06 08:30:33', 10, 'https://phimimg.com/upload/vod/20240730-1/1f9108b2b2df89e9d6922c5803faa810.jpg', 'tap-10', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(63, 25, 'Tập 01', 'Tập 1. Oberyn Martell đến King\'s Landing, Jon đối mặt thẩm vấn.', 'https://s1.phim1280.tv/20240215/CTGcxlYl/index.m3u8', '2025-04-06 08:30:33', 1, 'https://phimimg.com/upload/vod/20240205-1/3a9626c89bae7f9a723b15493d3130f4.jpg', 'tap-01', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(64, 25, 'Tập 02', 'Tập 2. Đám cưới Tím diễn ra với kết cục bất ngờ.', 'https://s1.phim1280.tv/20240215/oUjWG50Y/index.m3u8', '2025-04-06 08:30:33', 2, 'https://phimimg.com/upload/vod/20240205-1/3a9626c89bae7f9a723b15493d3130f4.jpg', 'tap-02', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(65, 25, 'Tập 03', 'Tập 3. Tyrion bị bắt giữ, Sam gửi Gilly đến nơi an toàn.', 'https://s1.phim1280.tv/20240215/DdJU54lM/index.m3u8', '2025-04-06 08:30:33', 3, 'https://phimimg.com/upload/vod/20240205-1/3a9626c89bae7f9a723b15493d3130f4.jpg', 'tap-03', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(66, 25, 'Tập 04', 'Tập 4. Jaime học cách chiến đấu bằng tay trái, Daenerys chiếm Meereen.', 'https://s1.phim1280.tv/20240215/AS78AVho/index.m3u8', '2025-04-06 08:30:33', 4, 'https://phimimg.com/upload/vod/20240205-1/3a9626c89bae7f9a723b15493d3130f4.jpg', 'tap-04', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(67, 25, 'Tập 05', 'Tập 5. Tommen lên ngôi, Littlefinger đưa Sansa đến Eyrie.', 'https://s1.phim1280.tv/20240215/x3ucwdSA/index.m3u8', '2025-04-06 08:30:33', 5, 'https://phimimg.com/upload/vod/20240205-1/3a9626c89bae7f9a723b15493d3130f4.jpg', 'tap-05', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(68, 25, 'Tập 06', 'Tập 6. Phiên tòa xét xử Tyrion bắt đầu.', 'https://s1.phim1280.tv/20240215/kENhvnn4/index.m3u8', '2025-04-06 08:30:33', 6, 'https://phimimg.com/upload/vod/20240205-1/3a9626c89bae7f9a723b15493d3130f4.jpg', 'tap-06', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(69, 25, 'Tập 07', 'Tập 7. Tyrion yêu cầu xét xử bằng chiến đấu, Lysa Arryn đối mặt Littlefinger.', 'https://s1.phim1280.tv/20240215/6nFwuJIp/index.m3u8', '2025-04-06 08:30:33', 7, 'https://phimimg.com/upload/vod/20240205-1/3a9626c89bae7f9a723b15493d3130f4.jpg', 'tap-07', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(70, 25, 'Tập 08', 'Tập 8. Oberyn Martell đấu với Ser Gregor Clegane.', 'https://s1.phim1280.tv/20240215/vSmJT2pn/index.m3u8', '2025-04-06 08:30:33', 8, 'https://phimimg.com/upload/vod/20240205-1/3a9626c89bae7f9a723b15493d3130f4.jpg', 'tap-08', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(71, 25, 'Tập 09', 'Tập 9. Trận chiến tại Castle Black giữa Đội Gác Đêm và Wildlings.', 'https://s1.phim1280.tv/20240215/qC5pB2GE/index.m3u8', '2025-04-06 08:30:33', 9, 'https://phimimg.com/upload/vod/20240205-1/3a9626c89bae7f9a723b15493d3130f4.jpg', 'tap-09', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(72, 25, 'Tập 10', 'Tập 10. Tyrion trốn thoát, Stannis đến Bức Tường, Arya ra khơi.', 'https://s1.phim1280.tv/20240215/U8ntlbO2/index.m3u8', '2025-04-06 08:30:33', 10, 'https://phimimg.com/upload/vod/20240205-1/3a9626c89bae7f9a723b15493d3130f4.jpg', 'tap-10', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(73, 26, 'Tập 01', 'Tập 1. Cersei và Jaime thích nghi với thế giới không Tywin, Jon bị giằng xé.', 'https://s3.phim1280.tv/20240730/mIj8EXSf/index.m3u8', '2025-04-06 08:30:33', 1, 'https://phimimg.com/upload/vod/20240730-1/2da6911b4042ea43e6975a45256321f4.jpg', 'tap-01', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(74, 26, 'Tập 02', 'Tập 2. Arya đến Braavos, Jon đưa ra quyết định về Stannis.', 'https://s3.phim1280.tv/20240730/sxlQOM9B/index.m3u8', '2025-04-06 08:30:33', 2, 'https://phimimg.com/upload/vod/20240730-1/2da6911b4042ea43e6975a45256321f4.jpg', 'tap-02', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(75, 26, 'Tập 03', 'Tập 3. Tommen kết hôn với Margaery, Jon trở thành Lord Commander.', 'https://s3.phim1280.tv/20240730/cJUSP5cW/index.m3u8', '2025-04-06 08:30:33', 3, 'https://phimimg.com/upload/vod/20240730-1/2da6911b4042ea43e6975a45256321f4.jpg', 'tap-03', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(76, 26, 'Tập 04', 'Tập 4. Hội Chim Sẻ thể hiện quyền lực, Tyrion bị Jorah bắt cóc.', 'https://s3.phim1280.tv/20240730/q0YA9G7l/index.m3u8', '2025-04-06 08:30:33', 4, 'https://phimimg.com/upload/vod/20240730-1/2da6911b4042ea43e6975a45256321f4.jpg', 'tap-04', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(77, 26, 'Tập 05', 'Tập 5. Jon lập kế hoạch cứu Wildlings, Daenerys đối mặt với Sons of the Harpy.', 'https://s3.phim1280.tv/20240730/y1q856sZ/index.m3u8', '2025-04-06 08:30:33', 5, 'https://phimimg.com/upload/vod/20240730-1/2da6911b4042ea43e6975a45256321f4.jpg', 'tap-05', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(78, 26, 'Tập 06', 'Tập 6. Arya bắt đầu huấn luyện khắc nghiệt, Sansa kết hôn với Ramsay.', 'https://s3.phim1280.tv/20240730/tk6bYN7Q/index.m3u8', '2025-04-06 08:30:33', 6, 'https://phimimg.com/upload/vod/20240730-1/2da6911b4042ea43e6975a45256321f4.jpg', 'tap-06', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(79, 26, 'Tập 07', 'Tập 7. Jon đến Hardhome, Cersei bị bắt giữ.', 'https://s3.phim1280.tv/20240730/R2XGHfEg/index.m3u8', '2025-04-06 08:30:33', 7, 'https://phimimg.com/upload/vod/20240730-1/2da6911b4042ea43e6975a45256321f4.jpg', 'tap-07', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(80, 26, 'Tập 08', 'Tập 8. Trận chiến kinh hoàng tại Hardhome.', 'https://s3.phim1280.tv/20240730/1qXEyJD9/index.m3u8', '2025-04-06 08:30:33', 8, 'https://phimimg.com/upload/vod/20240730-1/2da6911b4042ea43e6975a45256321f4.jpg', 'tap-08', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(81, 26, 'Tập 09', 'Tập 9. Stannis đưa ra lựa chọn tàn nhẫn, Daenerys bị phục kích tại đấu trường.', 'https://s3.phim1280.tv/20240730/DzOH3dPf/index.m3u8', '2025-04-06 08:30:33', 9, 'https://phimimg.com/upload/vod/20240730-1/2da6911b4042ea43e6975a45256321f4.jpg', 'tap-09', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(82, 26, 'Tập 10', 'Tập 10. Cersei thực hiện cuộc đi bộ sám hối, Jon đối mặt với sự phản bội.', 'https://s3.phim1280.tv/20240730/FKrHaiYb/index.m3u8', '2025-04-06 08:30:33', 10, 'https://phimimg.com/upload/vod/20240730-1/2da6911b4042ea43e6975a45256321f4.jpg', 'tap-10', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(83, 27, 'Tập 01', 'Tập 1. Jon Snow đã chết, Daenerys gặp một Khal mới.', 'https://s3.phim1280.tv/20240730/3d0d0xwA/index.m3u8', '2025-04-06 08:30:33', 1, 'https://phimimg.com/upload/vod/20240730-1/064053d9650ea7139ea8ce936bdc026a.jpg', 'tap-01', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(84, 27, 'Tập 02', 'Tập 2. Bran huấn luyện với Quạ Ba Mắt, Jon Snow trở về.', 'https://s3.phim1280.tv/20240730/CEY0K228/index.m3u8', '2025-04-06 08:30:33', 2, 'https://phimimg.com/upload/vod/20240730-1/064053d9650ea7139ea8ce936bdc026a.jpg', 'tap-02', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(85, 27, 'Tập 03', 'Tập 3. Daenerys gặp tương lai, Arya tiếp tục huấn luyện.', 'https://s3.phim1280.tv/20240730/LhDJZlJT/index.m3u8', '2025-04-06 08:30:33', 3, 'https://phimimg.com/upload/vod/20240730-1/064053d9650ea7139ea8ce936bdc026a.jpg', 'tap-03', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(86, 27, 'Tập 04', 'Tập 4. Sansa đoàn tụ với Jon, Tyrion thỏa thuận với các chủ nô.', 'https://s3.phim1280.tv/20240730/mPhJsk9B/index.m3u8', '2025-04-06 08:30:33', 4, 'https://phimimg.com/upload/vod/20240730-1/064053d9650ea7139ea8ce936bdc026a.jpg', 'tap-04', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(87, 27, 'Tập 05', 'Tập 5. Bran tìm hiểu nguồn gốc Bóng Trắng, Daenerys ra lệnh.', 'https://s3.phim1280.tv/20240730/5QvtWFfW/index.m3u8', '2025-04-06 08:30:33', 5, 'https://phimimg.com/upload/vod/20240730-1/064053d9650ea7139ea8ce936bdc026a.jpg', 'tap-05', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(88, 27, 'Tập 06', 'Tập 6. Arya đối mặt lựa chọn khó khăn, Bran gặp một người thân cũ.', 'https://s3.phim1280.tv/20240730/Dk1jNjGb/index.m3u8', '2025-04-06 08:30:33', 6, 'https://phimimg.com/upload/vod/20240730-1/064053d9650ea7139ea8ce936bdc026a.jpg', 'tap-06', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(89, 27, 'Tập 07', 'Tập 7. Jon và Sansa tập hợp đồng minh, The Hound trở lại.', 'https://s3.phim1280.tv/20240730/rBmM76Xx/index.m3u8', '2025-04-06 08:30:33', 7, 'https://phimimg.com/upload/vod/20240730-1/064053d9650ea7139ea8ce936bdc026a.jpg', 'tap-07', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(90, 27, 'Tập 08', 'Tập 8. Arya bị tấn công, Jaime đối đầu với Blackfish.', 'https://s3.phim1280.tv/20240730/K4yoM3Ih/index.m3u8', '2025-04-06 08:30:33', 8, 'https://phimimg.com/upload/vod/20240730-1/064053d9650ea7139ea8ce936bdc026a.jpg', 'tap-08', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(91, 27, 'Tập 09', 'Tập 9. Trận chiến của những đứa con hoang giành Winterfell.', 'https://s3.phim1280.tv/20240730/vyxHZUBz/index.m3u8', '2025-04-06 08:30:33', 9, 'https://phimimg.com/upload/vod/20240730-1/064053d9650ea7139ea8ce936bdc026a.jpg', 'tap-09', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(92, 27, 'Tập 10', 'Tập 10. Cersei thực hiện kế hoạch tại King\'s Landing, Jon được tuyên bố là Vua phương Bắc.', 'https://s3.phim1280.tv/20240730/Ur4AhKW0/index.m3u8', '2025-04-06 08:30:33', 10, 'https://phimimg.com/upload/vod/20240730-1/064053d9650ea7139ea8ce936bdc026a.jpg', 'tap-10', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(93, 28, 'Tập 01', 'Tập 1. Arya trả thù, Daenerys đến Dragonstone.', 'https://s3.phim1280.tv/20240730/UNJTO8RK/index.m3u8', '2025-04-06 08:30:33', 1, 'https://phimimg.com/upload/vod/20240730-1/586cc14fa8d154dd4250065e8f026236.jpg', 'tap-01', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(94, 28, 'Tập 02', 'Tập 2. Jon nhận được lời mời, Euron Greyjoy tấn công.', 'https://s3.phim1280.tv/20240730/04EO0APj/index.m3u8', '2025-04-06 08:30:33', 2, 'https://phimimg.com/upload/vod/20240730-1/586cc14fa8d154dd4250065e8f026236.jpg', 'tap-02', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(95, 28, 'Tập 03', 'Tập 3. Jon gặp Daenerys, Cersei nhận một món quà.', 'https://s3.phim1280.tv/20240730/YS2U0t2J/index.m3u8', '2025-04-06 08:30:33', 3, 'https://phimimg.com/upload/vod/20240730-1/586cc14fa8d154dd4250065e8f026236.jpg', 'tap-03', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(96, 28, 'Tập 04', 'Tập 4. Daenerys phản công nhà Lannister bằng lửa và máu.', 'https://s3.phim1280.tv/20240730/ac575iWm/index.m3u8', '2025-04-06 08:30:33', 4, 'https://phimimg.com/upload/vod/20240730-1/586cc14fa8d154dd4250065e8f026236.jpg', 'tap-04', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(97, 28, 'Tập 05', 'Tập 5. Jon nhận được tin từ Bran, Tyrion cố gắng đàm phán.', 'https://s3.phim1280.tv/20240730/JkcXQgSG/index.m3u8', '2025-04-06 08:30:33', 5, 'https://phimimg.com/upload/vod/20240730-1/586cc14fa8d154dd4250065e8f026236.jpg', 'tap-05', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(98, 28, 'Tập 06', 'Tập 6. Jon dẫn một nhóm đi bắt Bóng Trắng.', 'https://s3.phim1280.tv/20240730/9YCWp8BK/index.m3u8', '2025-04-06 08:30:33', 6, 'https://phimimg.com/upload/vod/20240730-1/586cc14fa8d154dd4250065e8f026236.jpg', 'tap-06', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(99, 28, 'Tập 07', 'Tập 7. Các phe phái gặp mặt tại King\'s Landing, Bức Tường sụp đổ.', 'https://s3.phim1280.tv/20240730/Rdhiovoy/index.m3u8', '2025-04-06 08:30:33', 7, 'https://phimimg.com/upload/vod/20240730-1/586cc14fa8d154dd4250065e8f026236.jpg', 'tap-07', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(100, 29, 'Tập 01', 'Tập 1. Daenerys và Jon đến Winterfell, các cuộc hội ngộ diễn ra.', 'https://s3.phim1280.tv/20240730/hCuhDYMm/index.m3u8', '2025-04-06 08:30:33', 1, 'https://phimimg.com/upload/vod/20240730-1/a758db8f7dd8192353b767a57c9e1920.jpg', 'tap-01', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(101, 29, 'Tập 02', 'Tập 2. Mọi người chuẩn bị cho trận chiến chống lại Dạ Đế.', 'https://s3.phim1280.tv/20240730/WUYOWwC1/index.m3u8', '2025-04-06 08:30:33', 2, 'https://phimimg.com/upload/vod/20240730-1/a758db8f7dd8192353b767a57c9e1920.jpg', 'tap-02', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(102, 29, 'Tập 03', 'Tập 3. Trận chiến Winterfell - Cuộc chiến chống lại Đội quân Xác sống.', 'https://s3.phim1280.tv/20240730/bZvDRc0k/index.m3u8', '2025-04-06 08:30:33', 3, 'https://phimimg.com/upload/vod/20240730-1/a758db8f7dd8192353b767a57c9e1920.jpg', 'tap-03', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(103, 29, 'Tập 04', 'Tập 4. Hậu quả của trận chiến, chuẩn bị cho cuộc chiến cuối cùng.', 'https://s3.phim1280.tv/20240730/9OzRpTSr/index.m3u8', '2025-04-06 08:30:33', 4, 'https://phimimg.com/upload/vod/20240730-1/a758db8f7dd8192353b767a57c9e1920.jpg', 'tap-04', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(104, 29, 'Tập 05', 'Tập 5. Trận chiến tại King\'s Landing diễn ra tàn khốc.', 'https://s3.phim1280.tv/20240730/Vtcjx71z/index.m3u8', '2025-04-06 08:30:33', 5, 'https://phimimg.com/upload/vod/20240730-1/a758db8f7dd8192353b767a57c9e1920.jpg', 'tap-05', '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(105, 29, 'Tập 06', 'Tập 6. Westeros đối mặt với tương lai sau cuộc chiến, số phận các nhân vật được định đoạt.', 'https://s3.phim1280.tv/20240730/RW7gdN0N/index.m3u8', '2025-04-06 08:30:33', 6, 'https://phimimg.com/upload/vod/20240730-1/a758db8f7dd8192353b767a57c9e1920.jpg', 'tap-06', '2025-04-06 08:30:33', '2025-04-06 08:30:33');

-- Dumping structure for table movie_db.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table movie_db.favorites
CREATE TABLE IF NOT EXISTS `favorites` (
  `user_id` bigint unsigned NOT NULL,
  `movie_id` bigint unsigned NOT NULL,
  `added_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`,`movie_id`),
  KEY `favorites_movie_id_foreign` (`movie_id`),
  CONSTRAINT `favorites_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.favorites: ~0 rows (approximately)
DELETE FROM `favorites`;

-- Dumping structure for table movie_db.history
CREATE TABLE IF NOT EXISTS `history` (
  `user_id` bigint unsigned NOT NULL,
  `episode_id` bigint unsigned NOT NULL,
  `progress` int NOT NULL,
  `last_watched_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`episode_id`),
  KEY `history_episode_id_foreign` (`episode_id`),
  CONSTRAINT `history_episode_id_foreign` FOREIGN KEY (`episode_id`) REFERENCES `episodes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.history: ~0 rows (approximately)
DELETE FROM `history`;

-- Dumping structure for table movie_db.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table movie_db.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table movie_db.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.migrations: ~16 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_03_27_133500_create_categories_table', 1),
	(5, '2025_03_27_133551_create_movies_table', 1),
	(6, '2025_03_27_133629_create_episodes_table', 1),
	(7, '2025_03_27_133629_create_packages_table', 1),
	(8, '2025_03_27_133629_create_subscriptions_table', 1),
	(9, '2025_03_27_133630_create_comments_table', 1),
	(10, '2025_03_27_133630_create_payments_table', 1),
	(11, '2025_03_27_133631_create_history_table', 1),
	(12, '2025_03_27_133631_create_ratings_table', 1),
	(13, '2025_03_27_133632_create_favorites_table', 1),
	(21, '2025_04_06_060550_add_is_active_to_package', 2),
	(22, '2025_04_06_060617_create_movie_package_table', 2),
	(23, '2025_04_07_132027_add_fulltext_index_to_movies_table', 2),
	(24, '2025_04_07_170901_add_fulltext_for_search', 3);

-- Dumping structure for table movie_db.movies
CREATE TABLE IF NOT EXISTS `movies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin_name` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên gốc',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genres` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `rating` double NOT NULL DEFAULT '0' COMMENT 'Điểm đánh giá',
  `view` int NOT NULL DEFAULT '0' COMMENT 'Số lượt xem',
  `actor` text COLLATE utf8mb4_unicode_ci,
  `director` text COLLATE utf8mb4_unicode_ci,
  `year` int DEFAULT NULL,
  `poster_url` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trailer_url` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ULR trailer neu la series, neu la movie thi URL movie',
  `type` enum('movie','series') COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail_url` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `movies_slug_unique` (`slug`),
  KEY `movies_category_id_foreign` (`category_id`),
  FULLTEXT KEY `name` (`name`,`description`,`genres`,`actor`,`director`,`origin_name`),
  FULLTEXT KEY `ft_movies_name` (`name`),
  FULLTEXT KEY `ft_movies_origin_name` (`origin_name`),
  FULLTEXT KEY `ft_movies_genres` (`genres`),
  FULLTEXT KEY `ft_movies_others` (`description`,`actor`,`director`),
  CONSTRAINT `movies_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.movies: ~29 rows (approximately)
DELETE FROM `movies`;
INSERT INTO `movies` (`id`, `slug`, `origin_name`, `name`, `genres`, `description`, `rating`, `view`, `actor`, `director`, `year`, `poster_url`, `trailer_url`, `type`, `thumbnail_url`, `category_id`, `created_at`, `updated_at`) VALUES
	(1, 'ngoi-truong-xac-song', 'All of Us Are Dead', 'Ngôi Trường Xác Sống', 'Hành Động, Phiêu Lưu, Chính Kịch, Khoa Học, Viễn Tưởng, Hàn Quốc', 'Một trường cấp ba trở thành điểm bùng phát virus thây ma. Các học sinh mắc kẹt phải nỗ lực thoát ra – hoặc biến thành một trong những người nhiễm bệnh hung tợn.', 0, 60, 'Park Ji-hu, Yoon Chan-young, Cho Yi-hyun, Lomon, Yoo In-soo, Lee You-mi, Kim Byung-chul, Lee Kyoo-hyung, Jeon Bae-soo', 'Đang cập nhật', 2022, 'https://phimimg.com/upload/vod/20250325-1/6db202d6161c123d96b0180c2da9b1e5.jpg', 'https://www.youtube.com/watch?v=IN5TD4VRcSM', 'series', 'https://phimimg.com/upload/vod/20250325-1/6985255433cba78af7f28fe63c5126c9.jpg', 3, '2025-04-06 08:30:23', '2025-04-10 23:44:12'),
	(2, 'batman-ninja-doi-dau-lien-minh-yakuza', 'Batman Ninja vs. Yakuza League', 'Batman Ninja Đối Đầu Liên Minh Yakuza', 'Hành Động, Phiêu Lưu, Nhật Bản, Âu Mỹ', 'Gia đình Batman trở về hiện tại và phát hiện Nhật Bản đã biến mất, thay vào đó là một hòn đảo khổng lồ tên Hinomoto lơ lửng trên bầu trời Gotham. Trên đỉnh quyền lực là Yakuza – một nhóm siêu nhân cai trị tàn bạo và trông rất giống Justice League. Giờ đây, Batman và đồng đội phải chiến đấu để cứu Gotham!', 0, 2, 'Koichi Yamadera, Yuki Kaji, Kengo Kawanishi, Daisuke Ono, Akira Ishida, Ayane Sakura, Akio Otsuka, Nobuyuki Hiyama, Romi Park, Rie Kugimiya, Wataru Takagi, Hochu Otsuka, Masaki Terasoma, Kazuhiro Yamaji, Takaya Kamikawa', 'Jumpei Mizusaki, Shinji Takagi', 2025, 'https://phimimg.com/upload/vod/20250328-1/844101bcb965fcf508838d4b9356649c.jpg', 'https://www.youtube.com/watch?v=QleeDtH_WWE', 'movie', 'https://phimimg.com/upload/vod/20250328-1/7e743bf2f82e6a4383f9add3ac5fdec0.jpg', 2, '2025-04-06 08:30:23', '2025-04-08 04:57:59'),
	(3, 'nha-tu-shawshank', 'The Shawshank Redemption', 'Nhà Tù Shawshank', 'Chính Kịch, Hình Sự, Âu Mỹ', 'Nhà tù Shawshank kể về Andrew, một nhân viên nhà băng, bị kết án chung thân sau khi giết vợ và nhân tình của cô. Anh một mực cho rằng mình bị oan. Andy bị đưa tới nhà tù Shawshank. Tại đây, thế giới ngầm của các phạm nhân, sự hà khắc của hệ thống quản giáo xung đột và giành nhau quyền thống trị. Chỉ có các phạm nhân trung lập là bị kẹt ở giữa và có thể bỏ mạng. Làm quen với tay ‘quản lý chợ đen’ Redding, Andy dần thích nghi với cuộc sống tại Shawshank. Song, kế hoạch lớn hơn việc tồn tại ở nhà tù này đang được anh suy tính. Qua con mắt và lời kể của Redding, cuộc vượt ngục vĩ đại này được kể tuần tự cùng một kết thúc bất ngờ', 0, 0, 'Tim Robbins, Morgan Freeman, Bob Gunton, William Sadler, Clancy Brown, Gil Bellows, James Whitmore, Mark Rolston, Jeffrey DeMunn, Larry Brandenburg, Neil Giuntoli, Brian Libby, David Proval, Joseph Ragno, Jude Ciccolella, Paul McCrane, Renee Blaine, Scott', 'Frank Darabont', 1994, 'https://phimimg.com/upload/vod/20231018-1/202b6de69554481f31e54423c8ed7309.jpg', 'https://s2.phim1280.tv/20231019/SRNpBmu7/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231018-1/e31eee0d4db3c8a1b194e76a36ca26b9.jpg', 1, '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(4, 'ke-danh-cap-giac-mo', 'Inception', 'Kẻ Đánh Cắp Giấc Mơ', 'Hành Động, Khoa Học, Phiêu Lưu, Anh, Âu Mỹ', 'Cobb đánh cắp thông tin từ các mục tiêu của mình bằng cách đi vào giấc mơ của họ. Saito đề nghị xóa sạch tiền án của Cobb như một khoản thanh toán cho việc thực hiện hành vi đầu tiên đối với con trai của đối thủ cạnh tranh ốm yếu của mình.', 0, 0, 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ken Watanabe, Tom Hardy, Elliot Page, Dileep Rao, Cillian Murphy, Tom Berenger, Marion Cotillard, Pete Postlethwaite, Michael Caine, Lukas Haas, Talulah Riley, Tohoru Masamune, Taylor Geare, Claire Geare, Johnathan', 'Christopher Nolan', 2010, 'https://phimimg.com/upload/vod/20231018-1/d0ccc81540ff2c85cecbf73dda05728f.jpg', 'https://s5.phim1280.tv/20240911/qTL6xlyr/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231018-1/ea2205d6cdedccf1251422eb4fa85f6d.jpg', 1, '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(5, 'bo-gia-1972', 'The Godfather', 'Bố Già', 'Chính Kịch, Hình Sự, Âu Mỹ', 'Một câu chuyện kéo dài từ năm 1945 đến năm 1955, một biên niên sử về gia đình tội phạm Corleone người Mỹ gốc Ý. Khi tộc trưởng gia đình tội phạm có tổ chức, Vito Corleone bị ám sát bởi băng nhóm đối thủ, con trai út của ông, Michael đã phải nhúng tay vào tội ác và chống lại đối thủ với việc phát động một chiến dịch trả thù đẫm máu.', 0, 0, 'Marlon Brando, Al Pacino, James Caan, Robert Duvall, Richard S. Castellano, Diane Keaton, Talia Shire, Gianni Russo, Sterling Hayden, John Marley', 'Francis Ford Coppola', 1972, 'https://phimimg.com/upload/vod/20231014-1/39aa8b76c6b10330bd685897500a6026.jpg', 'https://s2.phim1280.tv/20231017/sO7PyfCM/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231014-1/8274cbb886030eda232520b329dc2bbb.jpg', 1, '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(6, 'chuyen-tao-lao', 'Pulp Fiction', 'Chuyện Tào Lao', 'Tâm Lý, Hình Sự, Âu Mỹ', 'Những câu chuyện tưởng chừng tầm phào về 2 gã găng tơ trên đường thực hiện mệnh lệnh của ông chủ với 1 võ sĩ quyền anh giết chết người phải chạy trốn có vẻ không liên quan nhưng khi ghép lại người xem sẽ có 1 bức tranh tổng thể, đặc trưng cho phong cách của đạo diễn Quentin Tarantino: đậm chất bạo lực, máu me.', 0, 0, 'John Travolta, Samuel L. Jackson, Uma Thurman, Bruce Willis, Ving Rhames, Harvey Keitel, Eric Stoltz, Tim Roth, Amanda Plummer, Maria de Medeiros, Quentin Tarantino, Christopher Walken, Rosanna Arquette, Peter Greene, Duane Whitaker, Angela Jones, Phil La', 'Quentin Tarantino', 1994, 'https://phimimg.com/upload/vod/20231019-1/bebb7b1cf9f75cbcd81fe2333dfc3eb1.jpg', 'https://s3.phim1280.tv/20240618/VbXKq0OU/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231019-1/d8cb231f36481392329c958e2204eb66.jpg', 1, '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(7, 'cuoc-doi-forrest-gump', 'Forrest Gump', 'Cuộc Đời Forrest Gump', 'Hài Hước, Chính Kịch, Tình Cảm, Âu Mỹ', 'Forrest Gump là một đứa trẻ bất hạnh khi sinh ra đã không có cha, hơn nữa Forrest còn bị thiểu năng. Forrest luôn bị bạn bè cùng trang lứa trêu trọc, bắt nạt. Người bạn duy nhất của Forrest là Jenny, chính Jenny đã phát hiện ra những khả năng đặc biệt của anh. Tốt nghiệp đại học Forrest nhập ngũ và tham chiến ở Việt Nam, Bubba trở thành bạn thân thứ 2 của anh. Forrest rời chiến trường với vết thương và khả năng chơi bóng bàn xuất sắc. Những biến cố nối tiếp nhau xuất hiện làm thay đổi hoàn toàn cuộc đời Forrest.', 0, 0, 'Tom Hanks, Robin Wright, Gary Sinise, Sally Field, Mykelti Williamson, Michael Conner Humphreys, Hanna Hall, Haley Joel Osment, Siobhan Fallon Hogan, Rebecca Williams', 'Robert Zemeckis', 1994, 'https://phimimg.com/upload/vod/20230923-1/0238878e80d08aac4e1d4126f23840ba.jpg', 'https://s2.phim1280.tv/20230923/MxarzCw0/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20230923-1/496cbec2d5df42fa0c816a7a9ee12206.jpg', 1, '2025-04-06 08:30:23', '2025-04-06 08:30:23'),
	(8, 'ky-si-bong-dem', 'The Dark Knight', 'Kỵ Sĩ Bóng Đêm', 'Hành Động, Hình Sự, Viễn Tưởng, Phiêu Lưu, Khoa Học, Âu Mỹ, Anh', 'Người dơi bước vào giai đoạn cam go nhất trong cuộc chiến chống lại cái ác khi tên tội phạm bậc thầy The Joker xuất hiện. Các tay trùm thuê The Joker giết người dơi để trả thù cho chỗ tài sản phi pháp bị anh lật tẩy. Tiếp tay cho The Joker là toàn bộ tội phạm ở thành phố Gotham, những kẻ đang bị bộ ba Người dơi – Trung úy Gordon – Luật sư Harvey Dent thu hẹp đất sống. Hàng loạt vụ tấn công xảy ra khiến Người dơi lúng túng. Các quan chức bị sát hại, người dân không dám ra đường buổi tối vì sợ bị giết, mọi lối ra vào thành phố Gotham bị phong tỏa. Tất cả đều do một tay The Joker sắp đặt. Luật sư Dent và cô bạn lâu năm của Người dơi là Rachel Dawes bị lôi đến hai nhà kho chứa bom hẹn giờ. Quỷ kế của The Joker khiến Người dơi không kịp đến cứu bạn, luật sư Dent may mắn thoát chết nhưng bị cháy nửa mặt. The Joker giúp Dent thoát khỏi bệnh viện, đồng thời xúi giục y trút hết hận thù lên người dơi, cảnh sát và các ông trùm. Điên cuồng vì mất nàng Dawes, luật sư Dent trở thành kẻ Lưỡng diện xấu xa, trong số các nạn nhân của y suýt nữa có cả người dơi và toàn bộ gia đình trung úy Gordon. Không muốn dân chúng Gotham mất tinh thần khi biết sự thật về người đứng đầu thành phố, Người dơi quyết định đứng ra nhận tội thay Dent...', 0, 0, 'Christian Bale, Heath Ledger, Aaron Eckhart', 'Christopher Nolan', 2008, 'https://phimimg.com/upload/vod/20240104-1/2cae46be24f514793c9e873f6de0fc32.jpg', 'https://s2.phim1280.tv/20240120/Cy7c7Mg8/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20240104-1/b2748e456090a1c43b246c014b60fa1a.jpg', 1, '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(9, 'san-dau-sinh-tu', 'Fight Club', 'Sàn Đấu Sinh Tử', 'Chính Kịch, Âu Mỹ', 'Cuộc gặp gỡ định mệnh của một nhân viên giám định bảo hiểm và một gã lông bông bán xà phòng trên chuyến bay đã mở ra một cuộc phiêu lưu kỳ lạ. Với các "câu lạc bộ chiến đấu" ngầm hình thành ở mọi thị trấn, cuốn theo nhiều diễn biến phức tạp và một cái kết đầy kịch tính.', 0, 0, 'Edward Norton, Brad Pitt, Helena Bonham Carter, Meat Loaf, Jared Leto, Zach Grenier, Holt McCallany, Eion Bailey, Richmond Arquette, David Andrews', 'David Fincher', 1999, 'https://phimimg.com/upload/vod/20231014-1/9d29ae7c7285da93e8a3d117d58b8eea.jpg', 'https://s1.phim1280.tv/20231017/YDJU9iz0/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231014-1/11c145a6361f8e063d653eaee6f592dd.jpg', 1, '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(10, 'ma-tran', 'The Matrix', 'Ma Trận', 'Hành Động, Khoa Học, Âu Mỹ', 'The Matrix (MaTrận) là bộ phim khoa học giả tưởng về tương lai. Trong tương lai đó, Thomas, biệt danh Neo, là một hacker luôn có cảm giác khác thường về thế giới thực. Cho tới khi người đàn ông tự xưng là Morpheus tìm đến anh. Người đàn ông này nói cho anh biết, thế giới mà mọi người coi là thực chỉ là một chương trình giả lập để máy móc cai trị con người. Chỉ có một người có thể phá được Ma trận. Những người thật sống sót còn lại cuối cùng và những nhân viên Ma trận đều cùng đi tìm người đó. Và người đó chính là anh – Neo…', 0, 0, 'Keanu Reeves, Laurence Fishburne, Carrie-Anne Moss, Hugo Weaving, Joe Pantoliano, Marcus Chong, Anthony Ray Parker, Matt Doran, Gloria Foster, Belinda McClory', 'Lana Wachowski, Lilly Wachowski', 1999, 'https://phimimg.com/upload/vod/20231014-1/799d42634df3b35ae3fb28cf1bf14a9c.jpg', 'https://s5.phim1280.tv/20240911/Fg8j0dZu/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231014-1/19e1af28cf91bac65637b7089ac3a6a6.jpg', 1, '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(11, 'ky-sinh-trung', 'Parasite', 'Ký Sinh Trùng', 'Hài Hước, Tâm Lý, Chính Kịch, Hàn Quốc', 'Bộ phim xoay quanh hai gia đình hoàn toàn trái ngược: một bên vô cùng giàu có, còn một bên luôn sống trong cảnh nghèo túng. Họ tình cờ gặp gỡ và quen biết nhau sau một biến cố không ngờ.', 0, 0, 'Song Kang-ho, Lee Sun-kyun, Cho Yeo-jeong, Choi Woo-shik, Park So-dam, Lee Jung-eun, Jang Hye-jin, Park Myung-hoon, Jung Ji-so, Jung Hyeon-jun, Park Keun-rok, Jung Yi-seo, Cho Jae-myung, Jeong Ik-han, Kim Kyu-baek, Ahn Seong-bong, Yoon Young-woo, Park Jae', 'Bong Joon Ho', 2019, 'https://phimimg.com/upload/vod/20250223-1/43b06a10df87b9f14097b5f5212510a8.jpg', 'https://www.youtube.com/watch?v=o3ESQWArU2w', 'movie', 'https://phimimg.com/upload/vod/20250223-1/197144573d8adb78f386f0ab58682903.jpg', 1, '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(12, 'ho-den-tu-than', 'Interstellar', 'Hố Đen Tử Thần', 'Phiêu Lưu, Chính Kịch, Khoa Học, Viễn Tưởng, Anh, Âu Mỹ', 'Interstellar nói về chuyến hành trình đi tìm một hành tinh mới cho loài người trú ngụ khi mà Trái Đất dần dần không còn là nơi an toàn và sự sống thì đang chết dần với những dịch bệnh trên các loại cây lương thực, đe dọa đến sự tồn tại của loài người một cách mạnh mẽ. Chúng ta sẽ đi theo hành trình của phi hành đoàn gồm Copper, Amelia cùng các thành viên khác trong việc giải cứu các phi hành gia khác đã lên đường khám phá các hành tinh mới để tìm ra hành tinh mới cho loài người trú ngụ', 0, 0, 'Matthew McConaughey, Anne Hathaway, Michael Caine, Jessica Chastain, Casey Affleck, Wes Bentley, Topher Grace, Mackenzie Foy, Ellen Burstyn, John Lithgow, Bill Irwin, David Gyasi, Timothée Chalamet, Matt Damon, Josh Stewart, Leah Cairns, Liam Dickinson, F', 'Christopher Nolan', 2014, 'https://phimimg.com/upload/vod/20250302-1/4cee31c3afd0fbfd11ed4f14b1bfd816.jpg', 'https://www.youtube.com/watch?v=QqSp_dwslro', 'movie', 'https://phimimg.com/upload/vod/20250302-1/6012710a981edf1b6209bdb7fa82d1a6.jpg', 1, '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(13, 'vo-si-giac-dau', 'Gladiator', 'Võ Sĩ Giác Đấu', 'Hành Động, Chính Kịch, Phiêu Lưu, Anh, Âu Mỹ', 'Hoàng đế Marcus quyết định chọn vị tướng tin cẩn là Maximus làm người kế vị ngai vàng. Ðiều đó làm Commodus, con trai Marcus, nổi giận. Hắn đã giết cha để chiếm ngôi rồi hãm hại gia đình Maximus. Bản thân Maximus bị trở thành nô lệ, phải tham gia vào các cuộc thi giác đấu... Cuối cùng Maximus cũng về đến Rome và trả thù cho gia đình... Cuộc đời Maximus thay đổi từ ấy, từ một vị tướng oai dũng trở thành một kẻ nô lệ phải trải qua bao thử thách...', 0, 0, 'Russell Crowe, Joaquin Phoenix, Connie Nielsen, Oliver Reed, Richard Harris, Derek Jacobi, Djimon Hounsou, David Schofield, John Shrapnel, Tomas Arana', 'Ridley Scott', 2000, 'https://phimimg.com/upload/vod/20231014-1/b0d4f8f3e7c9a85e80dfcd8a9a9a379c.jpg', 'https://s1.phim1280.tv/20231017/SkHyXmPv/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231014-1/64d398a360904eaf83a9a5b98e599849.jpg', 1, '2025-04-06 08:30:26', '2025-04-06 08:30:26'),
	(14, 'chua-te-cua-nhung-chiec-nhan-su-tro-lai-cua-nha-vua', 'The Lord of the Rings: The Return of the King', 'Chúa Tể Của Những Chiếc Nhẫn: Sự Trở Lại Của Nhà Vua', 'Phiêu Lưu, Viễn Tưởng, Hành Động, New Zealand, Âu Mỹ', 'Sauron huy động toàn lực cho trận chiến khốc liệt cuối cùng, tấn công vào Gondor – vùng đất huyết mạch của loài người. Gondor dưới sự lãnh đạo của vị vua nhu nhược trở nên quá mong manh và nguy ngập. Đó chính là lúc những người anh hùng phát huy hết khả năng của mình. Cùng với sự hỗ trợ của các chiến binh Rohan, lực lượng những hồn ma bị nguyền rủa trong khe núi, Aragorn đã thống lĩnh những người Gondor chống lại Sauron, anh đã thực hiện sứ mệnh mà số phận giao phó cho mình, Aragorn sinh ra để trở thành Vua của Gondor, anh đã chứng minh điều đó. Cùng lúc đó, Frodo và Sam tiếp tục dấn sâu vào vùng đất ma quỷ đầy hiểm nguy để thực thi sứ mạng của mình, sứ mạng tiêu hủy Chiếc Nhẫn Quyền Lực. Có thể nói Sự Trở Lại Của Nhà Vua Phim là phần có nội dung hấp dẫn cùng những màn chiến đấu cực hay, cực kỳ hoành tráng.', 0, 1, 'Elijah Wood, Ian McKellen, Viggo Mortensen, Sean Astin, Andy Serkis, Dominic Monaghan, Billy Boyd, John Noble, David Wenham, Miranda Otto, Bernard Hill, John Rhys-Davies, Orlando Bloom, Hugo Weaving, Liv Tyler, Cate Blanchett, Karl Urban, Ian Holm, Sean B', 'Peter Jackson', 2003, 'https://phimimg.com/upload/vod/20250219-1/66cc63a6f719e7ad25992639b050ab57.jpg', 'https://www.youtube.com/watch?v=zckJCxYxn1g', 'movie', 'https://phimimg.com/upload/vod/20250219-1/55705980dbf57659fd445f17e7024ad2.jpg', 1, '2025-04-06 08:30:30', '2025-04-07 13:15:56'),
	(15, 'titanic', 'Titanic', 'Titanic', 'Chính Kịch, Tình Cảm, Âu Mỹ', 'Rose DeWitt Bukater, 101 tuổi, hồi nhớ về khoảng thời gian của bà trên tàu Titanic vào 84 năm trước . Khi bà còn là một cô gái trẻ lên tàu Titanic cùng mẹ và chồng sắp cưới. Định mệnh cho cô gặp Jack Dawson, một chàng họa sĩ trẻ tài năng. Rose kể toàn bộ câu chuyện của họ trên chuyến hành trình định mệnh đầu tiên và cuối cùng  của Titanic vào ngày 15 tháng 4 năm 1912.', 0, 0, 'Leonardo DiCaprio, Kate Winslet, Billy Zane, Kathy Bates, Frances Fisher, Gloria Stuart, Victor Garber, Bill Paxton, Bernard Hill, David Warner', 'James Cameron', 1997, 'https://phimimg.com/upload/vod/20231014-1/c1f84dac2ed61edc701980250e8affd7.jpg', 'https://s2.phim1280.tv/20231017/20MEMVbZ/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231014-1/1a68d864d144b4f3d8d2da38cf41f33b.jpg', 1, '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(16, 'su-im-lang-cua-bay-cuu', 'The Silence of the Lambs', 'Sự Im Lặng Của Bầy Cừu', 'Hình Sự, Chính Kịch, Tâm Lý, Âu Mỹ', 'Một cô gái trẻ mất tích và vụ án này có liên quan đến một kẻ giết người hàng loạt, để thỏa mãn cho những ý nghĩ bệnh hoạn của gã. Người duy nhất có thể làm vụ án sáng tỏ là tên tù nhân tâm thần, chuyên ăn thịt người Hannibal Lecter ( Anthony Hopkins ). Cô nhân viên trẻ Clarice được giao nhiệm vụ khai thác thông tin ở Hannibal. Từ đó, cô dần khám phá ra bí mật của những tiếng kêu kì lạ từ đàn cừu mà mình hằng ám ảnh…Một bộ phim đầy tính nhân bản được thể hiện qua những đoạn đốI thoại của Clarice và Hannibal và ở cái kết khiến người xem phải suy nghĩ rất nhiều…Bộ phim đã đoạt cùng lúc 5 giải Oscar quan trọng: giải phim hay nhất, đạo diễn, biên kịch, nam nữ diễn viên chính xuất sắc nhất. Đây là một thành tích ...', 0, 0, 'Jodie Foster, Anthony Hopkins, Scott Glenn, Ted Levine, Anthony Heald, Brooke Smith, Diane Baker, Kasi Lemmons, Frankie Faison, Tracey Walter, Charles Napier, Danny Darst, Alex Coleman, Dan Butler, Paul Lazar, Ron Vawter, Roger Corman, Lawrence A. Bonney', 'Jonathan Demme', 1991, 'https://phimimg.com/upload/vod/20231123-1/3481811832217e336a5167807211c228.jpg', 'https://s1.phim1280.tv/20231201/gp2Qvj7D/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231123-1/712e6c865714ad0ca495981aa11ed3b0.jpg', 1, '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(17, 'giai-cuu-binh-nhi-ryan', 'Saving Private Ryan', 'Giải Cứu Binh Nhì Ryan', 'Chính Kịch, Chiến Tranh, Âu Mỹ', 'Bắt đầu với cuộc xâm lược của quân Đồng minh Normandy vào ngày 6 tháng 6 năm 1944, các thành viên của tiểu đoàn tấn công thứ hai, dưới quyền Đại úy Miller, chiến đấu để giành quyền kiểm soát bãi biển. Trong trận chiến, hai anh em bị giết. Một chút trước đó, ở New Guinea, một người anh em thứ ba cũng bị giết. Mẹ của ông, bà Ryan, sẽ nhận được ba bức điện tử trong cùng một ngày. Người đứng đầu quân đội Mỹ, George C. Marshall, nhìn thấy cơ hội làm giảm bớt nỗi đau khổ của cô khi anh biết được một người anh em thứ tư, Private James Ryan, và quyết định cử tám người (Thuyền trưởng Miller và một nhóm được chọn từ tiểu đoàn của anh ta) đi tìm Anh ta và đưa anh ta trở về nhà của mẹ mình.', 0, 2, 'Tom Hanks, Tom Sizemore, Edward Burns, Matt Damon, Barry Pepper, Adam Goldberg, Vin Diesel, Giovanni Ribisi, Ted Danson, Jeremy Davies', 'Steven Spielberg', 1998, 'https://phimimg.com/upload/vod/20231014-1/d80dbd8e53e1e5c495e4ba35feba14cb.jpg', 'https://s1.phim1280.tv/20231017/YqTHKOJY/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231014-1/874adf6e586960b9bde6af4989e19681.jpg', 1, '2025-04-06 08:30:30', '2025-04-07 13:33:19'),
	(18, 'quan-giao-va-tu-tu', 'The Green Mile', 'Quản Giáo Và Tử Tù', 'Tâm Lý, Hình Sự, Viễn Tưởng, Khoa Học, Bí Ẩn, Âu Mỹ', 'Đây là câu chuyện hồi tưởng của một viên coi ngục cai quản những tên tử tù bất trị chờ ngày lên ngồi ghế điện trả giá cho những tội ác mà bọn chúng gây ra. Cuộc sống của ông thay đổi hoàn toàn kể từ khi một người tù da đen có khả năng kỳ lạ xuất hiện trong hầm ngục của ông.', 0, 0, 'Tom Hanks, Michael Clarke Duncan, David Morse', 'Frank Darabont', 1999, 'https://phimimg.com/upload/vod/20240207-1/e696f985732a857c2d8e4ecb8053b398.jpg', 'https://s2.phim1280.tv/20240219/SAOE0IDo/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20240207-1/3bca0630fd93caade43f0e838c80b6ca.jpg', 1, '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(19, 'mufasa-vua-su-tu', 'Mufasa: The Lion King', 'Mufasa: Vua Sư Tử', 'Phiêu Lưu, Gia Đình, Trẻ Em, Âu Mỹ', 'Mufasa: Vua Sư Tử là phần tiền truyện của bộ phim hoạt hình Vua Sư Tử trứ danh, kể về cuộc đời của Mufasa - cha của Simba. Phim là hành trình Mufasa từ một chú sư tử mồ côi lạc đàn trở thành vị vua sư tử huyền thoại của Xứ Vua (Pride Land). Ngoài ra, quá khứ về tên phản diện Scar và hành trình hắc hóa của hắn cũng sẽ được phơi bày trong phần phim này.', 4.5, 3, 'Aaron Pierre, Kelvin Harrison Jr., Tiffany Boone, Kagiso Lediga, Preston Nyman, Blue Ivy Carter, John Kani, Mads Mikkelsen, Seth Rogen, Billy Eichner, Thandiwe Newton, Lennie James, Anika Noni Rose, Keith David, Braelyn Rankins, Theo Somolu, Donald Glover', 'Barry Jenkins', 2024, 'https://phimimg.com/upload/vod/20250105-1/c1af45f8ba5635c1ad904b0b701fcb8d.jpg', 'https://www.youtube.com/watch?v=lMXh6vjiZrI', 'movie', 'https://phimimg.com/upload/vod/20250105-1/abea2fe4af2530fa1a668f51a1bc9758.jpg', 1, '2025-04-06 08:30:30', '2025-04-06 01:38:27'),
	(20, 'ao-thuat-gia-dau-tri', 'The Prestige', 'Ảo Thuật Gia Đấu Trí', 'Chính Kịch, Bí Ẩn, Khoa Học, Anh, Âu Mỹ', 'The Prestige xoay quanh hai ảo thuật gia đại tài là Robert Angier (Hugh Jackman) và Alfred Borden (Christian Bale). Ban đầu họ là những người bạn, thế nhưng bởi giấc mơ tạo ra tiết mục hấp dẫn nhất quá lớn, khiến họ đã bị cuốn vào một cuộc cạnh tranh một mất một còn với kết cục bi thảm.  Cũng mang đậm dấu ấn như bao phim khác của Christopher Nolan, The Prestige có kết cấu tương đối phức tạp. Cách kể chuyện không theo trình tự thời gian dẫn đến có đôi chỗ người xem sẽ cảm thấy mơ hồ, khó nắm bắt.', 0, 0, 'Christian Bale, Hugh Jackman, Michael Caine, Scarlett Johansson, Rebecca Hall, David Bowie, Andy Serkis, Piper Perabo, Jim Piddock, Samantha Mahurin, Mark Ryan, Jamie Harris, Daniel Davis, Roger Rees, Ricky Jay, Christopher Neame, Monty Stuart, Ron Perkin', 'Christopher Nolan', 2006, 'https://phimimg.com/upload/vod/20231020-1/31cc9cdbc15c942e8a0244cb20ee7182.jpg', 'https://s1.phim1280.tv/20231021/VSPcYDVn/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231020-1/033f14d49221c32831cb50b655dafee3.jpg', 1, '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(21, 'avengers-hoi-ket', 'Avengers: Endgame', 'Avengers: Hồi Kết', 'Phiêu Lưu, Khoa Học, Hành Động, Âu Mỹ', 'Sau sự kiện hủy diệt tàn khốc, vũ trụ chìm trong cảnh hoang tàn. Với sự trợ giúp của những đồng minh còn sống sót, biệt đội siêu anh hùng Avengers tập hợp một lần nữa để đảo ngược hành động của Thanos và khôi phục lại trật tự của vũ trụ.', 0, 0, 'Robert Downey Jr., Chris Evans, Mark Ruffalo, Chris Hemsworth, Scarlett Johansson, Jeremy Renner, Don Cheadle, Paul Rudd, Benedict Cumberbatch, Chadwick Boseman, Brie Larson, Tom Holland, Karen Gillan, Zoe Saldaña, Evangeline Lilly, Tessa Thompson, Rene R', 'Joe Russo, Anthony Russo', 2019, 'https://phimimg.com/upload/vod/20231018-1/88c1ee81bbfcd39a73db1f83203b5501.jpg', 'https://s2.phim1280.tv/20231019/3g1f5kg4/index.m3u8', 'movie', 'https://phimimg.com/upload/vod/20231018-1/3541f605986d7911e5c8d38db5b06447.jpg', 1, '2025-04-06 08:30:30', '2025-04-06 08:30:30'),
	(22, 'tro-choi-vuong-quyen-phan-1', 'Game Of Thrones (Season 1)', 'Trò Chơi Vương Quyền (Phần 1)', 'Hành Động, Chiến Tranh, Viễn Tưởng, Phiêu Lưu, Âu Mỹ', 'Tại vùng đất Westeros, bảy gia tộc quyền lực tranh giành Ngai Sắt. Vua Robert Baratheon mời người bạn cũ Eddard Stark làm Cánh Tay Phải, kéo ông vào vòng xoáy âm mưu chính trị tại King\'s Landing. Trong khi đó, ở bên kia Biển Hẹp, những người cuối cùng của gia tộc Targaryen bị lưu đày tìm cách trở lại. Và ở phương Bắc xa xôi, một mối đe dọa cổ xưa đang thức giấc.', 4.5, 23, 'Charles Dance, John Bradley, Isaac Hempstead-Wright', 'Daniel Minahan', 2011, 'https://phimimg.com/upload/vod/20240730-1/8bc4dbfd34d8c5e5c59a3c4eed2cefbd.jpg', 'https://www.youtube.com/watch?v=BpJYNVhGf1s', 'series', 'https://phimimg.com/upload/vod/20240730-1/8be14e4426c02652dcec30254e2988f0.jpg', 3, '2025-04-06 08:30:33', '2025-04-09 22:28:14'),
	(23, 'tro-choi-vuong-quyen-phan-2', 'Game Of Thrones (Season 2)', 'Trò Chơi Vương Quyền (Phần 2)', 'Hành Động, Chiến Tranh, Viễn Tưởng, Phiêu Lưu, Âu Mỹ', 'Cuộc Chiến Ngũ Vương bùng nổ trên khắp Westeros. Tại King\'s Landing, Tyrion Lannister cố gắng kiểm soát Vua Joffrey trẻ tuổi và tàn bạo. Robb Stark giành những chiến thắng ở phương Bắc. Stannis Baratheon, với sự giúp đỡ của Nữ Tu Sĩ Đỏ Melisandre, chuẩn bị tấn công thủ đô. Daenerys Targaryen dẫn dắt những người theo mình qua sa mạc khắc nghiệt. Jon Snow tiến sâu vào vùng đất băng giá phía Bắc Bức Tường.', 2.5, 44, 'Iain Glen, Emilia Clarke, Lena Headey', 'D.b. Weiss, David Benioff', 2012, 'https://phimimg.com/upload/vod/20240730-1/d31b8be8fcc5409cce4ec2d016806ba5.jpg', 'https://www.youtube.com/watch?v=XuKfFzk1uQs', 'series', 'https://phimimg.com/upload/vod/20240730-1/bc8c1ee26261c9fa192d253088f23ed2.jpg', 3, '2025-04-06 08:30:33', '2025-04-07 13:17:01'),
	(24, 'tro-choi-vuong-quyen-phan-3', 'Game Of Thrones (Season 3)', 'Trò Chơi Vương Quyền (Phần 3)', 'Hành Động, Chiến Tranh, Viễn Tưởng, Phiêu Lưu, Âu Mỹ', 'Sau trận Blackwater, nhà Lannister củng cố quyền lực tại King\'s Landing. Robb Stark đối mặt với những thách thức mới trong cuộc chiến. Jon Snow thâm nhập vào hàng ngũ Wildlings. Daenerys Targaryen bắt đầu xây dựng đội quân của mình tại Vịnh Nô Lệ. Những liên minh mới được hình thành và những mối thù cũ sâu sắc hơn, dẫn đến những sự kiện bi thảm làm thay đổi cục diện Westeros mãi mãi.', 0, 6, 'Peter Dinklage, Michelle Fairley, Lena Headey', 'Daniel Minahan, Alex Graves', 2013, 'https://phimimg.com/upload/vod/20240730-1/28dc57f591a7fa3ea6787b7c3fa5d4f2.jpg', 'https://www.youtube.com/watch?v=etLVobwWH1M', 'series', 'https://phimimg.com/upload/vod/20240730-1/1f9108b2b2df89e9d6922c5803faa810.jpg', 3, '2025-04-06 08:30:33', '2025-04-07 08:28:52'),
	(25, 'tro-choi-vuong-quyen-phan-4', 'Game Of Thrones (Season 4)', 'Trò Chơi Vương Quyền (Phần 4)', 'Hành Động, Chiến Tranh, Viễn Tưởng, Phiêu Lưu, Âu Mỹ', 'Westeros rung chuyển sau Đám Cưới Đỏ. Nhà Lannister tổ chức đám cưới hoàng gia cho Joffrey, nhưng niềm vui ngắn chẳng tày gang. Tyrion bị buộc tội và đối mặt với phiên tòa định mệnh. Jon Snow cảnh báo về mối đe dọa từ Wildlings và Bóng Trắng tại Bức Tường. Daenerys tiếp tục chinh phạt các thành phố nô lệ ở Essos. Những nhân vật mới xuất hiện, mang theo tham vọng và nguy hiểm riêng.', 0, 0, 'Kit Harington, Lena Headey, Natalie Dormer, Charles Dance, Nikolaj Coster-Waldau, Alfie Allen', 'Daniel Minahan, Alex Graves', 2014, 'https://phimimg.com/upload/vod/20240205-1/4f47f80734bd9092289241b69ea1f92f.jpg', 'https://www.youtube.com/watch?v=Z1Y_qhzxn2c', 'series', 'https://phimimg.com/upload/vod/20240205-1/3a9626c89bae7f9a723b15493d3130f4.jpg', 3, '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(26, 'tro-choi-vuong-quyen-phan-5', 'Game Of Thrones (Season 5)', 'Trò Chơi Vương Quyền (Phần 5)', 'Hành Động, Chiến Tranh, Viễn Tưởng, Phiêu Lưu, Âu Mỹ', 'Quyền lực thay đổi ở Westeros và Essos. Tại King\'s Landing, một nhóm tôn giáo cực đoan, Sparrows, trỗi dậy thách thức nhà Lannister. Jon Snow trở thành Chỉ Huy Trưởng Đội Gác Đêm và phải đối mặt với quyết định khó khăn về Wildlings. Tyrion tìm đường đến gặp Daenerys, người đang vật lộn để cai trị Meereen và kiểm soát những con rồng của mình. Arya bắt đầu quá trình huấn luyện tại Ngôi nhà Trắng Đen ở Braavos.', 0, 4, 'Peter Dinklage, Lena Headey, Kit Harington, Emilia Clarke, Maisie Williams, Iain Glen, Sophie Turner, Nikolaj Coster-Waldau, John Bradley, Alfie Allen, Aidan Gillen, Charles Dance, Rory Mccann, Conleth Hill, Jack Gleeson, Michelle Fairley, Isaac Hempstead', 'Đang cập nhật', 2015, 'https://phimimg.com/upload/vod/20240730-1/ec35080ed8c5585827cb1073c2668eb4.jpg', 'https://www.youtube.com/watch?v=wViILXQfX7Y', 'series', 'https://phimimg.com/upload/vod/20240730-1/2da6911b4042ea43e6975a45256321f4.jpg', 3, '2025-04-06 08:30:33', '2025-04-07 08:28:43'),
	(27, 'tro-choi-vuong-quyen-phan-6', 'Game Of Thrones (Season 6)', 'Trò Chơi Vương Quyền (Phần 6)', 'Hành Động, Chiến Tranh, Viễn Tưởng, Phiêu Lưu, Âu Mỹ', 'Sau những sự kiện chấn động cuối phần 5, các nhân vật phải đối mặt với hậu quả. Số phận của Jon Snow được tiết lộ. Daenerys gặp lại người Dothraki. Bran tiếp tục rèn luyện khả năng nhìn thấy quá khứ và tương lai. Tại King\'s Landing, Cersei tìm cách trả thù. Nhà Stark bắt đầu tập hợp lại lực lượng để giành lại Winterfell từ tay Ramsay Bolton, dẫn đến một trong những trận chiến lớn nhất lịch sử Westeros.', 0, 0, 'Peter Dinklage, Lena Headey, Emilia Clarke, Kit Harington, Sophie Turner, Maisie Williams, Iain Glen, John Bradley, Nikolaj Coster-Waldau, Alfie Allen, Aidan Gillen, Conleth Hill, Charles Dance, Rory Mccann, Jerome Flynn, Gwendoline Christie, Julian Glove', 'Đang cập nhật', 2016, 'https://phimimg.com/upload/vod/20240730-1/c91c5f43a15e569233ff1105cea59a52.jpg', 'https://www.youtube.com/watch?v=CuH3tJPiP-U', 'series', 'https://phimimg.com/upload/vod/20240730-1/064053d9650ea7139ea8ce936bdc026a.jpg', 3, '2025-04-06 08:30:33', '2025-04-06 08:30:33'),
	(28, 'tro-choi-vuong-quyen-phan-7', 'Game Of Thrones (Season 7)', 'Trò Chơi Vương Quyền (Phần 7)', 'Hành Động, Chiến Tranh, Viễn Tưởng, Phiêu Lưu, Âu Mỹ', 'Mùa đông đã đến. Daenerys Targaryen cuối cùng cũng đặt chân đến Westeros cùng đội quân và những con rồng của mình, sẵn sàng đối đầu Cersei Lannister để giành Ngai Sắt. Jon Snow, Vua phương Bắc, nhận ra mối đe dọa thực sự đến từ Bóng Trắng và tìm cách thuyết phục các thế lực khác liên minh chống lại kẻ thù chung. Các liên minh được thử thách, những cuộc hội ngộ được chờ đợi diễn ra, và cuộc chiến vĩ đại đang đến gần.', 0, 2, 'Peter Dinklage, Lena Headey, Emilia Clarke, Kit Harington, Sophie Turner, Maisie Williams, Nikolaj Coster-Waldau, Iain Glen, Alfie Allen, John Bradley, Conleth Hill, Aidan Gillen, Gwendoline Christie, Isaac Hempstead Wright, Jerome Flynn, Julian Glover, L', 'Đang cập nhật', 2017, 'https://phimimg.com/upload/vod/20240730-1/95a5021bc16a2062095163b36016aebf.jpg', 'https://www.youtube.com/watch?v=P5sHoNib7tg', 'series', 'https://phimimg.com/upload/vod/20240730-1/586cc14fa8d154dd4250065e8f026236.jpg', 3, '2025-04-06 08:30:33', '2025-04-07 08:28:49'),
	(29, 'tro-choi-vuong-quyen-phan-8', 'Game Of Thrones (Season 8)', 'Trò Chơi Vương Quyền (Phần 8)', 'Hành Động, Chiến Tranh, Viễn Tưởng, Phiêu Lưu, Âu Mỹ', 'Mùa cuối cùng của Trò Chơi Vương Quyền đã đến. Đội quân Bóng Trắng do Dạ Đế dẫn đầu tiến về Winterfell, buộc những người còn sống phải hợp lực trong trận chiến định mệnh cho nhân loại. Nhưng ngay cả khi mối đe dọa từ phương Bắc được giải quyết, cuộc chiến giành Ngai Sắt vẫn tiếp diễn với những hậu quả tàn khốc và bất ngờ, quyết định số phận cuối cùng của Westeros và những nhân vật chúng ta đã theo dõi.', 0, 2, 'Peter Dinklage, Lena Headey, Emilia Clarke, Kit Harington, Sophie Turner, Maisie Williams, Nikolaj Coster-Waldau, Iain Glen, Alfie Allen, John Bradley, Conleth Hill, Aidan Gillen, Isaac Hempstead Wright, Gwendoline Christie, Liam Cunningham, Jerome Flynn', 'D.b. Weiss, David Benioff', 2019, 'https://phimimg.com/upload/vod/20240730-1/49f2dafb4fca2fa2755fc568fbd4757e.jpg', 'https://www.youtube.com/watch?v=rlR4PJn8b8I', 'series', 'https://phimimg.com/upload/vod/20240730-1/a758db8f7dd8192353b767a57c9e1920.jpg', 3, '2025-04-06 08:30:33', '2025-04-07 11:36:59');

-- Dumping structure for table movie_db.movie_package
CREATE TABLE IF NOT EXISTS `movie_package` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `movie_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `movie_package_movie_id_package_id_unique` (`movie_id`,`package_id`),
  KEY `movie_package_package_id_foreign` (`package_id`),
  CONSTRAINT `movie_package_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movie_package_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.movie_package: ~45 rows (approximately)
DELETE FROM `movie_package`;
INSERT INTO `movie_package` (`id`, `movie_id`, `package_id`, `created_at`, `updated_at`) VALUES
	(1, 20, 2, NULL, NULL),
	(2, 2, 2, NULL, NULL),
	(3, 5, 2, NULL, NULL),
	(4, 14, 2, NULL, NULL),
	(5, 6, 2, NULL, NULL),
	(6, 7, 2, NULL, NULL),
	(7, 17, 2, NULL, NULL),
	(8, 12, 2, NULL, NULL),
	(9, 4, 2, NULL, NULL),
	(10, 8, 2, NULL, NULL),
	(11, 10, 2, NULL, NULL),
	(12, 19, 2, NULL, NULL),
	(13, 3, 2, NULL, NULL),
	(14, 18, 2, NULL, NULL),
	(15, 9, 2, NULL, NULL),
	(16, 1, 2, NULL, NULL),
	(17, 20, 3, NULL, NULL),
	(18, 21, 3, NULL, NULL),
	(19, 2, 3, NULL, NULL),
	(20, 5, 3, NULL, NULL),
	(21, 14, 3, NULL, NULL),
	(22, 6, 3, NULL, NULL),
	(23, 7, 3, NULL, NULL),
	(24, 17, 3, NULL, NULL),
	(25, 12, 3, NULL, NULL),
	(26, 4, 3, NULL, NULL),
	(27, 8, 3, NULL, NULL),
	(28, 11, 3, NULL, NULL),
	(29, 10, 3, NULL, NULL),
	(30, 19, 3, NULL, NULL),
	(31, 1, 3, NULL, NULL),
	(32, 3, 3, NULL, NULL),
	(33, 18, 3, NULL, NULL),
	(34, 9, 3, NULL, NULL),
	(35, 16, 3, NULL, NULL),
	(36, 15, 3, NULL, NULL),
	(37, 22, 3, NULL, NULL),
	(38, 23, 3, NULL, NULL),
	(39, 24, 3, NULL, NULL),
	(40, 25, 3, NULL, NULL),
	(41, 26, 3, NULL, NULL),
	(42, 27, 3, NULL, NULL),
	(43, 28, 3, NULL, NULL),
	(45, 13, 3, NULL, NULL),
	(46, 19, 4, NULL, NULL),
	(47, 1, 4, NULL, NULL),
	(48, 9, 4, NULL, NULL),
	(49, 13, 4, NULL, NULL),
	(50, 22, 2, NULL, NULL);

-- Dumping structure for table movie_db.packages
CREATE TABLE IF NOT EXISTS `packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int NOT NULL,
  `features` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.packages: ~3 rows (approximately)
DELETE FROM `packages`;
INSERT INTO `packages` (`id`, `name`, `description`, `price`, `duration_days`, `features`, `is_active`, `created_at`, `updated_at`) VALUES
	(2, 'VIP', 'Gói cước phục vụ cho khách hàng có nhu cầu xem nhiều phim ko giới hạn', 120000.00, 30, 'Xem các phim thuộc VIP.\r\nKhông quản cáo.', 1, '2025-04-05 23:44:41', '2025-04-05 23:44:41'),
	(3, 'PRO', 'Gói PRO bao gồm tất cả các gói VIP', 280000.00, 30, 'Truy cập tất cả phim\r\nTất cả tính năng của gói VIP', 1, '2025-04-06 01:44:36', '2025-04-06 01:44:36'),
	(4, 'Basic', 'Gói thường', 0.00, 3600, 'Truy cập các phim của gói', 1, '2025-04-06 02:13:00', '2025-04-06 02:13:00');

-- Dumping structure for table movie_db.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table movie_db.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `subscription_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` datetime NOT NULL,
  `status` enum('success','pending','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_user_id_foreign` (`user_id`),
  KEY `payments_subscription_id_foreign` (`subscription_id`),
  CONSTRAINT `payments_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.payments: ~2 rows (approximately)
DELETE FROM `payments`;
INSERT INTO `payments` (`id`, `user_id`, `subscription_id`, `amount`, `payment_method`, `payment_date`, `status`, `created_at`, `updated_at`) VALUES
	(30, 1, 4, 280000.00, 'vnpay', '2025-04-07 17:56:26', 'success', '2025-04-07 10:56:26', '2025-04-07 10:57:08'),
	(31, 1, 4, 280000.00, 'vnpay', '2025-04-10 05:21:51', 'success', '2025-04-09 22:21:51', '2025-04-09 22:23:12');

-- Dumping structure for table movie_db.ratings
CREATE TABLE IF NOT EXISTS `ratings` (
  `user_id` bigint unsigned NOT NULL,
  `movie_id` bigint unsigned NOT NULL,
  `rating_value` decimal(3,1) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`,`movie_id`),
  KEY `ratings_movie_id_foreign` (`movie_id`),
  CONSTRAINT `ratings_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.ratings: ~2 rows (approximately)
DELETE FROM `ratings`;
INSERT INTO `ratings` (`user_id`, `movie_id`, `rating_value`, `created_at`, `updated_at`) VALUES
	(1, 19, 4.5, NULL, NULL),
	(1, 22, 4.5, NULL, NULL),
	(1, 23, 2.5, NULL, NULL);

-- Dumping structure for table movie_db.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.sessions: ~4 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('FYwepo1RNw6LCrhOd6L9SqiPJFAqYlBc36JWQAyp', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZTdRdklkV1dadzRzMnRKUWRiVjhtYnFVUERhM1MzU2o0Y0FVWGNVUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9wYXltZW50cyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1744290786),
	('IfEjMmmr8nM7IhTcNU5Ew9P8ctFpCtwvYAhlAD4C', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZVhJSUhNWU9kY01TdVZabXZkdUlCNXFieE1pS2UxMzV1a05CMDkzVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1744261920),
	('mBidt6UlkJey4NhM1smyC1EFoxH8ySuv324Lc1iJ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVEFzOU42VW5ZTjNNNUNpTWpkenhDVzdDUVZJdHlIV1BGZFRzazVKUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9zdWJzY3JpcHRpb25zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1744058035),
	('ztrKrQNAA17Q7DiDZn296TezANoFaNPjFj4W6orz', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTmdKTWpKVlBydkVUNFhXd3diU3I0YzByaFQ5d3p2eUVpbkI4NFJsSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wYWNrYWdlLzMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1744262233);

-- Dumping structure for table movie_db.subscriptions
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('active','expired','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_user_id_foreign` (`user_id`),
  KEY `subscriptions_package_id_foreign` (`package_id`),
  CONSTRAINT `subscriptions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.subscriptions: ~1 rows (approximately)
DELETE FROM `subscriptions`;
INSERT INTO `subscriptions` (`id`, `user_id`, `package_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
	(4, 1, 3, '2025-04-10 05:23:12', '2025-05-10 05:23:12', 'active', '2025-04-07 10:57:08', '2025-04-09 22:23:12');

-- Dumping structure for table movie_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user','editor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table movie_db.users: ~1 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `email`, `email_verified_at`, `password`, `role`, `full_name`, `remember_token`, `avatar`, `created_at`, `updated_at`) VALUES
	(1, 'Hậu (Admin)', 'we.nobita1@gmail.com', NULL, '$2y$12$Rl8R2Z30mfxgSIGIJ8IRee3fVkxkD3hUPuoCOPRTHE.aqOheoVsMu', 'admin', 'Lý Trung Hậu', NULL, '/avatars/1744289246_hinh-nen-anime-4k-cho-pc_062427500.jpg', '2025-04-03 05:00:28', '2025-04-10 05:47:26');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
