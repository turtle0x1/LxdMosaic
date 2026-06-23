<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \dhope0000\LXDClient\Model\Database\Database;
use \dhope0000\LXDClient\Model\Vulnerabilities\Os\InsertOperatingSystem;
use \dhope0000\LXDClient\Model\Vulnerabilities\Vulnerability\InsertVulnerability;
use \dhope0000\LXDClient\Model\Vulnerabilities\Cve\InsertCve;
use \dhope0000\LXDClient\Model\Vulnerabilities\Package\InsertVulnerablePackage;
use \dhope0000\LXDClient\Model\Hosts\SoftwareAssets\InsertSoftwareAssetsSnapshot;
use \dhope0000\LXDClient\Tools\Vulnerabilities\Scan\GetImpactedInstancesOverview;

/**
 * Tests that GetImpactedInstancesOverview correctly flags instances
 * when the software assets snapshot contains vulnerable packages.
 */
final class GetImpactedInstancesOverviewTest extends TestCase
{
    private \DI\Container $container;

    private Database $dbWrapper;

    private InsertOperatingSystem $insertOs;
    private InsertVulnerability $insertVuln;
    private InsertCve $insertCve;
    private InsertVulnerablePackage $insertPkg;
    private InsertSoftwareAssetsSnapshot $insertSnapshot;
    private GetImpactedInstancesOverview $tool;

    #[\Override]
    protected function setUp(): void
    {
        $this->container = (new \DI\ContainerBuilder)->useAttributes(true)->build();

        // Swap our DB wrapper so all DI-resolved classes share one connection + transaction.
        $this->dbWrapper = new Database();
        $this->dbWrapper->beginTransaction();

        // Rebind Database so every autowired class gets our instance.
        $this->container->set(Database::class, $this->dbWrapper);

        // Resolve all models once up front.
        $this->insertOs = $this->container->make(InsertOperatingSystem::class);
        $this->insertVuln = $this->container->make(InsertVulnerability::class);
        $this->insertCve = $this->container->make(InsertCve::class);
        $this->insertPkg = $this->container->make(InsertVulnerablePackage::class);
        $this->insertSnapshot = $this->container->make(InsertSoftwareAssetsSnapshot::class);

        // Seed: a fake OS
        $osId = $this->insertOs->insert('ubuntu', '22.04', 'jammy');

        // Seed: a vulnerability with fixed_version = 2.0.0 for "test-package"
        $vId = $this->insertVuln->insert(
            "CVE-2099-12345 - test-package buffer overflow",
            'USN-TEST-9999',
            'https://example.com/usn-9999',
            'High',
            date('Y-m-d H:i:s'),
            'A test vulnerability for unit testing.',
            $osId
        );

        $this->insertCve->insert(
            'CVE-2099-12345',
            $vId,
            'critical',
            9.8,
            'Critical',
            'CVSS:3.1/AV:N/AC:L/PR:N/UI:N/S:U/C:H/I:H/A:H',
            date('Y-m-d')
        );

        $this->insertPkg->insert('test-package', $vId, '2.0.0');

        // Seed: initial snapshot with vulnerable version (below 2.0.0)
        $this->insertSnapshot->insert(
            new \DateTimeImmutable(date('Y-m-d')),
            $this->buildSnapshotData('test-package', '1.0.0')
        );

        // Pre-resolve the tool under test from DI.
        $this->tool = $this->container->make(GetImpactedInstancesOverview::class);
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->dbWrapper->rollbackTransaction();
    }

    /**
     * Snapshot has apt package v1.0.0 < fixed v2.0.0 → instance is flagged.
     */
    public function testFlagsInstanceWithVulnerablePackage(): void
    {
        $result = $this->tool->get();

        $this->assertTrue($result['has_snapshot'], 'Snapshot should exist');
        $this->assertGreaterThan(0, $result['impacted_instances_count'], 'At least one instance should be impacted');
        $this->assertGreaterThan(0, $result['total_impacted_packages'], 'At least one package should be flagged');
        $this->assertCount(1, $result['instance_details'], 'Should have exactly one impacted instance');

        $instance = $result['instance_details'][0];
        $this->assertEquals('my-instance', $instance['instance_name']);
        $this->assertEquals('default', $instance['project']);
        $this->assertGreaterThanOrEqual(1, $instance['impacted_packages_count']);
    }

    /**
     * Overwrite snapshot with patched version ≥ fixed → no impact.
     */
    public function testDoesNotFlagInstanceWhenPatched(): void
    {
        $this->insertSnapshot->insert(
            new \DateTimeImmutable(date('Y-m-d')),
            $this->buildSnapshotData('test-package', '3.0.0')
        );

        $result = $this->tool->get();

        $this->assertTrue($result['has_snapshot'], 'Snapshot should exist');
        $this->assertEquals(0, $result['impacted_instances_count'], 'No instances should be impacted');
        $this->assertEquals(0, $result['total_impacted_packages']);
        $this->assertEmpty($result['instance_details']);
    }

    /**
     * Extreme-patched version (999.0.0) also produces zero impacts.
     */
    public function testReturnsZeroImpactForExtremeVersion(): void
    {
        $this->insertSnapshot->insert(
            new \DateTimeImmutable(date('Y-m-d')),
            $this->buildSnapshotData('test-package', '999.0.0')
        );

        $result = $this->tool->get();

        $this->assertTrue($result['has_snapshot'], 'Snapshot should exist');
        $this->assertEquals(0, $result['impacted_instances_count']);
        $this->assertEquals(0, $result['total_impacted_packages']);
        $this->assertEmpty($result['instance_details']);
    }
    /**
     * Build snapshot data matching the structure the cron job produces.
     */
    private function buildSnapshotData(string $packageName, string $packageVersion): array
    {
        return [
            1 => [
                'default' => [
                    'my-instance' => [
                        [
                            'manager'      => 'apt',
                            'name'         => $packageName,
                            'version'      => $packageVersion,
                            'rev'          => null,
                            'tracking'     => null,
                            'publisher'    => null,
                            'notes'        => null,
                            'architecture' => 'amd64',
                            'status'       => ['installed', 'local'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
