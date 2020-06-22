<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\DeleteCloudConfig;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteCloudConfig $deleteCloudConfig)
    {
        $this->deleteCloudConfig = $deleteCloudConfig;
    }
    /**
     * @Route("", name="Delete Cloud Config")
     */
    public function delete(int $cloudConfigId)
    {
        $this->deleteCloudConfig->delete($cloudConfigId);
        return ["state"=>"success", "message"=>"Deleted Cloud Config"];
    }
}
