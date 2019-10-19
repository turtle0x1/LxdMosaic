use `LXD_Manager`;

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

CREATE TABLE `Instance_Settings` (
    `IS_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `IS_Name` VARCHAR(255) NOT NULL,
    `IS_Description` TEXT NOT NULL
);

INSERT INTO `Instance_Settings` (`IS_ID`, `IS_Name`, `IS_Description`) VALUES
(1, "Instance IP", "The connection string containers can reach this instance of LXDMosaic's node server to phone home when they are part of a deployment"),
(2, "Record Actions", "47 different actions will be recorded! 0 for disabled & 1 for enabled");


CREATE TABLE `Instance_Settings_Values` (
    `ISV_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ISV_Date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `ISV_IS_ID` INT(11) NOT NULL,
    `ISV_Value` VARCHAR(255) NOT NULL
);

INSERT INTO `Instance_Settings_Values` (`ISV_IS_ID`, `ISV_Value`) VALUES (
    2, 0
);

CREATE TABLE `Recorded_Actions` (
    `RA_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `RA_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `RA_Controller` VARCHAR(255) NOT NULL,
    `RA_Params` JSON NOT NULL
);
