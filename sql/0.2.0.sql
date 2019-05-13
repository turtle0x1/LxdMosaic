use `LXD_Manager`;

ALTER TABLE `Cloud_Config_Data` ADD COLUMN `CCD_Image_Details` JSON;

CREATE TABLE IF NOT EXISTS `Fleet_Analytics` (
  `FA_ID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `FA_Date_Created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FA_Total_Memory_Usage` BIGINT NOT NULL,
  `FA_Active_Containers` INT(11) NOT NULL
);

-- See https://stackoverflow.com/a/45548042 for more information
DROP PROCEDURE IF EXISTS `?`;
DELIMITER //
CREATE PROCEDURE `?`()
BEGIN
  DECLARE CONTINUE HANDLER FOR SQLEXCEPTION BEGIN END;
  ALTER TABLE `Hosts` ADD COLUMN `Host_Online` TINYINT(1) NULL DEFAULT 1;
END //
DELIMITER ;
CALL `?`();
DROP PROCEDURE `?`;

DROP PROCEDURE IF EXISTS `?`;
DELIMITER //
CREATE PROCEDURE `?`()
BEGIN
  DECLARE CONTINUE HANDLER FOR SQLEXCEPTION BEGIN END;
  ALTER TABLE `Hosts` ADD COLUMN `Host_Alias` VARCHAR(255) NULL;
END //
DELIMITER ;
CALL `?`();
DROP PROCEDURE `?`;

CREATE TABLE IF NOT EXISTS `Container_Options` (
    `CO_ID` INT(11) NOT NULL AUTO_INCREMENT,
    `CO_Key` VARCHAR(255) NOT NULL,
    `CO_Type` VARCHAR(255) NOT NULL,
    `CO_Default` VARCHAR(255) NOT NULL,
    `CO_Live_Update` VARCHAR(255) NOT NULL,
    `CO_Api_Extension` VARCHAR(255) NOT NULL,
    `CO_Description` TEXT NOT NULL,
    `CO_Enabled` BOOLEAN NOT NULL,
    PRIMARY KEY(`CO_ID`)
);

INSERT IGNORE INTO `Container_Options` (
    `CO_Key`,
    `CO_Type`,
    `CO_Default`,
    `CO_Live_Update`,
    `CO_Api_Extension`,
    `CO_Description`,
    `CO_Enabled`
) VALUES
("boot.autostart","boolean", "-", "n/a", "-", "Always start the container when LXD starts (if not set, restore last state)", "1"),
("boot.autostart.delay","integer","0","n/a","-","Number of seconds to wait after the container started before starting the next one","1"),
("boot.autostart.priority","integer","0","n/a","-","What order to start the containers in (starting with highest)","1"),
("boot.host_shutdown_timeout","integer","30","yes","container_host_shutdown_timeout","Seconds to wait for container to shutdown before it is force stopped","0"),
("boot.stop.priority","integer","0","n/a","container_stop_priority","What order to shutdown the containers (starting with highest)","0"),
("environment.*","string","-","yes (exec)","-","key/value environment variables to export to the container and set on exec","0"),
("limits.cpu","string","- ","yes","-","Number or range of CPUs to expose to the container","1"),
("limits.cpu.allowance","string","100%","yes","-","How much of the CPU can be used. Can be a percentage (e.g. 50%) for a soft limit or hard a chunk of time (25ms/100ms)","1"),
("limits.cpu.priority","integer","10","yes","-","CPU scheduling priority compared to other containers sharing the same CPUs (overcommit) (integer between 0 and 10)","1"),
("limits.disk.priority","integer","5","yes","-","When under load, how much priority to give to the container's I/O requests (integer between 0 and 10)","1"),
("limits.kernel.*","string","-","no","kernel_limits","This limits kernel resources per container (e.g. number of open files)","0"),
("limits.memory","string","-","yes","-","Percentage of the host's memory or fixed value in bytes (various suffixes supported, see below)","1"),
("limits.memory.enforce","string","hard","yes","-","If hard, container can't exceed its memory limit. If soft, the container can exceed its memory limit when extra host memory is available.","1"),
("limits.memory.swap","boolean","true","yes","-","Whether to allow some of the container's memory to be swapped out to disk","1"),
("limits.memory.swap.priority","integer","10","yes","-","The higher this is set, the least likely the container is to be swapped to disk (integer between 0 and 10)","1"),
("limits.network.priority","integer","0","yes","-","When under load, how much priority to give to the container's network requests (integer between 0 and 10)","1"),
("limits.processes","integer","-","yes","-","Maximum number of processes that can run in the container","1"),
("linux.kernel_modules","string","-","yes","-","Comma separated list of kernel modules to load before starting the container","1"),
("migration.incremental.memory","boolean","false","yes","migration_pre_copy","Incremental memory transfer of the container's memory to reduce downtime.","0"),
("migration.incremental.memory.goal","integer","70","yes","migration_pre_copy","Percentage of memory to have in sync before stopping the container.","0"),
("migration.incremental.memory.iterations","integer","10","yes","migration_pre_copy","Maximum number of transfer operations to go through before stopping the container.","0"),
("nvidia.driver.capabilities","string","compute,utility","no","nvidia_runtime_config","What driver capabilities the container needs (sets libnvidia-container NVIDIA_DRIVER_CAPABILITIES)","0"),
("nvidia.runtime","boolean","false","no","nvidia_runtime","Pass the host NVIDIA and CUDA runtime libraries into the container","0"),
("nvidia.require.cuda","string","-","no","nvidia_runtime_config","Version expression for the required CUDA version (sets libnvidia-container NVIDIA_REQUIRE_CUDA)","0"),
("nvidia.require.driver","string","-","no","nvidia_runtime_config","Version expression for the required driver version (sets libnvidia-container NVIDIA_REQUIRE_DRIVER)","0"),
("raw.apparmor","blob","-","yes","-","Apparmor profile entries to be appended to the generated profile","1"),
("raw.idmap","blob","-","no","id_map","Raw idmap configuration (e.g. 'both 1000 1000')","0"),
("raw.lxc","blob","-","no","-","Raw LXC configuration to be appended to the generated one","1"),
("raw.seccomp","blob","-","no","container_syscall_filtering","Raw Seccomp configuration","0"),
("security.devlxd","boolean","true","no","restrict_devlxd","Controls the presence of /dev/lxd in the container","0"),
("security.devlxd.images","boolean","false","no","devlxd_images","Controls the availability of the /1.0/images API over devlxd","0"),
("security.idmap.base","integer","-","no","id_map_base","The base host ID to use for the allocation (overrides auto-detection)","0"),
("security.idmap.isolated","boolean","false","no","id_map","Use an idmap for this container that is unique among containers with isolated set.","0"),
("security.idmap.size","integer","-","no","id_map","The size of the idmap to use","0"),
("security.nesting","boolean","false","yes","-","Support running lxd (nested) inside the container","1"),
("security.privileged","boolean","false","no","-","Runs the container in privileged mode","1"),
("security.protection.delete","boolean","false","yes","container_protection_delete","Prevents the container from being deleted","0"),
("security.protection.shift","boolean","false","yes","container_protection_shift","Prevents the container's filesystem from being uid/gid shifted on startup","0"),
("security.syscalls.blacklist","string","-","no","container_syscall_filtering","A '\n' separated list of syscalls to blacklist","0"),
("security.syscalls.blacklist_compat","boolean","false","no","container_syscall_filtering","On x86_64 this enables blocking of compat_* syscalls, it is a no-op on other arches","0"),
("security.syscalls.blacklist_default","boolean","true","no","container_syscall_filtering","Enables the default syscall blacklist","0"),
("security.syscalls.whitelist","string","-","no","container_syscall_filtering","A '\n' separated list of syscalls to whitelist (mutually exclusive with security.syscalls.blacklist*)","0"),
("snapshots.schedule","string","-","no","snapshot_scheduling","Cron expression (<minute> <hour> <dom> <month> <dow>)","0"),
("snapshots.schedule.stopped","bool","false","no","snapshot_scheduling","Controls whether or not stopped containers are to be snapshoted automatically","0"),
("snapshots.pattern","string","snap%d","no","snapshot_scheduling","Pongo2 template string which represents the snapshot name (used for scheduled snapshots and unnamed snapshots)","0"),
("user.*","string","-","n/a","-","","0");

INSERT IGNORE INTO `Instace_Type_Providers` (
    `ITP_ID`,
    `ITP_Name`
) VALUES
    (1, "aws"),
    (2, "azure"),
    (3, "gce");

INSERT IGNORE INTO `Instance_Types` (
    `IT_Provider_ID`,
    `IT_Name`,
    `IT_CPU`,
    `IT_Mem`
) VALUES
-- Aws
(1, "c1.medium", 2, 1.7),
(1, "c1.xlarge", 8, 7),
(1, "c3.2xlarge", 8, 15),
(1, "c3.4xlarge", 16, 30),
(1, "c3.8xlarge", 32, 60),
(1, "c3.large", 2, 3.75),
(1, "c3.xlarge", 4, 7.5),
(1, "c4.2xlarge", 8, 15),
(1, "c4.4xlarge", 16, 30),
(1, "c4.8xlarge", 36, 60),
(1, "c4.large", 2, 3.75),
(1, "c4.xlarge", 4, 7.5),
(1, "c5.18xlarge", 72, 144),
(1, "c5.2xlarge", 8, 16),
(1, "c5.4xlarge", 16, 32),
(1, "c5.9xlarge", 36, 72),
(1, "c5.large", 2, 4),
(1, "c5.xlarge", 4, 8),
(1, "cc2.8xlarge", 32, 60.5),
(1, "cg1.4xlarge", 16, 22.5),
(1, "cr1.8xlarge", 32, 244),
(1, "d2.2xlarge", 8, 61),
(1, "d2.4xlarge", 16, 122),
(1, "d2.8xlarge", 36, 244),
(1, "d2.xlarge", 4, 30.5),
(1, "f1.16xlarge", 64, 976),
(1, "f1.2xlarge", 8, 122),
(1, "g2.2xlarge", 8, 15),
(1, "g2.8xlarge", 32, 60),
(1, "g3.16xlarge", 64, 488),
(1, "g3.4xlarge", 16, 122),
(1, "g3.8xlarge", 32, 244),
(1, "hi1.4xlarge", 16, 60.5),
(1, "hs1.8xlarge", 16, 117),
(1, "i2.2xlarge", 8, 61),
(1, "i2.4xlarge", 16, 122),
(1, "i2.8xlarge", 32, 244),
(1, "i2.xlarge", 4, 30.5),
(1, "i3.16xlarge", 64, 488),
(1, "i3.2xlarge", 8, 61),
(1, "i3.4xlarge", 16, 122),
(1, "i3.8xlarge", 32, 244),
(1, "i3.large", 2, 15.25),
(1, "i3.xlarge", 4, 30.5),
(1, "m1.large", 2, 7.5),
(1, "m1.medium", 1, 3.75),
(1, "m1.small", 1, 1.7),
(1, "m1.xlarge", 4, 15),
(1, "m2.2xlarge", 4, 34.2),
(1, "m2.4xlarge", 8, 68.4),
(1, "m2.xlarge", 2, 17.1),
(1, "m3.2xlarge", 8, 30),
(1, "m3.large", 2, 7.5),
(1, "m3.medium", 1, 3.75),
(1, "m3.xlarge", 4, 15),
(1, "m4.10xlarge", 40, 160),
(1, "m4.16xlarge", 64, 256),
(1, "m4.2xlarge", 8, 32),
(1, "m4.4xlarge", 16, 64),
(1, "m4.large", 2, 8),
(1, "m4.xlarge", 4, 16),
(1, "p2.16xlarge", 64, 732),
(1, "p2.8xlarge", 32, 488),
(1, "p2.xlarge", 4, 61),
(1, "r3.2xlarge", 8, 61),
(1, "r3.4xlarge", 16, 122),
(1, "r3.8xlarge", 32, 244),
(1, "r3.large", 2, 15.25),
(1, "r3.xlarge", 4, 30.5),
(1, "r4.16xlarge", 64, 488),
(1, "r4.2xlarge", 8, 61),
(1, "r4.4xlarge", 16, 122),
(1, "r4.8xlarge", 32, 244),
(1, "r4.large", 2, 15.25),
(1, "r4.xlarge", 4, 30.5),
(1, "t1.micro", 1, 0.613),
(1, "t2.2xlarge", 8, 32),
(1, "t2.large", 2, 8),
(1, "t2.medium", 2, 4),
(1, "t2.micro", 1, 1),
(1, "t2.nano", 1, 0.5),
(1, "t2.small", 1, 2),
(1, "t2.xlarge", 4, 16),
(1, "x1.16xlarge", 64, 976),
(1, "x1.32xlarge", 128, 1952),
-- Azure
(2, "A10", 8, 56),
(2, "A11", 16, 112),
(2, "A5", 2, 14),
(2, "A6", 4, 28),
(2, "A7", 8, 56),
(2, "A8", 8, 56),
(2, "A9", 16, 112),
(2, "ExtraLarge", 8, 14),
(2, "ExtraSmall", 1, 0.768),
(2, "Large", 4, 7),
(2, "Medium", 2, 3.5),
(2, "Small", 1, 1.75),
(2, "Standard_A1_v2", 1, 2),
(2, "Standard_A2_v2", 2, 4),
(2, "Standard_A2m_v2", 2, 16),
(2, "Standard_A4_v2", 4, 8),
(2, "Standard_A4m_v2", 4, 32),
(2, "Standard_A8_v2", 8, 16),
(2, "Standard_A8m_v2", 8, 64),
(2, "Standard_D1", 1, 3.5),
(2, "Standard_D11", 2, 14),
(2, "Standard_D11_v2", 2, 14),
(2, "Standard_D12", 4, 28),
(2, "Standard_D12_v2", 4, 28),
(2, "Standard_D13", 8, 56),
(2, "Standard_D13_v2", 8, 56),
(2, "Standard_D14", 16, 112),
(2, "Standard_D14_v2", 16, 112),
(2, "Standard_D15_v2", 20, 140),
(2, "Standard_D1_v2", 1, 3.5),
(2, "Standard_D2", 2, 7),
(2, "Standard_D2_v2", 2, 7),
(2, "Standard_D3", 4, 14),
(2, "Standard_D3_v2", 4, 14),
(2, "Standard_D4", 8, 28),
(2, "Standard_D4_v2", 8, 28),
(2, "Standard_D5_v2", 16, 56),
(2, "Standard_G1", 2, 28),
(2, "Standard_G2", 4, 56),
(2, "Standard_G3", 8, 112),
(2, "Standard_G4", 16, 224),
(2, "Standard_G5", 32, 448),
(2, "Standard_H16", 16, 112),
(2, "Standard_H16m", 16, 224),
(2, "Standard_H16mr", 16, 224),
(2, "Standard_H16r", 16, 112),
(2, "Standard_H8", 8, 56),
(2, "Standard_H8m", 8, 112),
-- GCE
(3, "f1-micro", 0.2, 0.6),
(3, "g1-small", 0.5, 1.7),
(3, "n1-highcpu-16", 16, 14.4),
(3, "n1-highcpu-2", 2, 1.8),
(3, "n1-highcpu-32", 32, 28.8),
(3, "n1-highcpu-4", 4, 3.6),
(3, "n1-highcpu-64", 64, 57.6),
(3, "n1-highcpu-8", 8, 7.2),
(3, "n1-highmem-16", 16, 104),
(3, "n1-highmem-2", 2, 13),
(3, "n1-highmem-32", 32, 208),
(3, "n1-highmem-4", 4, 26),
(3, "n1-highmem-64", 64, 416),
(3, "n1-highmem-8", 8, 52),
(3, "n1-standard-1", 1, 3.75),
(3, "n1-standard-16", 16, 60),
(3, "n1-standard-2", 2, 7.5),
(3, "n1-standard-32", 32, 120),
(3, "n1-standard-4", 4, 15),
(3, "n1-standard-64", 64, 240),
(3, "n1-standard-8", 8, 30);


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

INSERT INTO `Cloud_Config_Data` (`CCD_ID`, `CCD_Cloud_Config_ID`, `CCD_Data`, `CCD_Image_Details`) VALUES (1, 1, "#cloud-config

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
 - pm2 save", '{"details": {"alias": "default", "server": "https://cloud-images.ubuntu.com/releases", "protocol": "simplestreams", "certificate": "", "fingerprint": "5b72cf46f628b3d60f5d99af48633539b2916993c80fc5a2323d7d841f66afbe"}, "description": "ubuntu 18.04 LTS amd64 (release) (20190424)"}');

INSERT INTO `Deployments` (`Deployment_ID`, `Deployment_Name`) VALUES (1, "Example");

INSERT INTO `Deployment_Cloud_Config` (`DCC_Deployment_ID`, `DCC_Cloud_Config_Rev_ID`)
VALUES (1, 1);
