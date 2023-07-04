<?php

namespace JosephCrowell\MagicForms\Updates;

use Winter\Storm\Database\Updates\Migration;
use Schema;

class RenameTables extends Migration
{
    const TABLES = ["records"];

    public function up()
    {
        foreach (self::TABLES as $table) {
            $from = "martin_forms_" . $table;
            $to = "josephcrowell_magicforms_" . $table;

            if (Schema::hasTable($from) && !Schema::hasTable($to)) {
                Schema::rename($from, $to);
            }
        }
    }

    public function down()
    {
        foreach (self::TABLES as $table) {
            $from = "josephcrowell_magicforms_" . $table;
            $to = "martin_forms_" . $table;

            if (Schema::hasTable($from) && !Schema::hasTable($to)) {
                Schema::rename($from, $to);
            }
        }
    }
}
