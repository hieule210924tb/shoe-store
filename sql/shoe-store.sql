-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 24, 2026 lúc 09:39 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shoe-store`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `shoe_size` tinyint(3) UNSIGNED NOT NULL DEFAULT 40,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `shoe_size`, `quantity`, `created_at`, `updated_at`) VALUES
(8, 4, 1, 40, 1, '2026-04-08 14:40:34', '2026-04-08 14:40:34'),
(9, 1, 23, 40, 1, '2026-04-11 16:14:34', '2026-04-11 16:14:34'),
(23, 5, 21, 40, 1, '2026-04-24 08:56:04', '2026-04-24 08:56:04'),
(25, 5, 19, 40, 1, '2026-04-24 08:57:19', '2026-04-24 08:57:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Sneakers', '2026-04-03 08:26:15'),
(2, 'Sandals', '2026-04-03 08:26:15'),
(3, 'Boots', '2026-04-03 08:26:15'),
(5, 'Chạy bộ & gym', '2026-04-11 16:01:13'),
(6, 'Công sở', '2026-04-11 16:01:13'),
(7, 'Streetwear', '2026-04-11 16:01:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `momo_transactions`
--

CREATE TABLE `momo_transactions` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `request_id` varchar(80) NOT NULL,
  `momo_order_id` varchar(80) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('initiated','paid','failed') NOT NULL DEFAULT 'initiated',
  `pay_url` varchar(500) DEFAULT NULL,
  `deeplink` varchar(500) DEFAULT NULL,
  `qr_code_url` varchar(500) DEFAULT NULL,
  `trans_id` varchar(80) DEFAULT NULL,
  `pay_type` varchar(50) DEFAULT NULL,
  `last_result_code` int(11) DEFAULT NULL,
  `raw_create_response` longtext DEFAULT NULL,
  `raw_return_payload` longtext DEFAULT NULL,
  `raw_ipn_payload` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `momo_transactions`
--

INSERT INTO `momo_transactions` (`id`, `order_id`, `request_id`, `momo_order_id`, `amount`, `status`, `pay_url`, `deeplink`, `qr_code_url`, `trans_id`, `pay_type`, `last_result_code`, `raw_create_response`, `raw_return_payload`, `raw_ipn_payload`, `created_at`, `updated_at`) VALUES
(1, 10, 'MOMO_REQ_10_1776156984', 'ORDER_10_1776156984', 3190000.00, 'failed', 'https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMF8xNzc2MTU2OTg0&s=1fff141610bab23de3133fb0ec55c65637a39600299c4b2f52c1c9f0cd3ac085', 'momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMF8xNzc2MTU2OTg0&v=3.0', 'momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMF8xNzc2MTU2OTg0&v=3.0&sr=0&sig=G0U6OdbsgBX7eDe', '1776157103005', '', 1006, '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_10_1776156984\",\"requestId\":\"MOMO_REQ_10_1776156984\",\"amount\":3190000,\"responseTime\":1776156984322,\"message\":\"Thành công.\",\"resultCode\":0,\"payUrl\":\"https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMF8xNzc2MTU2OTg0&s=1fff141610bab23de3133fb0ec55c65637a39600299c4b2f52c1c9f0cd3ac085\",\"deeplink\":\"momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMF8xNzc2MTU2OTg0&v=3.0\",\"qrCodeUrl\":\"momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMF8xNzc2MTU2OTg0&v=3.0&sr=0&sig=G0U6OdbsgBX7eDe\"}', '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_10_1776156984\",\"requestId\":\"MOMO_REQ_10_1776156984\",\"amount\":\"3190000\",\"orderInfo\":\"Thanh toan don hang #10\",\"orderType\":\"momo_wallet\",\"transId\":\"1776157103005\",\"resultCode\":\"1006\",\"message\":\"Giao dịch bị từ chối bởi người dùng.\",\"payType\":\"\",\"responseTime\":\"1776157103010\",\"extraData\":\"eyJvcmRlcl9pZCI6MTAsInVzZXJfaWQiOjV9\",\"signature\":\"bd689ca513d50ff61f1225271894c1b1be95fac6a650e6744f938f24123e5e56\"}', NULL, '2026-04-14 08:56:24', '2026-04-14 08:58:26'),
(2, 11, 'MOMO_REQ_11_1776157399', 'ORDER_11_1776157399', 1390000.00, 'initiated', 'https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMV8xNzc2MTU3Mzk5&s=fccc3fc501368c38179eda8abe5f0ab319057a660366fa8a4d17dd27cce57d44', 'momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMV8xNzc2MTU3Mzk5&v=3.0', 'momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMV8xNzc2MTU3Mzk5&v=3.0&sr=0&sig=FPZBvmyCD7ArWJ0', NULL, NULL, 0, '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_11_1776157399\",\"requestId\":\"MOMO_REQ_11_1776157399\",\"amount\":1390000,\"responseTime\":1776157399239,\"message\":\"Thành công.\",\"resultCode\":0,\"payUrl\":\"https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMV8xNzc2MTU3Mzk5&s=fccc3fc501368c38179eda8abe5f0ab319057a660366fa8a4d17dd27cce57d44\",\"deeplink\":\"momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMV8xNzc2MTU3Mzk5&v=3.0\",\"qrCodeUrl\":\"momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMV8xNzc2MTU3Mzk5&v=3.0&sr=0&sig=FPZBvmyCD7ArWJ0\"}', NULL, NULL, '2026-04-14 09:03:19', '2026-04-14 09:03:19'),
(3, 12, 'MOMO_REQ_12_1776157859', 'ORDER_12_1776157859', 1390000.00, 'failed', 'https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMl8xNzc2MTU3ODU5&s=4b0363a61eb054daff86963dbcec0696704fc8a3dddbaffde4b955cecec9f1dd', 'momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMl8xNzc2MTU3ODU5&v=3.0', 'momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMl8xNzc2MTU3ODU5&v=3.0&sr=0&sig=f0HIgG9u1sxeRyb', '1776157867005', '', 1006, '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_12_1776157859\",\"requestId\":\"MOMO_REQ_12_1776157859\",\"amount\":1390000,\"responseTime\":1776157859493,\"message\":\"Thành công.\",\"resultCode\":0,\"payUrl\":\"https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMl8xNzc2MTU3ODU5&s=4b0363a61eb054daff86963dbcec0696704fc8a3dddbaffde4b955cecec9f1dd\",\"deeplink\":\"momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMl8xNzc2MTU3ODU5&v=3.0\",\"qrCodeUrl\":\"momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xMl8xNzc2MTU3ODU5&v=3.0&sr=0&sig=f0HIgG9u1sxeRyb\"}', '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_12_1776157859\",\"requestId\":\"MOMO_REQ_12_1776157859\",\"amount\":\"1390000\",\"orderInfo\":\"Thanh toan don hang #12\",\"orderType\":\"momo_wallet\",\"transId\":\"1776157867005\",\"resultCode\":\"1006\",\"message\":\"Giao dịch bị từ chối bởi người dùng.\",\"payType\":\"\",\"responseTime\":\"1776157867008\",\"extraData\":\"eyJvcmRlcl9pZCI6MTIsInVzZXJfaWQiOjV9\",\"signature\":\"f4378f553a21315e31ba821a9e41de59467e3a1203a8909c01b51ea328c2e38c\"}', NULL, '2026-04-14 09:10:59', '2026-04-14 09:11:10'),
(4, 13, 'MOMO_REQ_13_1776158139', 'ORDER_13_1776158139', 1790000.00, 'failed', 'https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xM18xNzc2MTU4MTM5&s=bfde33edb7d74a7194100cfc5ead26a4f407b45e7fc8c29792d3844a8570273a', 'momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xM18xNzc2MTU4MTM5&v=3.0', 'momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xM18xNzc2MTU4MTM5&v=3.0&sr=0&sig=jGYqEhNsYYGAbt3', '4723839979', 'qr', 0, '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_13_1776158139\",\"requestId\":\"MOMO_REQ_13_1776158139\",\"amount\":1790000,\"responseTime\":1776158139646,\"message\":\"Thành công.\",\"resultCode\":0,\"payUrl\":\"https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xM18xNzc2MTU4MTM5&s=bfde33edb7d74a7194100cfc5ead26a4f407b45e7fc8c29792d3844a8570273a\",\"deeplink\":\"momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xM18xNzc2MTU4MTM5&v=3.0\",\"qrCodeUrl\":\"momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xM18xNzc2MTU4MTM5&v=3.0&sr=0&sig=jGYqEhNsYYGAbt3\"}', '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_13_1776158139\",\"requestId\":\"MOMO_REQ_13_1776158139\",\"amount\":\"1790000\",\"orderInfo\":\"Thanh toan don hang #13\",\"orderType\":\"momo_wallet\",\"transId\":\"4723839979\",\"resultCode\":\"0\",\"message\":\"Thành công.\",\"payType\":\"qr\",\"responseTime\":\"1776158202529\",\"extraData\":\"eyJvcmRlcl9pZCI6MTMsInVzZXJfaWQiOjJ9\",\"signature\":\"32503218560b4c30dafbb80cf4f3c5c82e8d429dea94d9054f318c21881aff58\"}', NULL, '2026-04-14 09:15:39', '2026-04-14 09:16:45'),
(5, 14, 'MOMO_REQ_14_1776158404', 'ORDER_14_1776158404', 450000.00, 'paid', 'https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xNF8xNzc2MTU4NDA0&s=f6c6963340e60549342c759df476f1a264d17a0e7c7d0217b3eab546b11b1f61', 'momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xNF8xNzc2MTU4NDA0&v=3.0', 'momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xNF8xNzc2MTU4NDA0&v=3.0&sr=0&sig=PtTfLbKCUH5Kupp', '4723800225', 'qr', 0, '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_14_1776158404\",\"requestId\":\"MOMO_REQ_14_1776158404\",\"amount\":450000,\"responseTime\":1776158404465,\"message\":\"Thành công.\",\"resultCode\":0,\"payUrl\":\"https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xNF8xNzc2MTU4NDA0&s=f6c6963340e60549342c759df476f1a264d17a0e7c7d0217b3eab546b11b1f61\",\"deeplink\":\"momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xNF8xNzc2MTU4NDA0&v=3.0\",\"qrCodeUrl\":\"momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8xNF8xNzc2MTU4NDA0&v=3.0&sr=0&sig=PtTfLbKCUH5Kupp\"}', '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_14_1776158404\",\"requestId\":\"MOMO_REQ_14_1776158404\",\"amount\":\"450000\",\"orderInfo\":\"Thanh toan don hang #14\",\"orderType\":\"momo_wallet\",\"transId\":\"4723800225\",\"resultCode\":\"0\",\"message\":\"Thành công.\",\"payType\":\"qr\",\"responseTime\":\"1776158457719\",\"extraData\":\"eyJvcmRlcl9pZCI6MTQsInVzZXJfaWQiOjJ9\",\"signature\":\"35b64618bd1cbc5e316216efbbd9189638609254003959d5b3c04b3aea03e327\"}', NULL, '2026-04-14 09:20:04', '2026-04-14 09:21:01'),
(6, 22, 'MOMO_REQ_22_1776343074', 'ORDER_22_1776343074', 2690000.00, 'paid', 'https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yMl8xNzc2MzQzMDc0&s=641839e4fb33dbb76ae1f05fad1639404fc0471d0b5989fd370c98f83a0834ec', 'momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yMl8xNzc2MzQzMDc0&v=3.0', 'momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yMl8xNzc2MzQzMDc0&v=3.0&sr=0&sig=D8FRwcLp9EMJfBC', '4724283763', 'qr', 0, '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_22_1776343074\",\"requestId\":\"MOMO_REQ_22_1776343074\",\"amount\":2690000,\"responseTime\":1776343075397,\"message\":\"Thành công.\",\"resultCode\":0,\"payUrl\":\"https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yMl8xNzc2MzQzMDc0&s=641839e4fb33dbb76ae1f05fad1639404fc0471d0b5989fd370c98f83a0834ec\",\"deeplink\":\"momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yMl8xNzc2MzQzMDc0&v=3.0\",\"qrCodeUrl\":\"momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yMl8xNzc2MzQzMDc0&v=3.0&sr=0&sig=D8FRwcLp9EMJfBC\"}', '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_22_1776343074\",\"requestId\":\"MOMO_REQ_22_1776343074\",\"amount\":\"2690000\",\"orderInfo\":\"Thanh toan don hang #22\",\"orderType\":\"momo_wallet\",\"transId\":\"4724283763\",\"resultCode\":\"0\",\"message\":\"Thành công.\",\"payType\":\"qr\",\"responseTime\":\"1776343113172\",\"extraData\":\"eyJvcmRlcl9pZCI6MjIsInVzZXJfaWQiOjV9\",\"signature\":\"4af2cf5c29e81d6bed722d32511f8cccd364d0c9634e6252d321a4dc55913664\"}', NULL, '2026-04-16 12:37:54', '2026-04-16 12:38:36'),
(7, 27, 'MOMO_REQ_27_1777017591', 'ORDER_27_1777017591', 7570000.00, 'failed', 'https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yN18xNzc3MDE3NTkx&s=012de646a013456de8340a666a4764d6ae84936260eda25e1d227815c4082247', 'momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yN18xNzc3MDE3NTkx&v=3.0', 'momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yN18xNzc3MDE3NTkx&v=3.0&sr=0&sig=GT1SqdsHjCvDeSc', '1777017602131', '', 1006, '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_27_1777017591\",\"requestId\":\"MOMO_REQ_27_1777017591\",\"amount\":7570000,\"responseTime\":1777017591736,\"message\":\"Thành công.\",\"resultCode\":0,\"payUrl\":\"https://test-payment.momo.vn/v2/gateway/pay?t=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yN18xNzc3MDE3NTkx&s=012de646a013456de8340a666a4764d6ae84936260eda25e1d227815c4082247\",\"deeplink\":\"momo://app?action=payWithApp&isScanQR=false&scanQR=false&serviceType=app&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yN18xNzc3MDE3NTkx&v=3.0\",\"qrCodeUrl\":\"momo://app?action=payWithApp&isScanQR=true&scanQR=true&serviceType=qr&sid=TU9NT05QTUIyMDIxMDYyOXxPUkRFUl8yN18xNzc3MDE3NTkx&v=3.0&sr=0&sig=GT1SqdsHjCvDeSc\"}', '{\"partnerCode\":\"MOMONPMB20210629\",\"orderId\":\"ORDER_27_1777017591\",\"requestId\":\"MOMO_REQ_27_1777017591\",\"amount\":\"7570000\",\"orderInfo\":\"Thanh toan don hang #27\",\"orderType\":\"momo_wallet\",\"transId\":\"1777017602131\",\"resultCode\":\"1006\",\"message\":\"Giao dịch bị từ chối bởi người dùng.\",\"payType\":\"\",\"responseTime\":\"1777017605303\",\"extraData\":\"eyJvcmRlcl9pZCI6MjcsInVzZXJfaWQiOjV9\",\"signature\":\"323f992cd2e7c2567c4f7ea89c81315cd93209254587fce40b8bc14fc05a7fd5\"}', NULL, '2026-04-24 07:59:52', '2026-04-24 08:00:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `buyer_phone` varchar(20) DEFAULT NULL,
  `addr_house` varchar(120) DEFAULT NULL,
  `addr_hamlet` varchar(120) DEFAULT NULL,
  `addr_commune` varchar(120) DEFAULT NULL,
  `addr_province` varchar(120) DEFAULT NULL,
  `payment_method` enum('momo','vnpay','cod') NOT NULL DEFAULT 'cod',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('paid','cancelled','pending') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `buyer_phone`, `addr_house`, `addr_hamlet`, `addr_commune`, `addr_province`, `payment_method`, `total_amount`, `status`, `created_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, 'cod', 1290000.00, 'paid', '2026-04-03 08:31:40'),
