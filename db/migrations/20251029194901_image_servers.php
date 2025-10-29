<?php

declare(strict_types=1);

use dhope0000\LXDClient\Constants\ImageServerType;
use Phinx\Migration\AbstractMigration;

final class ImageServers extends AbstractMigration
{
    public function change(): void
    {
        $this->table("Image_Servers")->drop()->save();
        $imageServers = $this->table('Image_Servers', ['id' => 'IS_ID', 'primary_key' => ['IS_ID']]);
        $imageServers->addColumn('IS_Date_Created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('IS_Alias', 'string')
            ->addColumn('IS_Url', 'string')
            ->addColumn('IS_Protocol', 'integer')
            ->addIndex([
                'IS_Alias'
            ], ['unique' => true,  'name' => 'unique_image_alias'])
            ->create();

        $imageServers->insert([
            ['IS_Alias' => 'linuxcontainers', 'IS_Url' => 'https://images.linuxcontainers.org/streams/v1/images.json', 'IS_Protocol' => ImageServerType::SIMPLE_STREAMS],
            ['IS_Alias' => 'ubuntu-release', 'IS_Url' => 'https://cloud-images.ubuntu.com/releases/streams/v1/com.ubuntu.cloud:released:download.json', 'IS_Protocol' => ImageServerType::UBUNTU_RELEASE],
            ['IS_Alias' => 'ubuntu-daily', 'IS_Url' => 'https://cloud-images.ubuntu.com/daily/streams/v1/com.ubuntu.cloud:daily:download.json', 'IS_Protocol' => ImageServerType::UBUNTU_RELEASE],
            ['IS_Alias' => 'canonical-images', 'IS_Url' => 'https://images.lxd.canonical.com/streams/v1/images.json', 'IS_Protocol' => ImageServerType::SIMPLE_STREAMS]
        ])->save();
    }
}
