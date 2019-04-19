use `LXD_Manager`;

CREATE TABLE `Deployments` (
    `Deployment_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `Deployment_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `Deployment_Name` VARCHAR(255) NOT NULL
);

CREATE TABLE `Deployment_Cloud_Config` (
    `DCC_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `DCC_Deployment_ID` INT(11) NOT NULL,
    `DCC_Cloud_Config_Rev_ID` INT(11) NOT NULL
);
