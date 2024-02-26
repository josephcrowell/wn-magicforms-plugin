<?php

namespace JosephCrowell\MagicForms\Updates;

use Winter\Storm\Support\Facades\Schema;
use Winter\Storm\Database\Updates\Migration;
use JosephCrowell\MagicForms\Models\Record;

class AddUnreadField extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('martin_forms_records', 'unread'))
        {
            // CREATE FIELD SETTING EXISTING RECORDS TO READ
            Schema::table('martin_forms_records', function ($table)
            {
                $table->boolean('unread')->default(0)->after('ip');
            });

            // UPDATE DEFAULT STATE TO UNREAD FOR NEW RECORDS
            Schema::table('martin_forms_records', function ($table)
            {
                $table->boolean('unread')->default(1)->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('martin_forms_records', 'unread'))
        {
            Schema::table('martin_forms_records', function ($table)
            {
                $table->dropColumn('unread');
            });
        }
    }
}
