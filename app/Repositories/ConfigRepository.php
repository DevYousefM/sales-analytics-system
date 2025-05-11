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

        $this->updateConfig($existing, $key, $temp, 'int');

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
    public function updateIncrementPercent($increment_percent)
    {
        $key = "increment_percent";

        $existing = $this->checkIfRecordExists($key);

        $this->updateConfig($existing, $key, $increment_percent, 'int');

        return $increment_percent;
    }
    private function updateConfig($record, $key, $value, $datatype)
    {
        if ($record === null) {
            DB::insert('INSERT INTO configs (`key`, `value`,`datatype`) VALUES (?, ?, ?)', [$key, $value, $datatype]);
        } else {
            DB::update('UPDATE configs SET `value` = ? WHERE `key` = ?', [1, $key]);
        }
    }
}
