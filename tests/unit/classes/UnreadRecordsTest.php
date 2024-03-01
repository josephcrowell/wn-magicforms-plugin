<?php
namespace JosephCrowell\MagicForms\Tests\Classes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use JosephCrowell\MagicForms\Classes\UnreadRecords;
use JosephCrowell\MagicForms\Models\Record;
use System\Classes\PluginManager;
use System\Tests\Bootstrap\PluginTestCase;

class UnreadRecordsTest extends PluginTestCase
{

    use RefreshDatabase;

    private $_record;

    public function setUp()
    {
        parent::setUp();
        PluginManager::instance()->bootAll(true);
        Record::unguard();
    }

    /**
     * @testdox Get total unread records with unread records
     */
    public function testGetTotal()
    {
        $record = Record::create([
            'group' => 'test group',
        ]);
        $unread = new UnreadRecords();
        $this->assertEquals(1, $record->id);
        $this->assertEquals('test group', $record->group);
        $this->assertEquals(1, $unread->getTotal());
    }

    /**
     * @testdox Get total unread records without unread records
     */
    public function testGetTotalNoUnread()
    {
        $record = Record::create([
            'group' => 'test group',
            'unread' => 0,
        ]);
        $unread = new UnreadRecords();
        $this->assertEquals(1, $record->id);
        $this->assertEquals('test group', $record->group);
        $this->assertEquals(0, $unread->getTotal());
        $this->assertNull($unread->getTotal());
    }

}
