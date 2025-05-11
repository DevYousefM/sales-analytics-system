<?php

namespace App\Repositories;

use App\Integrations\OpenWeather;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConfigRepository
{
    public function checkIfConfigExists($key)
    {
        $existing = DB::selectOne('SELECT * FROM configs WHERE `key` = ?', [$key]);
        return $existing;
    }

    public function updateConfig($record, $key, $value, $datatype)
    {
        if ($record == null) {
            DB::insert('INSERT INTO configs (`key`, `value`,`datatype`) VALUES (?, ?, ?)', [$key, $value, $datatype]);
        } else {
            DB::update('UPDATE configs SET `value` = ? WHERE `key` = ?', [$value, $key]);
        }
    }
}
