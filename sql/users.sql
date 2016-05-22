CREATE TABLE `users` (
`uid` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
`username` varchar(25) NOT NULL UNIQUE,
`password` varchar(255) NOT NULL,
`email` varchar(100) NOT NULL UNIQUE
);