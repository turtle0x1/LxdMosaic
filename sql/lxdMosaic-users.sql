CREATE TABLE `Users` (
    `User_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `User_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `User_Name` VARCHAR(255) NOT NULL,
    `User_Password` VARCHAR(255) NOT NULL,
    `User_Admin` TINYINT DEFAULT 0
);


INSERT INTO `Users` (`User_ID`, `User_Name`, `User_Password`, `User_Admin`) VALUES
    (1, "admin", "$2y$10$K5t0CK89OD8Xj2/S4KQ8Pub6/ChNmh5C7B9ehxOOQ.jUUOL1UqkPy", 1);
