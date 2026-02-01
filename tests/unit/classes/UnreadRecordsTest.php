<?php
namespace JosephCrowell\MagicForms\Tests\Classes;

use JosephCrowell\MagicForms\Classes\UnreadRecords;
use JosephCrowell\MagicForms\Models\Record;
use System\Classes\PluginManager;
use System\Tests\Bootstrap\PluginTestCase;

class UnreadRecordsTest extends PluginTestCase
{
    private $_record;

    public function setUp(): void
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
        $this->assertEquals(1, $record->id);
        $this->assertEquals('test group', $record->group);
        $this->assertEquals(1, UnreadRecords::getTotal());
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
        $this->assertEquals(1, $record->id);
        $this->assertEquals('test group', $record->group);
        $this->assertNull(UnreadRecords::getTotal());
    }
}
