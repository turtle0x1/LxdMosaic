<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RemoveMetricsTest extends TestCase
{
    private $removeMetrics;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->removeMetrics = $container->make("dhope0000\LXDClient\Tools\Instances\Metrics\RemoveMetrics");
    }

    public function testGroupingMetricsYeildsCorrectResult(): void
    {
        $x = $this->removeMetrics->groupMetrics([
            [
                'id' => 1,
                'hostId' => 1,
                'project' => 'default',
                'instance' => 'test',
                'typeId' => 1,
                'dTime' => (new \DateTime('2021-01-20'))->setTime(00, 00)
                    ->format('Y-m-d H:i:s'),
                'data' => json_encode([
                    'usage' => 1,
                    'swap_usage' => 0,
                    'usage_peak' => 1,
                    'swap_usage_peak' => 0,
                ]),
            ],
            [
                'id' => 2,
                'hostId' => 1,
                'project' => 'default',
                'instance' => 'test',
                'typeId' => 1,
                'dTime' => (new \DateTime('2021-01-20'))->setTime(00, 00)
                    ->format('Y-m-d H:i:s'),
                'data' => json_encode([
                    'usage' => 2,
                    'swap_usage' => 0,
                    'usage_peak' => 2,
                    'swap_usage_peak' => 0,
                ]),
            ],
            [
                'id' => 3,
                'hostId' => 1,
                'project' => 'default',
                'instance' => 'test',
                'typeId' => 1,
                'dTime' => (new \DateTime('2021-01-20'))->setTime(00, 00)
                    ->format('Y-m-d H:i:s'),
                'data' => json_encode([
                    'usage' => 3,
                    'swap_usage' => 0,
                    'usage_peak' => 3,
                    'swap_usage_peak' => 0,
                ]),
            ],
        ]);
        $this->assertEquals($x, [
            1 => [
                'default' => [
                    'test' => [
                        1 => [
                            '2021-01-20 00:00:00' => [
                                1 => [
                                    'usage' => 1,
                                    'swap_usage' => 0,
                                    'usage_peak' => 1,
                                    'swap_usage_peak' => 0,
                                ],
                                2 => [
                                    'usage' => 2,
                                    'swap_usage' => 0,
                                    'usage_peak' => 2,
                                    'swap_usage_peak' => 0,
                                ],
                                3 => [
                                    'usage' => 3,
                                    'swap_usage' => 0,
                                    'usage_peak' => 3,
                                    'swap_usage_peak' => 0,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
