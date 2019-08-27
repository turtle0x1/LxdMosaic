CREATE TABLE `Deployment_Containers` (
    `DC_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `DC_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `DC_Deployment_ID` INT(11) NOT NULL,
    `DC_Host_ID` INT(11) NOT NULL,
    `DC_Name` VARCHAR(255) NOT NULL,
    `DC_First_Start` DATETIME NULL,
    `DC_Last_Start` DATETIME NULL,
    `DC_Last_Stop` DATETIME NULL,
    `DC_Phone_Home_Date` DATETIME NULL,
    `DC_Destoryed` DATETIME NULL
);
