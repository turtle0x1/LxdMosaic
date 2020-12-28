USE `LXD_Manager`;

ALTER TABLE `Fleet_Analytics` ADD COLUMN `FA_Total_Storage_Available` BIGINT NOT NULL;

CREATE TABLE `Users` (
    `User_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `User_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `User_Name` VARCHAR(255) NOT NULL,
    `User_Password` VARCHAR(255) NOT NULL,
    `User_Admin` TINYINT DEFAULT 0
);

CREATE TABLE `User_Api_Tokens` (
    `UAT_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `UAT_Created_At` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UAT_User_ID` INT(11),
    `UAT_Token` TEXT NOT NULL,
    `UAT_Permanent` TINYINT NOT NULL DEFAULT 0
);

INSERT INTO `Users` (`User_ID`, `User_Name`, `User_Password`, `User_Admin`) VALUES
    (1, "admin", "", 1);
