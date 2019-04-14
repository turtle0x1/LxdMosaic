use `LXD_Manager`;

CREATE TABLE `Deployments` (
    `Deployment_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `Deployment_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `Deployment_Name` VARCHAR(255) NOT NULL
);
