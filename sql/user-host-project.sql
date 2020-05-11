CREATE TABLE `User_Host_Projects`(
    `UHP_User_ID` INT(11) NOT NULL,
    `UHP_Host_ID` INT(11) NOT NULL,
    `UHP_Project` VARCHAR(255) NOT NULL
);

ALTER TABLE `User_Host_Projects` ADD PRIMARY KEY( `UHP_User_ID`, `UHP_Host_ID`);
