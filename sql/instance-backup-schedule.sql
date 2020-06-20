CREATE TABLE `Backup_Strategies` (
    `BS_ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `BS_Name` VARCHAR(255) NOT NULL
);

CREATE TABLE `Instance_Backup_Schedule` (
    `IBS_ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `IBS_User_ID` INT NOT NULL,
    `IBS_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `IBS_Host_ID` INT NOT NULL,
    `IBS_Instance` VARCHAR(255) NOT NULL,
    `IBS_Project` VARCHAR(255) NOT NULL,
    `IBS_Schedule_String` VARCHAR(255) NOT NULL,
    `IBS_BS_ID` INT NOT NULL
);

INSERT INTO `Backup_Strategies` (`BS_ID`, `BS_Name`) VALUES
(1, "Backup"),
(2, "Backup & Import");
