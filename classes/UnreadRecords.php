<?php

namespace Martin\Forms\Classes;

use Schema;
use Martin\Forms\Models\Record;

class UnreadRecords
{
    public static function getTotal()
    {
        if (Schema::hasTable('martin_forms_records')) {
            $unread = Record::where('unread', 1)->count();
        }

        return (isset($unread) && $unread > 0) ? $unread : null;
    }
}
