<?php

namespace dhope0000\LXDClient\Objects;

class RouteToNameMapping
{
    public $routesToName = array (
  'dhope0000\\LXDClient\\Controllers\\Profiles\\GetProfileController\\get' => 'Get Profile',
  'dhope0000\\LXDClient\\Controllers\\Profiles\\DeleteProfileController\\delete' => 'Delete Profile',
  'dhope0000\\LXDClient\\Controllers\\Profiles\\CreateProfileController\\create' => 'Create Profile',
  'dhope0000\\LXDClient\\Controllers\\Profiles\\CopyProfileController\\copyProfile' => 'Copy profile',
  'dhope0000\\LXDClient\\Controllers\\Profiles\\RenameProfileController\\rename' => 'Rename Profile',
  'dhope0000\\LXDClient\\Controllers\\Instances\\CreateImageController\\create' => 'Create image from instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Profiles\\RemoveProfileController\\remove' => 'Remove profile from instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Profiles\\AssignProfilesController\\assign' => 'Assign profiles to instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\SetSettingsController\\set' => 'Set Instance Settings',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Files\\DeletePathController\\delete' => 'Delete Instance File',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Files\\GetPathController\\get' => 'Get Instance Path Contents',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Files\\UploadFilesToPathController\\upload' => 'Upload File To Instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\MigrateInstanceController\\migrate' => 'Migrate Instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Backups\\DeleteBackupController\\delete' => 'Delete Remote Instance Backup',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Backups\\DisableScheduledBackupsController\\disable' => 'Disable Instance Backup Schedule',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Backups\\ImportBackupController\\import' => 'Import Remote Instance Backup',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Backups\\BackupController\\backup' => 'Backup Instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Backups\\ScheduleBackupController\\schedule' => 'Set Instance Backup Schedule',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Backups\\DeleteLocalBackupController\\delete' => 'Delete Local Instance Backup',
  'dhope0000\\LXDClient\\Controllers\\Instances\\CreateController\\create' => 'Create Instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\RenameInstanceController\\rename' => 'Rename Instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Snapshot\\TakeSnapshotController\\takeSnapshot' => 'Take Instance Snapshot',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Snapshot\\RestoreSnapshotController\\restoreSnapshot' => 'Restore Instance Snapshot',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Snapshot\\RenameSnapshotController\\renameSnapshot' => 'Rename Instance Snapshot',
  'dhope0000\\LXDClient\\Controllers\\Instances\\Snapshot\\DeleteSnapshotController\\deleteSnapshot' => 'Delete Instance Snapshot',
  'dhope0000\\LXDClient\\Controllers\\Instances\\DeleteInstanceController\\delete' => 'Delete Instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\CopyInstanceController\\copy' => 'Copy instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\StateController\\start' => 'Start Instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\StateController\\stop' => 'Stop Instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\StateController\\restart' => 'Restart Instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\StateController\\freeze' => 'Freeze Instance',
  'dhope0000\\LXDClient\\Controllers\\Instances\\StateController\\unfreeze' => 'UnFreeze Instance',
  'dhope0000\\LXDClient\\Controllers\\Projects\\DeleteProjectController\\delete' => 'Delete Project',
  'dhope0000\\LXDClient\\Controllers\\Projects\\RenameProjectController\\rename' => 'Rename Project',
  'dhope0000\\LXDClient\\Controllers\\Projects\\CreateProjectController\\create' => 'Create Project',
  'dhope0000\\LXDClient\\Controllers\\Hosts\\Instances\\DeleteInstancesController\\delete' => 'Delete Instances',
  'dhope0000\\LXDClient\\Controllers\\Hosts\\Instances\\GetAllProxyDevicesController\\get' => 'Get all proxy devices',
  'dhope0000\\LXDClient\\Controllers\\Hosts\\Instances\\StartInstancesController\\start' => 'Start Instances',
  'dhope0000\\LXDClient\\Controllers\\Hosts\\Instances\\AddProxyDeviceController\\add' => 'Add proxy device',
  'dhope0000\\LXDClient\\Controllers\\Hosts\\Instances\\DeleteProxyDeviceController\\delete' => 'Delete proxy device',
  'dhope0000\\LXDClient\\Controllers\\Hosts\\Instances\\StopInstancesController\\stop' => 'Stop Instances',
  'dhope0000\\LXDClient\\Controllers\\Hosts\\Settings\\UpdateSettingsController\\update' => 'Update hosts settings',
  'dhope0000\\LXDClient\\Controllers\\Hosts\\DeleteHostController\\delete' => 'Delete Host',
  'dhope0000\\LXDClient\\Controllers\\Hosts\\AddHostsController\\add' => 'Add Hosts',
  'dhope0000\\LXDClient\\Controllers\\Backups\\RestoreBackupController\\restore' => 'Restore Local Backup To Host',
  'dhope0000\\LXDClient\\Controllers\\CloudConfig\\DeployController\\deploy' => 'Deploy Cloud Config',
  'dhope0000\\LXDClient\\Controllers\\CloudConfig\\DeleteController\\delete' => 'Delete Cloud Config',
  'dhope0000\\LXDClient\\Controllers\\CloudConfig\\UpdateController\\update' => 'Update Cloud Config',
  'dhope0000\\LXDClient\\Controllers\\CloudConfig\\CreateController\\create' => 'Create Cloud Config',
  'dhope0000\\LXDClient\\Controllers\\Networks\\DeleteNetworkController\\delete' => 'Delete Network',
  'dhope0000\\LXDClient\\Controllers\\Networks\\Tools\\FindIpAddressController\\find' => 'Find instance ip address',
  'dhope0000\\LXDClient\\Controllers\\Networks\\CreateNetworkController\\create' => 'Create Network',
  'dhope0000\\LXDClient\\Controllers\\InstanceSettings\\GetSettingsOverviewController\\get' => 'Get LXDMosaic Settings Overview',
  'dhope0000\\LXDClient\\Controllers\\InstanceSettings\\SaveAllSettingsController\\saveAll' => 'Save LXDMosaic Settings',
  'dhope0000\\LXDClient\\Controllers\\Storage\\CreatePoolController\\create' => 'Create Storage',
  'dhope0000\\LXDClient\\Controllers\\Storage\\DeleteStoragePoolController\\delete' => 'Delete Storage',
  'dhope0000\\LXDClient\\Controllers\\Deployments\\DeleteDeploymentController\\delete' => 'Delete Deployment',
  'dhope0000\\LXDClient\\Controllers\\Deployments\\StopDeploymentController\\stop' => 'Stop Deployment',
  'dhope0000\\LXDClient\\Controllers\\Deployments\\DeployController\\deploy' => 'Deploy Deployment',
  'dhope0000\\LXDClient\\Controllers\\Deployments\\StartDeploymentController\\start' => 'Start Deployment',
  'dhope0000\\LXDClient\\Controllers\\Deployments\\CreateController\\create' => 'Create Deployment',
  'dhope0000\\LXDClient\\Controllers\\Images\\ImportRemoteImagesController\\import' => 'Import image from simplestream server',
  'dhope0000\\LXDClient\\Controllers\\Images\\DeleteImagesController\\delete' => 'Delete Images From Hosts',
  'dhope0000\\LXDClient\\Controllers\\Images\\Aliases\\RenameController\\rename' => 'Rename Image Alias',
  'dhope0000\\LXDClient\\Controllers\\Images\\Aliases\\UpdateDescriptionController\\update' => 'Update Image Alias Description',
  'dhope0000\\LXDClient\\Controllers\\Images\\Aliases\\DeleteController\\delete' => 'Delete Image Alias',
  'dhope0000\\LXDClient\\Controllers\\Images\\Aliases\\CreateController\\create' => 'Create Image Alias',
  'dhope0000\\LXDClient\\Controllers\\Images\\ImportLinuxContainersByAliasController\\import' => 'Import LinunxContainer.Org Image',
);

    public function getControllerName(string $controller)
    {
        if (!isset($this->routesToName[$controller])) {
            return "";
        }
        return $this->routesToName[$controller];
    }
}