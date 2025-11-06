<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RemoveMetricsAverageTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->removeMetrics = $container->make("dhope0000\LXDClient\Tools\Instances\Metrics\RemoveMetrics");
    }

    public function testAverageGrouped(): void
    {
        $x = [
            1 => [
                'default' => [
                    'test' => [
                        1 => [
                            '2021-01-19 23:55:00' => [
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
                                4 => [
                                    'usage' => 4,
                                    'swap_usage' => 0,
                                    'usage_peak' => 4,
                                    'swap_usage_peak' => 0,
                                ],
                                5 => [
                                    'usage' => 5,
                                    'swap_usage' => 0,
                                    'usage_peak' => 5,
                                    'swap_usage_peak' => 0,
                                ],
                            ],
                            '2021-01-20 00:00:00' => [
                                // This metric should NOT be deleted &
                                // should be EXCLUDED from the average because
                                // there is 6 items in this array and this one
                                // is an average because they are in date ASC
                                // order and metrics shouldn't be inserted at
                                // 00 seconds were as the average metrics are.
                                6 => [
                                    'usage' => 3,
                                    'swap_usage' => 0,
                                    'usage_peak' => 3,
                                    'swap_usage_peak' => 0,
                                ],
                                7 => [
                                    'usage' => 1,
                                    'swap_usage' => 0,
                                    'usage_peak' => 1,
                                    'swap_usage_peak' => 0,
                                ],
                                8 => [
                                    'usage' => 2,
                                    'swap_usage' => 0,
                                    'usage_peak' => 2,
                                    'swap_usage_peak' => 0,
                                ],
                                9 => [
                                    'usage' => 3,
                                    'swap_usage' => 0,
                                    'usage_peak' => 3,
                                    'swap_usage_peak' => 0,
                                ],
                                10 => [
                                    'usage' => 4,
                                    'swap_usage' => 0,
                                    'usage_peak' => 4,
                                    'swap_usage_peak' => 0,
                                ],
                                11 => [
                                    'usage' => 5,
                                    'swap_usage' => 0,
                                    'usage_peak' => 5,
                                    'swap_usage_peak' => 0,
                                ],
                            ],
                            '2021-01-20 00:05:00' => [
                                4 => [
                                    'usage' => 1,
                                    'swap_usage' => 0,
                                    'usage_peak' => 1,
                                    'swap_usage_peak' => 0,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $r = $this->removeMetrics->averageGrouped($x);

        $this->assertEquals($r, [
            'toDelete' => [1, 2, 3, 4, 5, 7, 8, 9, 10, 11],
            'toInsert' => [
                [
                    0 => '2021-01-20 00:00:00',
                    1 => 1,
                    2 => 'test',
                    3 => 1,
                    4 => '{"usage":3,"swap_usage":0,"usage_peak":3,"swap_usage_peak":0}',
                ],
                [
                    0 => '2021-01-20 00:05:00',
                    1 => 1,
                    2 => 'test',
                    3 => 1,
                    4 => '{"usage":3,"swap_usage":0,"usage_peak":3,"swap_usage_peak":0}',
                ],
            ],
        ]);
    }
}
