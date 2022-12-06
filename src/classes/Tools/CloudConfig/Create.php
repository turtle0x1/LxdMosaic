<?php

namespace dhope0000\LXDClient\Tools\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\Namespaces\GetCloudConfigs;
use dhope0000\LXDClient\Model\CloudConfig\CreateConfig;

class Create
{
    private GetCloudConfigs $getCloudConfigs;
    private CreateConfig $createConfig;

    public function __construct(
        GetCloudConfigs $getCloudConfigs,
        CreateConfig $createConfig
    ) {
        $this->getCloudConfigs = $getCloudConfigs;
        $this->createConfig = $createConfig;
    }

    public function create(
        string $name,
        string $namespace,
        string $description = ""
    ) :bool {
        if ($this->getCloudConfigs->haveCloudConfigInNamespace($name, $namespace)) {
            throw new \Exception("Have a cloud config with this name in this namespace", 1);
        }

        $this->createConfig->create($name, $namespace, $description);
        return true;
    }
}
