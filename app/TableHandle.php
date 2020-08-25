<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TableHandle extends Model
{
    /**
     * @param $fromTable
     * @param $toTable
     * @return bool
     */
    public static function copyTableStructure($existTable,$newTable)
    {
        return DB::statement('create table if not exists '.$newTable.' like '.$existTable);
    }
    /**
     * @param $table
     * @return bool
     */
    public static function tableExist($table)
    {
        return Schema::hasTable($table);
    }
}
