<?php

namespace App\Services;

use App\Enum\TempCategoryEnum;
use App\Integrations\OpenWeather;
use App\Repositories\ConfigRepository;

class ConfigService
{
    private static $temp_key = "temp";
    private static $increment_percent_key = "increment_percent";

    protected $configRepository;
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }
    public function updateTemp()
    {
        $temp = OpenWeather::getTemperature();

        $key = self::$temp_key;

        $existing = $this->configRepository->checkIfConfigExists($key);

        $this->configRepository->updateConfig($existing, $key, $temp, 'int');

        return $temp;
    }
    public function getTemperature(): int
    {
        $key = self::$temp_key;
        $existing = $this->configRepository->checkIfConfigExists($key);
        if ($existing === null) {
            $temp = $this->updateTemp();
            return $temp;
        }
        return $existing->value;
    }
    public function updateIncrementPercent($increment_percent)
    {
        $key = self::$increment_percent_key;

        $existing = $this->configRepository->checkIfConfigExists($key);

        $this->configRepository->updateConfig($existing, $key, $increment_percent, 'int');

        return $increment_percent;
    }
    public function getIncrementPercent()
    {
        $key = self::$increment_percent_key;
        $existing = $this->configRepository->checkIfConfigExists($key);
        if ($existing === null) {
            $increment_percent = $this->updateIncrementPercent(10);
            return $increment_percent;
        }
        return $existing->value;
    }
    public function checkTempCategory(int $temp)
    {
        if ($temp >= 30) {
            return TempCategoryEnum::COLD->value;
        } else {
            return TempCategoryEnum::HOT->value;
        }
    }
}
