<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GetOverviewTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->getBackupsOverview = $container->make(
            "dhope0000\LXDClient\Controllers\Backups\GetBackupsOverviewController"
        );
    }

    public function testGetOverviewResponseKeys(): void
    {
        $result = array_keys($this->getBackupsOverview->get(1));
        $this->assertEquals(['sizeByMonthYear', 'filesByMonthYear', 'allBackups'], $result);
    }
}
