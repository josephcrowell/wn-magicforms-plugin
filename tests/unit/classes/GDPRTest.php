<?php
namespace JosephCrowell\MagicForms\Tests\Classes;

use Carbon\Carbon;
use JosephCrowell\MagicForms\Classes\GDPR;
use JosephCrowell\MagicForms\Models\Record;
use JosephCrowell\MagicForms\Models\Settings;
use PHPUnit\Framework\Attributes\TestDox;
use System\Behaviors\SettingsModel;
use System\Classes\PluginManager;
use System\Tests\Bootstrap\PluginTestCase;

class GDPRTest extends PluginTestCase
{
    private $_record;

    public function setUp(): void
    {
        parent::setUp();
        // The settings instance is cached statically and outlives each test's database.
        SettingsModel::clearInternalCache();
        PluginManager::instance()->bootAll(true);
        Record::unguard();
        $this->_record = Record::create([
            'group' => 'test group',
            'created_at' => Carbon::now()->subDays(20),
            'updated_at' => Carbon::now()->subDays(20),
        ]);
    }

    #[TestDox('GDPR cleanup disabled')]
    public function testCleanRecordsDisabled()
    {
        Settings::set([
            'gdpr_enable' => false,
            'gdpr_days' => 10,
        ]);
        GDPR::cleanRecords();
        $totalRecords = Record::all()->count();
        $this->assertEquals(1, $totalRecords);
    }

    #[TestDox('GDPR cleanup with older records')]
    public function testCleanRecordsWithOlder()
    {
        Settings::set([
            'gdpr_enable' => true,
            'gdpr_days' => 10,
        ]);
        GDPR::cleanRecords();
        $totalRecords = Record::all()->count();
        $this->assertEquals(0, $totalRecords);
    }

    #[TestDox('GDPR cleanup without older records')]
    public function testCleanRecordsWithoutOlder()
    {
        Settings::set([
            'gdpr_enable' => true,
            'gdpr_days' => 30,
        ]);
        GDPR::cleanRecords();
        $totalRecords = Record::all()->count();
        $this->assertEquals(1, $totalRecords);
    }

    #[TestDox('GDPR cleanup with invalid days parameter')]
    public function testCleanRecordsInvalidDays()
    {
        $this->expectException(\Winter\Storm\Database\ModelException::class);
        $this->expectExceptionMessage('GDPR');

        Settings::set([
            'gdpr_enable' => true,
            'gdpr_days' => 'INVALID',
        ]);
    }
}
