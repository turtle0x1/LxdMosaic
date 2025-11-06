<?php

use Crunz\Schedule;

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

(\Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'))->load();

$getInstanceSetting = $container->make("dhope0000\LXDClient\Model\InstanceSettings\GetSetting");

$ldapServer = $getInstanceSetting->getSettingLatestValue(
    dhope0000\LXDClient\Constants\InstanceSettingsKeys::LDAP_SERVER
);

if (empty($ldapServer)) {
    return new Schedule();
}

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/importLdapUsers.php');
$task
    ->hourly()
    ->description('Check for users in ldap to be imported');

return $schedule;
