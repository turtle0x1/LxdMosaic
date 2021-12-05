<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class EnableNvidiaContainerOptions extends AbstractMigration
{
    public function change(): void
    {
        $this->query("UPDATE
                        `Container_Options`
                    SET
                        `CO_Enabled` = 1
                    WHERE
                        `CO_Key` IN (
                            'nvidia.driver.capabilities',
                            'nvidia.runtime',
                            'nvidia.require.cuda',
                            'nvidia.require.driver'
                        )
                    ");
    }
}
