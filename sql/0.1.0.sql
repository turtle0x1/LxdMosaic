ALTER TABLE `Hosts`
    ADD `Host_Cert_Only_File` VARCHAR(255) NOT NULL AFTER `Host_Cert_Path`,
    ADD `Host_Key_File` VARCHAR(255) NOT NULL AFTER `Host_Cert_Only_File`; 
