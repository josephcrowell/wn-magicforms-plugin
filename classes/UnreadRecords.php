<?php
namespace JosephCrowell\MagicForms\Classes;

use Schema;
use JosephCrowell\MagicForms\Models\Record;

class UnreadRecords
{
    public static function getTotal()
    {
        if (Schema::hasTable('josephcrowell_magicforms_records'))
        {
            $unread = Record::where('unread', 1)->count();
        }

        return (isset($unread) && $unread > 0) ? $unread : null;
    }
}
