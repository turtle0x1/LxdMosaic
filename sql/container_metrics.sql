CREATE TABLE `Instance_Metric_Types` (
    `IMT_ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `IMT_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `IMT_Name` VARCHAR(255) NOT NULL,
    `IMT_Description` TEXT NOT NULL,
    `IMT_Metrics_Template_Key` VARCHAR(255) NOT  NULL
);

CREATE TABLE `Instance_Metric_Values` (
    `IMV_ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `IMV_Date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `IMV_Host_ID` INT(11) NOT NULL,
    `IMV_Containr_Name` VARCHAR(255) NOT NULL,
    `IMV_IMT_ID` INT(11) NOT NULL,
    `IMV_Data` JSON
);


INSERT INTO `Instance_Metric_Types` (
    `IMT_Name`,
    `IMT_Description`,
    `IMT_Metrics_Template_Key`
) VALUES (
    "Load Averages",
    "Load averages for containers",
    "loadAvg"
);
