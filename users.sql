CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `firstname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
 `lastname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,    
 `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
 `password` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;