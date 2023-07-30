
SET NAMES utf8mb4;

DROP TABLE IF EXISTS `Brands`;

CREATE TABLE `Brands` (
                          `B_Name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
                          `B_CreateDate` int DEFAULT NULL,
                          `B_LastEditDate` int DEFAULT NULL,
                          PRIMARY KEY (`B_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `BrandsLog`;

CREATE TABLE `BrandsLog` (
                             `id` int unsigned NOT NULL AUTO_INCREMENT,
                             `BL_OldName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
                             `BL_NewName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
                             `BL_Date` int NOT NULL,
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
