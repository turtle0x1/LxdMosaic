-- user-id-record-actions.sql

ALTER TABLE `Recorded_Actions` ADD `RA_User_ID` INT NULL AFTER `RA_Date_Created`;

ALTER TABLE `Recorded_Actions` ADD FOREIGN KEY (`RA_User_ID`) REFERENCES `Users`(`User_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- user-host-project.sql

CREATE TABLE `User_Host_Projects`(
    `UHP_User_ID` INT(11) NOT NULL,
    `UHP_Host_ID` INT(11) NOT NULL,
    `UHP_Project` VARCHAR(255) NOT NULL
);

ALTER TABLE `User_Host_Projects` ADD PRIMARY KEY( `UHP_User_ID`, `UHP_Host_ID`);


-- instance-settings-documentation.sql

ALTER TABLE `Instance_Settings` DROP `IS_Description`;

-- instance-backup-schedule.sql

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
    `IBS_BS_ID` INT NOT NULL,
    `IBS_Retention` INT NOT NULL,
    `IBS_Disabled` TINYINT NOT NULL DEFAULT 0,
    `IBS_Disabled_Date` DATETIME,
    `IBS_Disabled_By` INT
);

INSERT INTO `Backup_Strategies` (`BS_ID`, `BS_Name`) VALUES
(1, "Backup & Import");

ALTER TABLE `Instance_Backup_Schedule`
    ADD FOREIGN KEY (`IBS_User_ID`)
    REFERENCES `Users`(`User_ID`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT;

ALTER TABLE `Instance_Backup_Schedule`
    ADD FOREIGN KEY (`IBS_Host_ID`)
    REFERENCES `Hosts`(`Host_ID`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT;

ALTER TABLE `Instance_Backup_Schedule`
    ADD FOREIGN KEY (`IBS_BS_ID`)
    REFERENCES `Backup_Strategies`(`BS_ID`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT;

ALTER TABLE `Instance_Backup_Schedule`
    ADD FOREIGN KEY (`IBS_Disabled_By`)
    REFERENCES `Users`(`User_ID`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT;


-- container_metrics.sql
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

ALTER TABLE `Instance_Metric_Values`
    ADD FOREIGN KEY (`IMV_Host_ID`) REFERENCES `Hosts`(`Host_ID`)
    ON
        DELETE RESTRICT
    ON
        UPDATE RESTRICT;

ALTER TABLE `Instance_Metric_Values`
    ADD FOREIGN KEY (`IMV_IMT_ID`) REFERENCES `Instance_Metric_Types`(`IMT_ID`)
    ON
        DELETE RESTRICT
    ON
        UPDATE RESTRICT;

ALTER TABLE `Instance_Metric_Values` ADD INDEX( `IMV_Date`);
ALTER TABLE `Instance_Metric_Values` ADD INDEX( `IMV_Instance_Name`);

INSERT INTO `Instance_Metric_Types` (
    `IMT_Name`,
    `IMT_Description`,
    `IMT_Metrics_Template_Key`,
    `IMT_Format_Bytes`
) VALUES (
    "Load Averages",
    "Load averages for instance",
    "loadAvg",
    0
), (
    "Memory Usage",
    "Memory usage for instance",
    "memoryUsage",
    1
), (
    "Network Usage",
    "Network usage for instance",
    "networkUsage",
    0
), (
    "Stroage Usage",
    "Storage usage for instance",
    "storageUsage",
    1
), (
    "Nvidia GPU's",
    "Nvidia GPU",
    "nvidiaGpuDetails",
    0
);
