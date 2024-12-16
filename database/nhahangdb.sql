-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2024 at 02:39 PM
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
-- Database: `nhahangdb`
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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_code`, `name`, `slug`, `description`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'HS', 'Hải sản', 'hai-san', NULL, 'uploads/categories/1732466386.jpg', 1, '2024-11-24 09:39:46', '2024-11-25 01:52:56', NULL),
(2, 'KV', 'Khai vị', 'khai-vi', NULL, 'uploads/categories/1732524496.jpg', 1, '2024-11-25 01:48:16', '2024-11-25 01:48:16', NULL),
(3, 'TM', 'Tráng miệng', 'trang-mieng', NULL, 'uploads/categories/1732524533.jpg', 1, '2024-11-25 01:48:53', '2024-11-25 01:48:53', NULL),
(4, 'TB', 'Thịt', 'thit', NULL, 'uploads/categories/1732524613.jpg', 1, '2024-11-25 01:50:13', '2024-11-26 07:26:01', NULL),
(5, 'MN', 'Món nước', 'mon-nuoc', NULL, 'uploads/categories/1732524657.jpg', 1, '2024-11-25 01:50:57', '2024-11-25 01:50:57', NULL),
(6, 'DR', 'Đồ uống', 'do-uong', NULL, 'uploads/categories/1732524715.jpg', 1, '2024-11-25 01:51:55', '2024-11-25 01:51:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_code` varchar(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `position` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `menu_code`, `name`, `slug`, `description`, `price`, `image`, `category_code`, `status`, `position`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'MENUTM20241125', 'Bánh plan', 'banh-plan', 'Bánh plan với các nguyên liệu hảo hạn được nhập từ Pháp.', 25000.00, 'uploads/menu/1732595419_dessert.jpg', 'TM', 1, 1, '2024-11-25 02:30:25', '2024-11-25 21:30:19', NULL),
(2, 'MENUTM2024112502', 'Yaourt nhiệt đới', 'yaourt-nhiet-doi', 'Món yaourt nhiệt đới được pha trộn nhiều loại trái cây tự nhiên, tươi mát đến từ hòn đảo Caribbean nổi tiếng.', 50000.00, 'uploads/menu/1732595496_dessert2.jpg', 'TM', 1, 2, '2024-11-25 02:35:26', '2024-11-25 21:31:36', NULL),
(3, 'MENUDR20241125', 'Sinh tố dâu', 'sinh-to-dau', 'Sinh tố dâu được làm từ những quả dâu ngon nhất từ vùng đất Đà lạt xa xôi, được hòa quyện với các nguyên liệu tự nhiên.', 35000.00, 'uploads/menu/1732595515_drink1.jpg', 'DR', 1, 3, '2024-11-25 02:39:34', '2024-11-25 21:31:55', NULL),
(4, 'MENUDR2024112502', 'Nước cam', 'nuoc-cam', 'Nước cam tự nhiên', 30000.00, 'uploads/menu/1732595528_drink2.jpg', 'DR', 1, 4, '2024-11-25 02:41:55', '2024-11-25 21:32:08', NULL),
(5, 'MENUDR2024112503', 'Nước cam cao cấp', 'nuoc-cam-cao-cap', 'Nước cam thanh mát', 50000.00, 'uploads/menu/1732595539_drink3.jpg', 'DR', 1, 5, '2024-11-24 20:56:50', '2024-11-25 21:32:19', NULL),
(6, 'MENUDR2024112504', 'Bia tươi', 'bia-tuoi', 'Như một bức tranh, mỗi cóc bia đều có câu chuyện riêng của nó', 100000.00, 'uploads/menu/1732595548_drink4.jpg', 'DR', 1, 6, '2024-11-25 00:21:15', '2024-11-25 21:32:28', NULL),
(7, 'MENUDR2024112505', 'Coca Cola', 'coca-cola', 'sảng khoái hơn với Coca', 20000.00, 'uploads/menu/1732595582_drink5.jpg', 'DR', 1, 7, '2024-11-25 00:31:07', '2024-11-25 21:33:02', NULL),
(8, 'MENUDR2024112506', 'Nước mát hoa quả', 'nuoc-mat-hoa-qua', 'Nước hoa quả thanh lọc cơ thể của bạn', 40000.00, 'uploads/menu/1732595597_drink6.jpg', 'DR', 1, 8, '2024-11-25 00:33:39', '2024-11-25 21:33:17', NULL),
(9, 'MENUDR2024112507', 'Rượu nho', 'ruou-nho', 'Hãy cùng nhau nâng ly, vì tình bạn là thứ đáng trân trọng nhất', 150000.00, 'uploads/menu/1732595610_drink7.jpg', 'DR', 1, 9, '2024-11-25 00:35:23', '2024-11-25 21:33:30', NULL),
(10, 'MENUDR2024112508', 'Nước ép trái cây tươi', 'nuoc-ep-trai-cay-tuoi', 'Trái cây tươi ngon, nước ép thơm mát, giải nhiệt mùa hè hiệu quả nhất!', 45000.00, 'uploads/menu/1732595622_drink8.jpg', 'DR', 1, 10, '2024-11-25 00:37:05', '2024-11-25 21:33:42', NULL),
(11, 'MENUDR2024112509', 'Nước ép Kiwi', 'nuoc-ep-kiwi', 'Trái cây ép trực tiếp, mang đến vị ngon tự nhiên, tươi mát khó cưỡng', 50000.00, 'uploads/menu/1732595636_drink9.jpg', 'DR', 1, 11, '2024-11-25 00:37:57', '2024-11-25 21:33:56', NULL),
(12, 'MENUHS20241125', 'Tôm sốt thái', 'tom-sot-thai', 'Ăn là sẽ nhớ - nhớ rồi sẽ tới ăn', 200000.00, 'uploads/menu/1732595673_hai_san.jpg', 'HS', 1, 12, '2024-11-25 00:44:04', '2024-11-25 21:34:33', NULL),
(13, 'MENUHS2024112502', 'Súp tôm càng', 'sup-tom-cang', 'Nhìn là thích - CHÉN là NGON', 200000.00, 'uploads/menu/1732595690_hai_san2.jpg', 'HS', 1, 13, '2024-11-25 00:45:04', '2024-11-25 21:34:50', NULL),
(14, 'MENUHS2024112503', 'Hào tươi', 'hao-tuoi', 'Đừng ăn thỏa đói, đừng nói thỏa giận', 100000.00, 'uploads/menu/1732595868_hai_san3.jpg', 'HS', 1, 14, '2024-11-25 00:47:30', '2024-11-25 21:37:48', NULL),
(15, 'MENUHS2024112504', 'Tôm hùm Alaska', 'tom-hum-alaska', 'Chỉ cần là bạn thích - có ngay món ngon', 500000.00, 'uploads/menu/1732595879_hai_san4.jpg', 'HS', 1, 15, '2024-11-25 00:50:06', '2024-11-25 21:37:59', NULL),
(16, 'MENUHS2024112505', 'Hải sản thập cẩm', 'hai-san-thap-cam', 'Cá nào cũng có - Tôm nào cũng ngon', 400000.00, 'uploads/menu/1732595889_hai_san5.jpg', 'HS', 1, 16, '2024-11-25 00:51:05', '2024-11-25 21:38:09', NULL),
(17, 'MENUKV20241125', 'Salad  dầu giấm', 'salad-dau-giam', 'Để có một sức khỏe tốt, hãy đầu tư vào chế độ ăn uống lành mạnh', 70000.00, 'uploads/menu/1732595908_khai_vi.jpg', 'KV', 1, 17, '2024-11-25 00:54:50', '2024-11-25 21:38:28', NULL),
(18, 'MENUKV2024112502', 'Salad  rau củ', 'salad-rau-cu', 'Rau tươi, củ ngọt, ngại gì mà không ăn', 70000.00, 'uploads/menu/1732595917_khai_vi2.jpg', 'KV', 1, 18, '2024-11-25 00:55:58', '2024-11-25 21:38:37', NULL),
(19, 'MENUKV2024112503', 'Salad thịt xông khói', 'salad-thit-xong-khoi', 'Hương thơm lan tỏa khắp nơi', 100000.00, 'uploads/menu/1732595935_khai_vi3.jpg', 'KV', 1, 19, '2024-11-25 00:57:12', '2024-11-25 21:38:55', NULL),
(20, 'MENUKV2024112504', 'Salad hải sản', 'salad-hai-san', 'Có rau, có cá, cả 2 hòa huyện', 120000.00, 'uploads/menu/1732595950_khai_vi4.jpg', 'KV', 1, 20, '2024-11-25 00:58:51', '2024-11-25 21:39:10', NULL),
(21, 'MENUKV2024112505', 'Cá sốt đặc biệt', 'ca-sot-dac-biet', 'Cá mềm ăn kèm sốt đặc biệt, hương vị thật không thể chê', 180000.00, 'uploads/menu/1732595962_khai_vi5.jpg', 'KV', 1, 21, '2024-11-25 01:00:09', '2024-11-25 21:39:22', NULL),
(22, 'MENUKV2024112506', 'Bò kho bánh mì', 'bo-kho-banh-mi', 'Đậm đà hương vị Việt', 90000.00, 'uploads/menu/1732595977_khai_vi6.jpg', 'KV', 1, 22, '2024-11-25 01:05:53', '2024-11-25 21:39:37', NULL),
(23, 'MENUKV2024112507', 'Cơm cuộn Nhật', 'com-cuon-nhat', 'Món ăn đến từ Nhật Bản', 150000.00, 'uploads/menu/1732595987_khai_vi7.jpg', 'KV', 1, 23, '2024-11-25 01:25:14', '2024-11-25 21:39:47', NULL),
(24, 'MENUKV2024112508', 'Mandu chiên', 'mandu-chien', 'Giòn bên ngoài, ngọt ngào bên trong', 80000.00, 'uploads/menu/1732595995_khai_vi8.jpg', 'KV', 1, 24, '2024-11-25 01:26:48', '2024-11-25 21:39:55', NULL),
(25, 'MENUKV2024112509', 'Xúc xích xông khói', 'xuc-xich-xong-khoi', 'Thơm ngon bùng vị', 100000.00, 'uploads/menu/1732596012_khai_vi9.jpg', 'KV', 1, 25, '2024-11-25 01:35:05', '2024-11-25 21:40:12', NULL),
(26, 'MENUKV2024112510', 'Hamburger', 'hamburger', 'Ẩm thực mê ly', 100000.00, 'uploads/menu/1732596021_khai_vi10.jpg', 'KV', 1, 26, '2024-11-25 01:36:51', '2024-11-25 21:40:21', NULL),
(27, 'MENUKV2024112511', 'Bánh cuộn', 'banh-cuon', 'Cuộn tròn với hương vị mới', 100000.00, 'uploads/menu/1732596031_khai_vi11.jpg', 'KV', 1, 27, '2024-11-25 01:38:31', '2024-11-25 21:40:31', NULL),
(28, 'MENUTB20241125', 'Beefsteak bò Waygu', 'beefsteak-bo-waygu', 'Hấp dẫn từ  lần đầu tiên', 500000.00, 'uploads/menu/1732596041_meat.jpg', 'TB', 1, 28, '2024-11-25 01:43:48', '2024-11-25 21:40:41', NULL),
(29, 'MENUTB2024112502', 'Gà nướng rau củ', 'ga-nuong-rau-cu', 'Món gà độc lạ', 300000.00, 'uploads/menu/1732596052_meat2.jpg', 'TB', 1, 29, '2024-11-25 02:05:16', '2024-11-25 21:40:52', NULL),
(30, 'MENUTB2024112503', 'Gà sốt', 'ga-sot', 'Gà đậm vị sốt, ăn vào miễn chê', 150000.00, 'uploads/menu/1732596061_meat3.jpg', 'TB', 1, 30, '2024-11-25 02:06:17', '2024-11-25 21:41:01', NULL),
(31, 'MENUTB2024112504', 'Gà phô mai', 'ga-pho-mai', 'Gà đậm vị kèm với phô mai béo tạo nên món ăn ngon', 200000.00, 'uploads/menu/1732596087_meat4.jpg', 'TB', 1, 31, '2024-11-25 02:07:30', '2024-11-25 21:41:27', NULL),
(32, 'MENUTB2024112505', 'Xiên bò nướng Hàn Quốc', 'xien-bo-nuong-han-quoc', 'Sốt ướp từ Hàn Quốc mang đến hương vị đặc sắc cho món ăn', 150000.00, 'uploads/menu/1732596096_meat5.jpg', 'TB', 1, 32, '2024-11-25 02:08:52', '2024-11-25 21:41:36', NULL),
(33, 'MENUTB2024112506', 'Sườn bò nướng', 'suon-bo-nuong', 'Thưởng thức món ăn đặc sắc, nguyên bản từ những que sườn thơm ngon tẩm vị', 500000.00, 'uploads/menu/1732596104_meat6.jpg', 'TB', 1, 33, '2024-11-25 02:11:08', '2024-11-25 21:41:44', NULL),
(34, 'MENUTB2024112507', 'Beefsteak bò Kobe', 'beefsteak-bo-kobe', 'Thịt thượng hạng, mang đến sự mềm mại và hấp dẫn', 500000.00, 'uploads/menu/1732596130_meat7.jpg', 'TB', 1, 34, '2024-11-25 02:12:27', '2024-11-25 21:42:10', NULL),
(35, 'MENUTB2024112508', 'Steak phô mai', 'steak-pho-mai', 'Thịt bò tẩm vị vừa phải hòa huyện với sốt phô mai đặc trưng tạo ra món ăn ngon', 250000.00, 'uploads/menu/1732596139_meat8.jpg', 'TB', 1, 35, '2024-11-25 02:13:50', '2024-11-25 21:42:19', NULL),
(36, 'MENUMN20241125', 'Canh rau củ', 'canh-rau-cu', 'Món canh thanh mát, giải nhiệt cơ thể', 150000.00, 'uploads/menu/1732596152_soup.jpg', 'MN', 1, 36, '2024-11-25 02:14:36', '2024-11-25 21:42:32', NULL),
(37, 'MENUMN2024112502', 'Mì Ramen', 'mi-ramen', 'Món mì đặc trưng từ Nhật Bản, hương vị thanh tao', 150000.00, 'uploads/menu/1732596161_soup_2.jpg', 'MN', 1, 37, '2024-11-25 02:15:38', '2024-11-25 21:42:41', NULL),
(38, 'MENUMN2024112503', 'Bún bò', 'bun-bo', 'Hấp dẫn đến giọt cuối cùng', 100000.00, 'uploads/menu/1732596174_soup_3.jpg', 'MN', 1, 38, '2024-11-25 02:18:38', '2024-11-25 21:42:54', NULL),
(39, 'MENUMN2024112504', 'Bún bò Huế', 'bun-bo-hue', 'Một tô bún làm nên thương hiệu', 100000.00, 'uploads/menu/1732596183_soup_4.jpg', 'MN', 1, 39, '2024-11-25 02:19:31', '2024-11-25 21:43:03', NULL),
(40, 'MENUMN2024112505', 'Sủi Cảo', 'sui-cao', 'Thịt mền ăn kèm vỏ bánh, nước súp ngọt diệu', 100000.00, 'uploads/menu/1732596193_soup_5.jpg', 'MN', 1, 40, '2024-11-25 02:22:57', '2024-11-25 21:43:13', NULL),
(41, 'MENUMN2024112506', 'Phở', 'pho', 'Món ăn đặc trưng, quen thuộc với người Việt', 100000.00, 'uploads/menu/1732596206_soup_6.jpg', 'MN', 1, 41, '2024-11-25 02:23:42', '2024-11-25 21:43:26', NULL),
(42, 'MENUMN2024112507', 'Mì bò hầm', 'mi-bo-ham', 'Mì ăn kèm với bò hầm mềm mại, nước súp bò hầm đậm đà bùng vị', 150000.00, 'uploads/menu/1732596215_soup_7.jpg', 'MN', 1, 42, '2024-11-25 02:24:59', '2024-11-25 21:43:35', NULL),
(43, 'MENUMN2024112508', 'Mì xá xíu', 'mi-xa-xiu', 'Mì tươi ăn cùng thịt xá xíu thơm ngon', 100000.00, 'uploads/menu/1732596223_soup_8.jpg', 'MN', 1, 43, '2024-11-25 02:25:52', '2024-11-25 21:43:43', NULL),
(44, 'MENUMN20241126', 'Mì cắt', 'mi-cat', 'Mì cắt Trung Quốc', 350000.00, 'uploads/menu/1732596610_cook.jpg', 'MN', 0, 44, '2024-11-25 21:50:10', '2024-11-25 21:50:10', NULL);

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
(18, '0001_01_01_000000_create_users_table', 1),
(19, '0001_01_01_000001_create_cache_table', 1),
(20, '2024_11_20_173945_alter_user_table', 1),
(21, '2024_11_21_145706_alter_users_table', 1),
(32, '2024_11_24_162806_tao_bang_categories', 2),
(33, '2024_11_24_162936_tao_bang_menu', 3),
(34, '2024_11_26_061154_add_social_login_fields_to_users_table', 4),
(35, '2024_11_26_133649_modify_password_reset_tokens_table', 5),
(36, '2024_11_29_082542_create_orders_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `email`, `token`, `created_at`) VALUES
(4, 'nhankun2303@gmail.com', 'h5Vrswm12WYh0Y8bOYes0cGyo7WsdkT67sZcG0JQQjuHifPTnTAN50h9XWjJyUIi', '2024-11-26 06:50:41');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` int(11) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone_number`, `provider`, `provider_id`) VALUES
(1, 'Trần Trọng Nhân', 'adminttn@gmail.com', 1, NULL, '$2y$12$RF8XoalOpHaghBZvFrd3du7u/CFtUynTK3M1lWcuU/Lsn1CfBK1eu', NULL, '2024-11-23 23:33:20', '2024-11-23 23:34:30', NULL, NULL, NULL),
(2, 'Lê Quang Nhân', 'adminlqn@gmail.com', 1, NULL, '$2y$12$0I2h7rOw6Kwzs.P.JoXuyuJqjJvhp/gL88N0UTumaQqkDtuk5G5ku', NULL, '2024-11-23 23:36:02', '2024-11-23 23:36:02', NULL, NULL, NULL),
(3, 'Khách 1', 'khach1@gmail.com', 0, NULL, '$2y$12$UGgERykpudvWF.Vuvj.AKOTvm7v.cp2Wf.v8ixRVxAwVO/SJ4xUUq', NULL, '2024-11-23 23:37:54', '2024-11-23 23:37:54', NULL, NULL, NULL),
(4, 'Nhan', 'lequangnhanbrvt58@gmail.com', 1, NULL, '$2y$12$CgApggLdVFXS.DjDKn5/b.qQ4YAHEiSzEDS.L/XvfvJtVe7xK0Yz.', NULL, '2024-11-26 06:29:45', '2024-11-26 06:46:48', NULL, NULL, NULL),
(5, 'nhant', 'nhankun2303@gmail.com', 1, NULL, '$2y$12$dF4x8y/c2Ho9JYd72ni74eXl8XD8h6LIlUJAojGNb.4uRmLUNyTtS', NULL, '2024-11-26 06:50:19', '2024-11-26 07:05:05', '0334554423', NULL, NULL),
(10, 'Khang đẹp trai', 'test1@gmail.com', 0, NULL, '$2y$12$47e1/Ui.Vm.Yx6X0egZH2OVIeJVyBSkm7EqXjQq59n8ywDi7nWhr.', NULL, '2024-11-29 01:59:08', '2024-11-29 01:59:08', NULL, NULL, NULL),
(11, 'Khang đẹp gái', 'adkhang@gmail.com', 1, NULL, '$2y$12$8ajtKNJDThwUe.81QmBgpusmKNcZH/mC1tvg0LbLbO9z/l9VR8lUC', NULL, '2024-11-29 02:00:11', '2024-11-29 02:00:11', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_category_code_unique` (`category_code`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu_menu_code_unique` (`menu_code`),
  ADD UNIQUE KEY `menu_slug_unique` (`slug`),
  ADD KEY `menu_category_code_foreign` (`category_code`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_reset_tokens_email_index` (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_category_code_foreign` FOREIGN KEY (`category_code`) REFERENCES `categories` (`category_code`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
