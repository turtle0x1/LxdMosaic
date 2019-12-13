use LXD_Manager;
ALTER TABLE `Cloud_Config_Data` ADD COLUMN `CCD_Enviroment_Variables` JSON;

INSERT INTO `Instance_Settings` (`IS_ID`, `IS_Name`, `IS_Description`) VALUES
(3, "Backup Directory", "The local directory backups can be downloaded into");

INSERT INTO `Instance_Settings_Values` (`ISV_IS_ID`, `ISV_Value`) VALUES (
    3, "/var/www/LxdMosaic/src/sensitiveData/backups"
);
