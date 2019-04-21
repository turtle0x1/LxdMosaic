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

ALTER TABLE `Deployment_Cloud_Config`
ADD CONSTRAINT `deployments_link`
FOREIGN KEY `drop_on_delete`(`DCC_Deployment_ID`)
REFERENCES `Deployments`(`Deployment_ID`)
ON DELETE CASCADE
ON UPDATE CASCADE;



-- Demo example

INSERT INTO `Cloud_Config` (`CC_ID`, `CC_Name`, `CC_Namespace`, `CC_Description`) VALUES (1, "Example Node", "example/node", "an example cloud config for a node app");

INSERT INTO `Cloud_Config_Data` (`CCD_ID`, `CCD_Cloud_Config_ID`, `CCD_Data`) VALUES (1, 1, "#cloud-config

# Apply updates using apt
package_update: true
package_upgrade: true

# Install packages
packages:
 - nodejs
 - npm

runcmd:
 - git clone https://github.com/turtle0x1/nodeExample /root/nodeExample
 - cd /root/nodeExample && npm install
 - npm -g install pm2
 - pm2 start /root/nodeExample/index.js
 - pm2 startup
 - pm2 save");

INSERT INTO `Deployments` (`Deployment_ID`, `Deployment_Name`) VALUES (1, "Example");

INSERT INTO `Deployment_Cloud_Config` (`DCC_Deployment_ID`, `DCC_Cloud_Config_Rev_ID`)
VALUES (1, 1);