(2, 1, NULL, NULL, NULL, NULL, NULL, 'cod', 7950000.00, 'paid', '2026-04-03 08:53:35'),
(3, 4, NULL, NULL, NULL, NULL, NULL, 'cod', 1290000.00, 'paid', '2026-04-03 09:33:01'),
(4, 3, NULL, NULL, NULL, NULL, NULL, 'cod', 2450000.00, 'paid', '2026-04-01 03:30:00'),
(5, 3, NULL, NULL, NULL, NULL, NULL, 'cod', 3590000.00, 'paid', '2026-04-02 08:05:00'),
(6, 3, NULL, NULL, NULL, NULL, NULL, 'cod', 5180000.00, 'pending', '2026-04-08 02:15:00'),
(7, 3, NULL, NULL, NULL, NULL, NULL, 'cod', 1890000.00, 'paid', '2026-04-09 11:40:00'),
(8, 3, NULL, NULL, NULL, NULL, NULL, 'cod', 4080000.00, 'cancelled', '2026-04-05 04:00:00'),
(9, 5, NULL, NULL, NULL, NULL, NULL, 'cod', 6380000.00, 'paid', '2026-04-14 03:21:53'),
(10, 5, NULL, NULL, NULL, NULL, NULL, 'cod', 3190000.00, 'pending', '2026-04-14 08:56:24'),
(11, 5, NULL, NULL, NULL, NULL, NULL, 'cod', 1390000.00, 'pending', '2026-04-14 09:03:19'),
(12, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'cod', 1390000.00, 'pending', '2026-04-14 09:10:59'),
(13, 2, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'cod', 1790000.00, 'pending', '2026-04-14 09:15:39'),
(14, 2, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'cod', 450000.00, 'paid', '2026-04-14 09:20:04'),
(15, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'vnpay', 2790000.00, 'pending', '2026-04-14 09:36:10'),
(16, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'vnpay', 2790000.00, 'pending', '2026-04-14 09:37:12'),
(17, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'vnpay', 2790000.00, 'pending', '2026-04-14 09:37:54'),
(18, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'vnpay', 1190000.00, 'pending', '2026-04-14 09:38:27'),
(19, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'vnpay', 1190000.00, 'pending', '2026-04-14 09:40:02'),
(20, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'vnpay', 1190000.00, 'cancelled', '2026-04-14 09:41:32'),
(21, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'vnpay', 1190000.00, 'paid', '2026-04-14 09:42:12'),
(22, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'momo', 2690000.00, 'paid', '2026-04-16 12:37:54'),
(23, 13, '0372953009', '15a', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'vnpay', 3190000.00, 'paid', '2026-04-16 13:08:23'),
(24, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'vnpay', 450000.00, 'pending', '2026-04-16 13:56:54'),
(25, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'vnpay', 450000.00, 'paid', '2026-04-16 13:57:21'),
(26, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'vnpay', 9460000.00, 'cancelled', '2026-04-24 03:20:04'),
(27, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'momo', 7570000.00, 'cancelled', '2026-04-24 07:59:51'),
(28, 5, '0372953009', '12', 'Tân Minh', 'Đông Thái Ninh', 'Hưng Yên', 'cod', 7570000.00, 'pending', '2026-04-24 08:22:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `shoe_size` tinyint(3) UNSIGNED NOT NULL DEFAULT 40,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `shoe_size`, `quantity`, `unit_price`, `created_at`) VALUES
(1, 1, 1, 40, 1, 1290000.00, '2026-04-03 08:31:40'),
(2, 2, 1, 40, 2, 1290000.00, '2026-04-03 08:53:35'),
(3, 2, 2, 40, 3, 1790000.00, '2026-04-03 08:53:35'),
(4, 3, 1, 40, 1, 1290000.00, '2026-04-03 09:33:01'),
(5, 4, 1, 40, 1, 1290000.00, '2026-04-11 16:10:03'),
(6, 4, 4, 40, 2, 590000.00, '2026-04-11 16:10:03'),
(7, 5, 8, 40, 1, 1490000.00, '2026-04-11 16:10:03'),
(8, 5, 2, 40, 1, 1790000.00, '2026-04-11 16:10:03'),
(9, 5, 5, 40, 1, 790000.00, '2026-04-11 16:10:03'),
(10, 9, 23, 42, 2, 3190000.00, '2026-04-14 03:21:53'),
(11, 10, 23, 40, 1, 3190000.00, '2026-04-14 08:56:24'),
(12, 11, 19, 40, 1, 1390000.00, '2026-04-14 09:03:19'),
(13, 12, 19, 40, 1, 1390000.00, '2026-04-14 09:10:59'),
(14, 13, 2, 40, 1, 1790000.00, '2026-04-14 09:15:39'),
(15, 14, 21, 40, 1, 450000.00, '2026-04-14 09:20:04'),
(16, 15, 17, 40, 1, 2790000.00, '2026-04-14 09:36:10'),
(17, 16, 17, 40, 1, 2790000.00, '2026-04-14 09:37:12'),
(18, 17, 17, 40, 1, 2790000.00, '2026-04-14 09:37:54'),
(19, 18, 22, 40, 1, 1190000.00, '2026-04-14 09:38:27'),
(20, 19, 22, 40, 1, 1190000.00, '2026-04-14 09:40:02'),
(21, 20, 22, 40, 1, 1190000.00, '2026-04-14 09:41:32'),
(22, 21, 22, 40, 1, 1190000.00, '2026-04-14 09:42:12'),
(23, 22, 14, 40, 1, 2690000.00, '2026-04-16 12:37:54'),
(24, 23, 23, 40, 1, 3190000.00, '2026-04-16 13:08:23'),
(25, 24, 21, 40, 1, 450000.00, '2026-04-16 13:56:54'),
(26, 25, 21, 40, 1, 450000.00, '2026-04-16 13:57:21'),
(27, 26, 17, 40, 2, 2790000.00, '2026-04-24 03:20:04'),
(28, 26, 20, 40, 1, 1890000.00, '2026-04-24 03:20:04'),
(29, 26, 18, 40, 1, 1990000.00, '2026-04-24 03:20:04'),
(30, 27, 17, 40, 2, 2790000.00, '2026-04-24 07:59:51'),
(31, 27, 18, 40, 1, 1990000.00, '2026-04-24 07:59:51'),
(32, 28, 17, 40, 2, 2790000.00, '2026-04-24 08:22:30'),
(33, 28, 18, 40, 1, 1990000.00, '2026-04-24 08:22:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token_hash`, `expires_at`, `used_at`, `created_at`) VALUES
(5, 4, 'f066a4738f54f1fcdbd5f39c81749c9bc30056daa83d4a266c9d3b6d5dbed58c', '2026-04-08 22:09:39', '2026-04-08 21:40:19', '2026-04-08 14:39:39'),
(7, 5, 'a283db955b242e199a1406db308222592e4a5a3ca21f0ed23c12f1b763222b32', '2026-04-08 22:57:40', '2026-04-08 22:28:20', '2026-04-08 15:27:40'),
(8, 13, '910f41619753ecd7449d7349218f20ae666eea0cb0bfa65a4f760a72346814eb', '2026-04-16 20:41:36', '2026-04-16 20:12:14', '2026-04-16 13:11:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_qty` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `image_path`, `price`, `stock_qty`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sneakers Classic', 'Đôi sneaker classic hướng tới phong cách tối giản nhưng vẫn đủ nổi bật để khoác lên mọi outfit trong tuần. Phần upper kết hợp canvas thoáng khí với các điểm nhấn da tổng hợp cao cấp, dễ lau chùi và bền màu sau nhiều lần giặt nhẹ. Đế giữa làm từ EVA đúc một khối giúp giảm trọng lượng tổng thể và hấp thụ lực khi bạn di chuyển liên tục trên hè phố hay trong khuôn viên trường học. Đế ngoài cao su non với họa tiết rãnh chống trơn, bám tốt trên gạch bóng lẫn nhựa đường. Form giày ôm vừa phải ở gót và thả nhẹ ở ngón; khuyên bạn giữ nguyên size thường mang. Phù hợp nhân viên văn phòng, sinh viên hay người thích phong cách clean, dễ phối với quần jean slim, chinos hoặc quần short ngắn vào cuối tuần.', NULL, 1290000.00, 21, '2026-04-03 08:30:04', '2026-04-11 16:01:13'),
(2, 1, 'Sneakers Pro', 'Sneakers Pro được thiết kế cho nhịp sống năng động: từ buổi tập nhẹ tại phòng gym đến chuyến đi bộ dài trong công viên. Lớp lưới kỹ thuật ở thân giày tăng luồng gió, hạn chế cảm giác bí khi mang trong thời gian dài. Hệ thống dây buộc đa điểm giúp cổ chân được cố định ổn định, giảm trượt gót khi chạy nước rút hoặc đổi hướng đột ngột. Đệm giữa bán cứng vừa phải: không quá mềm để mất lực đẩy, cũng không quá cứng gây mỏi cẳng. Đế ngoài cao su carbon hóa tăng độ bền ma sát ở vùng tiếp xúc nhiều. Form hơi rộng nửa size ở phần mũi cho ngón chân thoải mái; nếu bàn chân hẹp có thể chọn dây buộc chặt hơn một nấc. Đây là lựa chọn an toàn cho người mới tập cardio hoặc cần một đôi sneaker đa năng cho cả tuần.', NULL, 1790000.00, 15, '2026-04-03 08:30:04', '2026-04-11 16:01:13'),
(3, 1, 'Sneakers White', 'Tông màu trắng ngà tinh tế, dễ phối với quần tối hoặc sáng màu mà không tạo cảm giác lệch tông. Bề mặt synthetic mịn, hạn chế bám bụi đường và dễ lau bằng khăn ẩm sau mỗi lần đi mưa nhẹ. Đế trắng đồng bộ với upper tạo một khối liền mạch, phù hợp phong cách minimalist và streetwear Hàn. Form gọn, không quá cồng kềnh nên nhìn chân thon và cân đối khi chụp ảnh OOTD. Lớp đệm mỏng vừa đủ cho đi bộ hàng ngày; nếu bạn cần đứng cả ngày nên cân nhắc thêm lót giày orthotic. Sneakers White là món đầu tư hợp lý khi bạn muốn một đôi giày trung tính, lên hình đẹp và không lỗi thời theo mùa.', NULL, 990000.00, 30, '2026-04-03 08:30:04', '2026-04-11 16:01:13'),
(4, 2, 'Sandals Comfort', 'Sandals Comfort tập trung vào cảm giác đi êm và thư giãn cho bàn chân sau giờ làm việc hay những ngày hè oi bức. Quai đeo điều chỉnh được bằng khóa dán Velcro, dễ thao tác khi tay đang cầm đồ hoặc khi vội ra cửa. Đế PU dày vừa phải với rãnh chống trơn, phân tán áp lực ở gót và lòng bàn chân khi đi trên gạch ẩm. Phần tiếp xúc da chân được làm mềm, hạn chế phồng rộp khi mang sockless. Trọng lượng nhẹ giúp bỏ vào balo du lịch mà không chiếm nhiều diện tích. Phù hợp đi biển ngắn ngày, dạo phố buổi tối hoặc mang trong nhà thay dép cứng. Không khuyến khích chạy bộ cường độ cao vì thiết kế hướng tới thư giãn chứ không phải giày thể thao.', NULL, 590000.00, 40, '2026-04-03 08:30:04', '2026-04-11 16:01:13'),
(5, 2, 'Sandals Sport', 'Sandals Sport mang đến sự cân bằng giữa thoáng mát của sandal và độ ổn định gần giống giày sneaker. Quai dệt chắc chắn ôm sát mu bàn chân, hạn chế trượt khi bạn vận động nhanh hoặc leo bậc thang ngoài trời. Đế cao su đúc với pattern gai sâu, bám tốt trên sân pickleball, đường nhựa hoặc lối đi công viên có sỏi nhỏ. Phần đệm gót dày hơn một chút so với sandal thường để giảm sóc khi bước dài. Thoát nước nhanh sau khi dội qua vũng nông, phù hợp du lịch miền nhiệt đới. Bạn có thể mang kèm tất cổ thấp nếu muốn phong cách thể thao hơn. Lưu ý: tránh ngâm nước biển quá lâu để bảo quản độ bền quai và khóa kim loại.', NULL, 790000.00, 22, '2026-04-03 08:30:04', '2026-04-11 16:01:13'),
(6, 3, 'Boots Winter', 'Boots Winter được may lại với phom cổ cao vừa phải, giúp che gót chân và một phần cổ chân khi bạn mặc quần dài hoặc quần ống hơi rộng. Lớp lót bên trong dùng vải nỉ mỏng giữ ấm nhẹ mà không gây bí khi vào phòng có máy sưởi. Mũi giày gia cố chống va đập nhẹ, hữu ích khi di chuyển trong bãi đỗ xe hay công trường có vật liệu rơi vãi. Đế cao su dày, rãnh sâu để tăng ma sát trên lá ướt hoặc đường phủ sương. Khóa kéo bên hông (nếu có trên phiên bản cụ thể) hoặc dây buộc truyền thống giúp bạn dễ điều chỉnh độ chặt theo tất dày mỏng. Phù hợp mùa se lạnh miền Bắc, đi làm hoặc đi chơi tối. Không phải ủng tuyết chuyên dụng nhưng đủ ấm cho nền nhiệt độ trên 10 độ C nếu kết hợp tất len.', NULL, 2190000.00, 12, '2026-04-03 08:30:04', '2026-04-11 16:01:13'),
(7, 3, 'Boots Street', 'Boots Street nhấn mạnh silhouette thô, đường chỉ nổi và màu da trầm hoặc đen bóng nhẹ tùy phiên bản, tạo điểm nhìn mạnh cho outfit streetwear và layer áo khoác dày. Thân boots làm từ da tổng hợp cao cấp, bề mặt dễ đánh bóng lại sau một thời gian sử dụng. Cổ giày có đệm bọc xốp mỏng để không cọ vào cổ chân khi bạn cử động nhiều. Đế platform vừa phải tăng chiều cao trực quan nhưng vẫn giữ trọng tâm ổn định; nên làm quen vài ngày nếu bạn chưa quen giày đế dày. Phối tốt với quần jean ống rộng, cargo hoặc váy midi và tất đen. Đây là item cá tính, phù hợp chụp lookbook, đi concert hoặc dạo khu phố đông người vào cuối tuần.', NULL, 2590000.00, 9, '2026-04-03 08:30:04', '2026-04-11 16:01:13'),
(8, 3, 'Boots Basic', 'Boots Basic là dòng an toàn cho người mới thử boots lần đầu: thiết kế cổ điển, ít chi tiết thừa, dễ phối với quần tây và quần jean slim. Chất liệu da tổng hợp mềm, cần vài buổi đi để form giày ôm theo bàn chân mà không gây phồng rộp nghiêm trọng. Đế cao su tự nhiên màu nâu hoặc đen đồng bộ với upper, may chắc ở mũi và gót. Trọng lượng trung bình, không nặng như boots công trường nên đi trong nhà ga sân bay cũng chấp nhận được. Giá tầm trung nhưng độ bền ổn nếu bạn xoay vòng 2–3 đôi giày trong tuần thay vì chỉ mang một đôi mỗi ngày. Một lựa chọn hợp lý cho sinh viên năm cuối, nhân viên mới đi làm hoặc người cần boots đa năng cho cả tuần mà không muốn quá nổi bật.', NULL, 1490000.00, 15, '2026-04-03 08:30:04', '2026-04-11 16:01:13'),
(9, 2, 'Adidas', 'ddd', 'assets/images/products/prod_69cf7b63502070.93609758.jpg', 700000.00, 3, '2026-04-03 08:33:39', '2026-04-11 16:25:22'),
(10, 5, 'Air Stream Runner 2.0', 'Mẫu chạy bộ entry-level nhưng được tinh chỉnh đế và upper...', NULL, 2190000.00, 28, '2026-04-11 16:06:30', '2026-04-11 16:06:30'),
(11, 5, 'VeloTrack Sprint Pro', 'Định hướng cho buổi interval và chạy nước rút ngắn trên track hoặc sân cỏ nhân tạo...', NULL, 2890000.00, 16, '2026-04-11 16:08:54', '2026-04-11 16:08:54'),
(12, 6, 'Mono Step Leather Oxford', 'Giày buộc dây phong cách Oxford dành cho môi trường công sở...', NULL, 2490000.00, 20, '2026-04-11 16:08:55', '2026-04-11 16:08:55'),
(13, 6, 'Heritage Penny Loafer', 'Loafer không dây, tiện khi qua cửa an ninh sân bay...', NULL, 2290000.00, 18, '2026-04-11 16:08:55', '2026-04-11 16:08:55'),
(14, 7, 'Urban Pulse Max Cushion', 'Sneaker đế dày dòng lifestyle: đệm siêu mềm...', NULL, 2690000.00, 21, '2026-04-11 16:08:55', '2026-04-16 12:38:36'),
(15, 7, 'Grit Canvas High-Top', 'High-top canvas lấy cảm hứng từ giày trượt...', NULL, 1590000.00, 35, '2026-04-11 16:08:55', '2026-04-11 16:08:55'),
(16, 2, 'Coastal Walk Pro Sandal', 'Sandal quai ngang kép điều chỉnh được...', NULL, 890000.00, 42, '2026-04-11 16:08:55', '2026-04-11 16:08:55'),
(17, 3, 'Summit Grip Winter Boot', 'Boot cổ cao hơn Winter cơ bản một chút...', NULL, 2790000.00, 9, '2026-04-11 16:08:55', '2026-04-24 08:22:30'),
(18, 5, 'Aero Mesh Light Runner', 'Giày chạy siêu nhẹ dưới 250g mỗi chiếc...', NULL, 1990000.00, 23, '2026-04-11 16:08:55', '2026-04-24 08:22:30'),
(19, 6, 'Metro Slip Knit Office', 'Slip-on dệt kim co giãn, không dây...', 'assets/images/products/prod_69da76777d3f34.43497589.webp', 1390000.00, 30, '2026-04-11 16:10:02', '2026-04-11 16:27:35'),
(20, 7, 'Neon Lane Retro Sneaker', 'Phối màu retro 90s: neon chạm nhẹ...', 'assets/images/products/prod_69e63325689f23.59128150.webp', 1890000.00, 26, '2026-04-11 16:10:02', '2026-04-20 14:07:33'),
(21, 2, 'Breeze Open Toe Comfort', 'Sandal xỏ ngón với quai mềm...', 'assets/images/products/prod_69da766766b972.94693015.jpg', 450000.00, 53, '2026-04-11 16:10:02', '2026-04-16 13:59:50'),
(22, 1, 'Courtline Low Sneaker', 'Sneaker court cổ thấp...', 'assets/images/products/prod_69e62bf48f3b46.40695656.webp', 1190000.00, 39, '2026-04-11 16:10:02', '2026-04-20 13:36:52'),
(23, 3, 'Ridge Hiker Mid Boot', 'Boot cổ giữa cho địa hình đồi nhẹ...', 'assets/images/products/prod_69da7688a688d6.11112672.webp', 3190000.00, 7, '2026-04-11 16:10:02', '2026-04-16 13:10:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 5, 18, 2, 'okke', '2026-04-16 13:53:28', '2026-04-16 13:53:28'),
(2, 5, 21, 5, 'sản phẩm quá oke, và đẹp, lần sau ủng hộ lại .', '2026-04-16 14:27:06', '2026-04-16 14:42:03'),
(3, 5, 14, 1, 'quá ok', '2026-04-16 14:27:38', '2026-04-16 14:27:38'),
(4, 5, 22, 5, 'quá oke', '2026-04-16 14:30:43', '2026-04-16 14:30:43'),
(5, 13, 23, 5, 'oke đấy, lần sau ủng hộ tiếp', '2026-04-16 14:44:26', '2026-04-16 14:44:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Lê Văn Hiếu', 'hieu123@gmail.com', '$2y$10$dQcQ.NzhIqv8HOoWEhDqcedP2TiY3jcofcdF0jiEPEnFQaNGq/sOC', 'admin', '2026-04-03 08:27:24'),
(2, 'Admin Demo', 'admin@example.com', '$2y$10$5MTYoOCfxguAkDt4XOnwCeQBGZtg7v3Pm3npeoDxdyPom/9LdcQqy', 'admin', '2026-04-03 08:30:04'),
(3, 'User Demo', 'user@example.com', '$2y$10$zUWpC7Zf7MPeeELg.cifruevjA..bE26YQcFK6UC.6AW1MxI81IfS', 'user', '2026-04-03 08:30:04'),
(4, 'hieule', 'manu210924@gmail.com', '$2y$10$BhrD5NaMN13OUQHdS4mRk.IGP2U1yEpJycZRfUl1bijGTgHY75iAK', 'user', '2026-04-03 09:32:46'),
(5, 'levanhieu', 'lehieu210924@gmail.com', '$2y$10$uiA8ykGSzLyOiiUw/F6TzeVJ/abjqmznZvyJDFbfVvyokQx1J18Ui', 'user', '2026-04-08 15:01:01'),
(13, 'Hieulevan', 'hiele210924@gmail.com', '$2y$10$usCqNMqy36vVrNRAymlpW.C9IxIVsP7fHvogRKvDDLqfXvaynuEqS', 'user', '2026-04-16 13:06:52'),
(14, 'admin123', 'admin123@gmail.com', '$2y$10$5H1VRsvK0VqNxbz.HKUzB.KeeYVtIvjbWnX/WMjR4z0/nicP04fe2', 'admin', '2026-04-20 14:47:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vnpay_transactions`
--

CREATE TABLE `vnpay_transactions` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `txn_ref` varchar(80) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('initiated','paid','failed') NOT NULL DEFAULT 'initiated',
  `payment_url` varchar(500) DEFAULT NULL,
  `bank_code` varchar(50) DEFAULT NULL,
  `bank_tran_no` varchar(100) DEFAULT NULL,
  `transaction_no` varchar(100) DEFAULT NULL,
  `response_code` varchar(10) DEFAULT NULL,
  `transaction_status` varchar(10) DEFAULT NULL,
  `raw_return_payload` longtext DEFAULT NULL,
  `raw_ipn_payload` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vnpay_transactions`
--

INSERT INTO `vnpay_transactions` (`id`, `order_id`, `txn_ref`, `amount`, `status`, `payment_url`, `bank_code`, `bank_tran_no`, `transaction_no`, `response_code`, `transaction_status`, `raw_return_payload`, `raw_ipn_payload`, `created_at`, `updated_at`) VALUES
(1, 16, 'VNPAY_16_1776159432', 2790000.00, 'initiated', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Version=2.1.0&vnp_Command=pay&vnp_TmnCode=U1Q149D8&vnp_Amount=279000000&vnp_CreateDate=20260414113712&vnp_CurrCode=VND&vnp_IpAddr=%3A%3A1&vnp_Locale=vn&vnp_OrderInfo=Thanh%20toan%20don%20hang%20%2316&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2Flocalhost%2Fphp%2Fshoe_store%2Fuser%2Fvnpay-return.php&vnp_TxnRef=VNPAY_16_1776159432&vnp_SecureHash=0b68b45175545eb7a6f8956fed45ef8e66a40409b3a175d41747bcf881636fb6a5530b9ef80be7db16553d1fa4be3701f', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 09:37:12', '2026-04-14 09:37:12'),
(2, 17, 'VNPAY_17_1776159474', 2790000.00, 'initiated', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Version=2.1.0&vnp_Command=pay&vnp_TmnCode=U1Q149D8&vnp_Amount=279000000&vnp_CreateDate=20260414113754&vnp_CurrCode=VND&vnp_IpAddr=%3A%3A1&vnp_Locale=vn&vnp_OrderInfo=Thanh%20toan%20don%20hang%20%2317&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2Flocalhost%2Fphp%2Fshoe_store%2Fuser%2Fvnpay-return.php&vnp_TxnRef=VNPAY_17_1776159474&vnp_SecureHash=98a86cd0c8d374a0b11d0db48602e734e20f72aae838e5a5cb559e84193bd102446e51d6a8f5d517b13cc5925189c790f', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 09:37:54', '2026-04-14 09:37:54'),
(3, 18, 'VNPAY_18_1776159507', 1190000.00, 'initiated', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Version=2.1.0&vnp_Command=pay&vnp_TmnCode=U1Q149D8&vnp_Amount=119000000&vnp_CreateDate=20260414113827&vnp_CurrCode=VND&vnp_IpAddr=%3A%3A1&vnp_Locale=vn&vnp_OrderInfo=Thanh%20toan%20don%20hang%20%2318&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2Flocalhost%2Fphp%2Fshoe_store%2Fuser%2Fvnpay-return.php&vnp_TxnRef=VNPAY_18_1776159507&vnp_SecureHash=8789a9d99a554c42b0575aea846429ecb23e367d2e7de7d260b537a00dbd2f7130c6bee3af9d541fb2db30a4c7afd7dfc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 09:38:27', '2026-04-14 09:38:27'),
(4, 19, 'VNPAY_19_1776159602', 1190000.00, 'initiated', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Version=2.1.0&vnp_Command=pay&vnp_TmnCode=U1Q149D8&vnp_Amount=119000000&vnp_CreateDate=20260414164002&vnp_ExpireDate=20260414165502&vnp_CurrCode=VND&vnp_IpAddr=%3A%3A1&vnp_Locale=vn&vnp_OrderInfo=Thanh%20toan%20don%20hang%20%2319&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2Flocalhost%2Fphp%2Fshoe_store%2Fuser%2Fvnpay-return.php&vnp_TxnRef=VNPAY_19_1776159602&vnp_SecureHash=8d23dbab2dbfddeff825ca59495df51407e0e648cc46eec4fdf3c573d4a682a38c4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 09:40:02', '2026-04-14 09:40:02'),
(5, 20, 'VNPAY_20_1776159692', 1190000.00, 'failed', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Version=2.1.0&vnp_Command=pay&vnp_TmnCode=U1Q149D8&vnp_Amount=119000000&vnp_CreateDate=20260414164132&vnp_ExpireDate=20260414165632&vnp_CurrCode=VND&vnp_IpAddr=%3A%3A1&vnp_Locale=vn&vnp_OrderInfo=Thanh+toan+don+hang+%2320&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2Flocalhost%2Fphp%2Fshoe_store%2Fuser%2Fvnpay-return.php&vnp_TxnRef=VNPAY_20_1776159692&vnp_SecureHashType=HmacSHA512&vnp_SecureHash=3b6871938405774c4aea3088d9438f74f1c03011b9b04', 'VNPAY', NULL, '0', '24', '02', '{\"vnp_Amount\":\"119000000\",\"vnp_BankCode\":\"VNPAY\",\"vnp_CardType\":\"QRCODE\",\"vnp_OrderInfo\":\"Thanh toan don hang #20\",\"vnp_PayDate\":\"20260414164132\",\"vnp_ResponseCode\":\"24\",\"vnp_TmnCode\":\"U1Q149D8\",\"vnp_TransactionNo\":\"0\",\"vnp_TransactionStatus\":\"02\",\"vnp_TxnRef\":\"VNPAY_20_1776159692\",\"vnp_SecureHash\":\"c90a585c548d19a047ab3a4af584e8f0e02b29f8bb9279212852bdce015bebebca3ba859eb2f23589eaf3398faf89626e861b44383d55095c047b245147a25e0\"}', NULL, '2026-04-14 09:41:32', '2026-04-14 09:41:56'),
(6, 21, 'VNPAY_21_1776159732', 1190000.00, 'paid', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Version=2.1.0&vnp_Command=pay&vnp_TmnCode=U1Q149D8&vnp_Amount=119000000&vnp_CreateDate=20260414164212&vnp_ExpireDate=20260414165712&vnp_CurrCode=VND&vnp_IpAddr=%3A%3A1&vnp_Locale=vn&vnp_OrderInfo=Thanh+toan+don+hang+%2321&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2Flocalhost%2Fphp%2Fshoe_store%2Fuser%2Fvnpay-return.php&vnp_TxnRef=VNPAY_21_1776159732&vnp_SecureHashType=HmacSHA512&vnp_SecureHash=e056ffbcc93d1c8fb139cc42cedda62e7e2ddab27273d', 'NCB', 'VNP15496033', '15496033', '00', '00', '{\"vnp_Amount\":\"119000000\",\"vnp_BankCode\":\"NCB\",\"vnp_BankTranNo\":\"VNP15496033\",\"vnp_CardType\":\"ATM\",\"vnp_OrderInfo\":\"Thanh toan don hang #21\",\"vnp_PayDate\":\"20260414164535\",\"vnp_ResponseCode\":\"00\",\"vnp_TmnCode\":\"U1Q149D8\",\"vnp_TransactionNo\":\"15496033\",\"vnp_TransactionStatus\":\"00\",\"vnp_TxnRef\":\"VNPAY_21_1776159732\",\"vnp_SecureHash\":\"661ce0d5a215a0aaaac1bd891a6a9b4cb5168d671f2081b776897338158fae99b123e4b25bbc76a01c73c919a3a0a6f461b9f38dd2397d8557d1fdfa647831c5\"}', NULL, '2026-04-14 09:42:12', '2026-04-14 09:45:51'),
(7, 23, 'VNPAY_23_1776344903', 3190000.00, 'paid', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Version=2.1.0&vnp_Command=pay&vnp_TmnCode=U1Q149D8&vnp_Amount=319000000&vnp_CreateDate=20260416200823&vnp_ExpireDate=20260416202323&vnp_CurrCode=VND&vnp_IpAddr=%3A%3A1&vnp_Locale=vn&vnp_OrderInfo=Thanh+toan+don+hang+%2323&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2Fsaclike-wooly-shemeka.ngrok-free.dev%2Fphp%2Fshoe_store%2Fuser%2Fvnpay-return.php&vnp_TxnRef=VNPAY_23_1776344903&vnp_SecureHashType=HmacSHA512&vnp_SecureHash=c90642cd54c2efd3f1', 'NCB', 'VNP15499988', '15499988', '00', '00', '{\"vnp_Amount\":\"319000000\",\"vnp_BankCode\":\"NCB\",\"vnp_BankTranNo\":\"VNP15499988\",\"vnp_CardType\":\"ATM\",\"vnp_OrderInfo\":\"Thanh toan don hang #23\",\"vnp_PayDate\":\"20260416201039\",\"vnp_ResponseCode\":\"00\",\"vnp_TmnCode\":\"U1Q149D8\",\"vnp_TransactionNo\":\"15499988\",\"vnp_TransactionStatus\":\"00\",\"vnp_TxnRef\":\"VNPAY_23_1776344903\",\"vnp_SecureHash\":\"e434e257f08aaffba2ffff249427a2c49c9ef25623380eb43005e31f63910629bde9238d6e97bf22061a0401dfdc2e77c94fc5f4d3aceeaaa8852af46dd600ab\"}', NULL, '2026-04-16 13:08:23', '2026-04-16 13:10:54'),
(8, 24, 'VNPAY_24_1776347814', 450000.00, 'initiated', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Version=2.1.0&vnp_Command=pay&vnp_TmnCode=U1Q149D8&vnp_Amount=45000000&vnp_CreateDate=20260416205654&vnp_ExpireDate=20260416211154&vnp_CurrCode=VND&vnp_IpAddr=%3A%3A1&vnp_Locale=vn&vnp_OrderInfo=Thanh+toan+don+hang+%2324&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2Flocalhost%2Fphp%2Fshoe_store%2Fuser%2Fvnpay-return.php&vnp_TxnRef=VNPAY_24_1776347814&vnp_SecureHashType=HmacSHA512&vnp_SecureHash=e61899dba5fdb191f65f323075053fa65ca16c492bdd9e', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-16 13:56:54', '2026-04-16 13:56:54'),
(9, 25, 'VNPAY_25_1776347841', 450000.00, 'paid', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Version=2.1.0&vnp_Command=pay&vnp_TmnCode=U1Q149D8&vnp_Amount=45000000&vnp_CreateDate=20260416205721&vnp_ExpireDate=20260416211221&vnp_CurrCode=VND&vnp_IpAddr=%3A%3A1&vnp_Locale=vn&vnp_OrderInfo=Thanh+toan+don+hang+%2325&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2Flocalhost%2Fphp%2Fshoe_store%2Fuser%2Fvnpay-return.php&vnp_TxnRef=VNPAY_25_1776347841&vnp_SecureHashType=HmacSHA512&vnp_SecureHash=38ba5c341d2c8b29211ade774a8dd5def29f6acb951a85', 'NCB', 'VNP15500056', '15500056', '00', '00', '{\"vnp_Amount\":\"45000000\",\"vnp_BankCode\":\"NCB\",\"vnp_BankTranNo\":\"VNP15500056\",\"vnp_CardType\":\"ATM\",\"vnp_OrderInfo\":\"Thanh toan don hang #25\",\"vnp_PayDate\":\"20260416205854\",\"vnp_ResponseCode\":\"00\",\"vnp_TmnCode\":\"U1Q149D8\",\"vnp_TransactionNo\":\"15500056\",\"vnp_TransactionStatus\":\"00\",\"vnp_TxnRef\":\"VNPAY_25_1776347841\",\"vnp_SecureHash\":\"e928b584ebda4030b86ce14f7b7d020cc71a02dfb98fbfb14f023d7739e20c6201ece9be278d7163f0c7d73aba2d7f44da67ffdfaae7e041ad00a657f0be2366\"}', NULL, '2026-04-16 13:57:21', '2026-04-16 13:59:50'),
(10, 26, 'VNPAY_26_1777000804', 9460000.00, 'failed', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Version=2.1.0&vnp_Command=pay&vnp_TmnCode=U1Q149D8&vnp_Amount=946000000&vnp_CreateDate=20260424102004&vnp_ExpireDate=20260424103504&vnp_CurrCode=VND&vnp_IpAddr=%3A%3A1&vnp_Locale=vn&vnp_OrderInfo=Thanh+toan+don+hang+%2326&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2Flocalhost%2Fphp%2Fshoe_store%2Fuser%2Fvnpay-return.php&vnp_TxnRef=VNPAY_26_1777000804&vnp_SecureHashType=HmacSHA512&vnp_SecureHash=ce836ac75c0048fa3538da8a45fbd520da91d04362219', 'VNPAY', NULL, '0', '24', '02', '{\"vnp_Amount\":\"946000000\",\"vnp_BankCode\":\"VNPAY\",\"vnp_CardType\":\"QRCODE\",\"vnp_OrderInfo\":\"Thanh toan don hang #26\",\"vnp_PayDate\":\"20260424102004\",\"vnp_ResponseCode\":\"24\",\"vnp_TmnCode\":\"U1Q149D8\",\"vnp_TransactionNo\":\"0\",\"vnp_TransactionStatus\":\"02\",\"vnp_TxnRef\":\"VNPAY_26_1777000804\",\"vnp_SecureHash\":\"7fe91c175b24c59209bd771ec453c207967d5815dc2c071697203b19eda14092a88eb9c2dd21805bacd186ac8e883b315376f2e26b6497250d12aba1a3b4feda\"}', NULL, '2026-04-24 03:20:04', '2026-04-24 03:20:09');

-- --------------------------------------------------------
--
-- Cấu trúc bảng cho bảng `chat_threads`
--

CREATE TABLE `chat_threads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Cấu trúc bảng cho bảng `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `sender_role` enum('user','admin') NOT NULL,
  `sender_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `read_by_user_at` timestamp NULL DEFAULT NULL,
  `read_by_admin_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_cart_user_product_size` (`user_id`,`product_id`,`shoe_size`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `chat_threads`
--
ALTER TABLE `chat_threads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_chat_threads_user` (`user_id`),
  ADD KEY `idx_chat_threads_admin` (`admin_id`),
  ADD KEY `idx_chat_threads_last_message_at` (`last_message_at`);

--
-- Chỉ mục cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chat_messages_thread_id` (`thread_id`),
  ADD KEY `idx_chat_messages_created_at` (`created_at`),
  ADD KEY `idx_chat_messages_read_admin` (`read_by_admin_at`),
  ADD KEY `idx_chat_messages_read_user` (`read_by_user_at`);

--
-- Chỉ mục cho bảng `momo_transactions`
--
ALTER TABLE `momo_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_momo_order` (`order_id`),
  ADD UNIQUE KEY `uniq_momo_request` (`request_id`),
  ADD UNIQUE KEY `uniq_momo_order_code` (`momo_order_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order` (`order_id`),
  ADD KEY `fk_order_items_product` (`product_id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_password_resets_token_hash` (`token_hash`),
  ADD KEY `idx_password_resets_user_id` (`user_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_category` (`category_id`);

--
-- Chỉ mục cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_product_reviews_user_product` (`user_id`,`product_id`),
  ADD KEY `idx_product_reviews_product_id` (`product_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `vnpay_transactions`
--
ALTER TABLE `vnpay_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_vnpay_order` (`order_id`),
  ADD UNIQUE KEY `uniq_vnpay_txn_ref` (`txn_ref`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `chat_threads`
--
ALTER TABLE `chat_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `momo_transactions`
--
ALTER TABLE `momo_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `vnpay_transactions`
--
ALTER TABLE `vnpay_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `chat_threads`
--
ALTER TABLE `chat_threads`
  ADD CONSTRAINT `fk_chat_threads_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_chat_threads_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `fk_chat_messages_thread` FOREIGN KEY (`thread_id`) REFERENCES `chat_threads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `momo_transactions`
--
ALTER TABLE `momo_transactions`
  ADD CONSTRAINT `fk_momo_transactions_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `fk_password_resets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `fk_product_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `vnpay_transactions`
--
ALTER TABLE `vnpay_transactions`
  ADD CONSTRAINT `fk_vnpay_transactions_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
