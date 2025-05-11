<?php

namespace App\Repositories;

use App\Integrations\OpenWeather;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConfigRepository
{
    private static $temp_key = "temp";
    private static $increment_percent_key = "increment_percent";

    private function checkIfRecordExists($key)
    {
        $existing = DB::selectOne('SELECT * FROM configs WHERE `key` = ?', [$key]);
        return $existing;
    }
    public function updateTemp()
    {
        $temp = OpenWeather::getTemperature();

        $key = self::$temp_key;

        $existing = $this->checkIfRecordExists($key);

        $this->updateConfig($existing, $key, $temp, 'int');

        return $temp;
    }
    public function getTemperature(): int
    {
        $key = self::$temp_key;
        $existing = $this->checkIfRecordExists($key);
        if ($existing === null) {
            $temp = $this->updateTemp();
            return $temp;
        }
        return $existing->value;
    }
    public function updateIncrementPercent($increment_percent)
    {
        $key = self::$increment_percent_key;

        $existing = $this->checkIfRecordExists($key);

        $this->updateConfig($existing, $key, $increment_percent, 'int');

        return $increment_percent;
    }
    private function updateConfig($record, $key, $value, $datatype)
    {
        if ($record == null) {
            DB::insert('INSERT INTO configs (`key`, `value`,`datatype`) VALUES (?, ?, ?)', [$key, $value, $datatype]);
        } else {
            DB::update('UPDATE configs SET `value` = ? WHERE `key` = ?', [$value, $key]);
        }
    }
    public function getIncrementPercent()
    {
        $key = self::$increment_percent_key;
        $existing = $this->checkIfRecordExists($key);
        if ($existing === null) {
            $increment_percent = $this->updateIncrementPercent(10);
            return $increment_percent;
        }
        return $existing->value;
    }
}
