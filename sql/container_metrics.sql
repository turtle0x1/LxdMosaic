ALTER TABLE `Hosts` ADD `Host_Support_Load_Averages` TINYINT NOT NULL DEFAULT '0' AFTER `Host_Online`;

CREATE TABLE `Instance_Metric_Types` (
    `IMT_ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `IMT_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `IMT_Name` VARCHAR(255) NOT NULL,
    `IMT_Description` TEXT NOT NULL,
    `IMT_Metrics_Template_Key` VARCHAR(255) NOT  NULL,
    `IMT_Format_Bytes` TINYINT NOT NULL DEFAULT 0
);

CREATE TABLE `Instance_Metric_Values` (
    `IMV_ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `IMV_Date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `IMV_Host_ID` INT(11) NOT NULL,
    `IMV_Project_Name` VARCHAR(255) NOT NULL DEFAULT "default",
    `IMV_Instance_Name` VARCHAR(255) NOT NULL,
    `IMV_IMT_ID` INT(11) NOT NULL,
    `IMV_Data` JSON
);

CREATE TABLE `User_Dashboards` (
    `UD_ID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `UD_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `UD_User_ID` INT NOT NULL,
    `UD_Name` VARCHAR(255) NOT NULL,
    `UD_Public` TINYINT NOT NULL DEFAULT 0
);

CREATE TABLE `User_Dashboard_Graphs` (
    `UDG_ID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `UDG_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `UDG_UD_ID` INT NOT NULL,
    `UDG_Name` VARCHAR(255) NOT NULL,
    `UDG_Host_ID` INT NOT NULL,
    `UDG_Instance` VARCHAR(255) NOT NULL,
    `UDG_Project` VARCHAR(255) NOT NULL DEFAULT "default",
    `UDG_Metric_ID` INT NOT NULL,
    `UDG_Filter` VARCHAR(255) NOT NULL,
    `UDG_Range` VARCHAR(255) NOT NULL
);

ALTER TABLE `User_Dashboard_Graphs`
    ADD FOREIGN KEY(`UDG_UD_ID`) REFERENCES `User_Dashboards`(`UD_ID`)
    ON
        DELETE CASCADE
    ON
        UPDATE RESTRICT;

ALTER TABLE `User_Dashboard_Graphs`
    ADD FOREIGN KEY(`UDG_Host_ID`) REFERENCES `Hosts`(`Host_ID`)
    ON
        DELETE CASCADE
    ON
        UPDATE RESTRICT;

ALTER TABLE `User_Dashboard_Graphs`
    ADD FOREIGN KEY(`UDG_Metric_ID`) REFERENCES `Instance_Metric_Types`(`IMT_ID`)
    ON
        DELETE CASCADE
    ON
        UPDATE RESTRICT;

ALTER TABLE `Instance_Metric_Values` ADD INDEX( `IMV_Date`);
ALTER TABLE `Instance_Metric_Values` ADD INDEX( `IMV_Instance_Name`);

INSERT INTO `Instance_Metric_Types` (
    `IMT_Name`,
    `IMT_Description`,
    `IMT_Metrics_Template_Key`
) VALUES (
    "Load Averages",
    "Load averages for instance",
    "loadAvg"
), (
    "Memory Usage",
    "Memory usage for instance",
    "memoryUsage"
), (
    "Network Usage",
    "Network usage for instance",
    "networkUsage"
), (
    "Stroage Usage",
    "Storage usage for instance",
    "storageUsage"
), (
    "Nvidia GPU's",
    "Nvidia GPU",
    "nvidiaGpuDetails"
);
