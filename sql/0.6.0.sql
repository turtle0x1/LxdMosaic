use LXD_Manager;

INSERT INTO `Instance_Settings` (`IS_ID`, `IS_Name`, `IS_Description`) VALUES
(3, "Backup Directory", "The local directory backups can be downloaded into");

INSERT INTO `Instance_Settings_Values` (`ISV_IS_ID`, `ISV_Value`) VALUES (
    3, "/var/www/LxdMosaic/src/sensitiveData/backups"
);


CREATE TABLE `Container_Backups` (
    `CB_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `CB_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `CB_Backup_Date_Created` DATETIME NOT NULL,
    `CB_Host_ID` INT(11) NOT NULL,
    `CB_Container` VARCHAR(255) NOT NULL,
    `CB_Backup` VARCHAR(255) NOT NULL,
    `CB_Local_Path` VARCHAR(255) NOT NULL
);

ALTER TABLE `Cloud_Config_Data` ADD COLUMN `CCD_Enviroment_Variables` JSON;
