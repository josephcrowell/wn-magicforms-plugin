<?php
namespace JosephCrowell\MagicForms\Tests\Classes;

use Carbon\Carbon;
use JosephCrowell\MagicForms\Classes\GDPR;
use JosephCrowell\MagicForms\Models\Record;
use JosephCrowell\MagicForms\Models\Settings;
use System\Classes\PluginManager;
use System\Tests\Bootstrap\PluginTestCase;

class GDPRTest extends PluginTestCase
{
    private $_record;

    public function setUp(): void
    {
        parent::setUp();
        PluginManager::instance()->bootAll(true);
        Record::unguard();
        $this->_record = Record::create([
            'group' => 'test group',
            'created_at' => Carbon::now()->subDays(20),
            'updated_at' => Carbon::now()->subDays(20),
        ]);
    }

    /**
     * @testdox GDPR cleanup disabled
     */
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

    /**
     * @testdox GDPR cleanup with older records
     */
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

    /**
     * @testdox GDPR cleanup without older records
     */
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

    /**
     * @testdox GDPR cleanup with invalid days parameter
     */
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
