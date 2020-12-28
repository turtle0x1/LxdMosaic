<?php
namespace dhope0000\LXDClient\Controllers\Instances\Volumes;

use dhope0000\LXDClient\Tools\Instances\Volumes\AttachVolumes;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class AttachVolumesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $attachVolumes;

    public function __construct(AttachVolumes $attachVolumes)
    {
        $this->attachVolumes = $attachVolumes;
    }
    /**
     * @Route("", name="Attach volume to instance")
     */
    public function attach(
        int $userId,
        Host $host,
        string $container,
        array $volume,
        string $name,
        string $path
    ) {
        $this->attachVolumes->attach(
            $userId,
            $host,
            $container,
            $volume,
            $name,
            $path
        );
        return ["state"=>"success", "message"=>"Attached volume"];
    }
}
