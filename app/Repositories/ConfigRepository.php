<?php

namespace App\Repositories;

use App\Integrations\OpenWeather;
use Illuminate\Support\Facades\DB;

class ConfigRepository
{
    private function checkIfRecordExists($key)
    {
        $existing = DB::selectOne('SELECT * FROM configs WHERE `key` = ?', [$key]);
        return $existing;
    }
    public function updateTemp()
    {
        $temp = OpenWeather::getTemperature();

        $key = "temp";

        $existing = $this->checkIfRecordExists($key);

        if ($existing === null) {
            DB::insert('INSERT INTO configs (`key`, `value`,`datatype`) VALUES (?, ?, ?)', [$key, $temp, 'int']);
        } else {
            DB::update('UPDATE configs SET `value` = ? WHERE `key` = ?', [$temp, $key]);
        }

        return $temp;
    }
    public function getTemperature(): int
    {
        $key = "temp";
        $existing = $this->checkIfRecordExists($key);
        if ($existing === null) {
            $temp = $this->updateTemp();
            return $temp;
        }
        return $existing->value;
    }
}
