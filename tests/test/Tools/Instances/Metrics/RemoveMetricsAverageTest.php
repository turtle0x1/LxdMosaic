<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RemoveMetricsAverageTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->removeMetrics = $container->make("dhope0000\LXDClient\Tools\Instances\Metrics\RemoveMetrics");
    }

    public function test_averageGrouped() :void
    {
        $x = [
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
                            '2021-01-20 00:05:00' => [
                                4 => [
                                    'usage' => 1,
                                    'swap_usage' => 0,
                                    'usage_peak' => 1,
                                    'swap_usage_peak' => 0,
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $r = $this->removeMetrics->averageGrouped($x);

        $this->assertEquals($r, [
            'toDelete' => [
                0 => 1,
                1 => 2,
                2 => 3,
            ],
            'toInsert' => [
                [
                  0 => '2021-01-20 00:05:00',
                  1 => 1,
                  2 => 'test',
                  3 => 1,
                  4 => '{"usage":2,"swap_usage":0,"usage_peak":2,"swap_usage_peak":0}',
                ],
            ],
        ]);
    }
}
