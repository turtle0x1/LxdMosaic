<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\DeleteCloudConfig;

class DeleteController
{
    public function __construct(DeleteCloudConfig $deleteCloudConfig)
    {
        $this->deleteCloudConfig = $deleteCloudConfig;
    }

    public function delete(int $cloudConfigId)
    {
        $this->deleteCloudConfig->delete($cloudConfigId);
        return ["state"=>"success", "message"=>"Deleted Cloud Config"];
    }
}
