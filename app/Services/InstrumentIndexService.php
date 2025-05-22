<?php

namespace App\Services;

use App\Models\Area;
use App\Models\InstrumentIndex;
use App\Models\Service;

class InstrumentIndexService
{
    public static function generateLoopNo($dev,$areaId, $serviceId): ?string

    {
            if(!$areaId || !$serviceId){
                return null;
            }
            $excludedLetters = ['I','O','S','Z'];
            $area = Area::findOrFail($areaId) ?? "";
            $service = Service::findOrFail($serviceId) ?? "";



            $baseCode = "{$dev}{$area->code}{$service->code}";

            // Check if the exact code already exists
            $exists = InstrumentIndex::where('code', $baseCode)->exists();

            if (!$exists) {
                return $baseCode; // First entry
            }

            // Get existing suffixes for this base
            $similarCodes = InstrumentIndex::where('code', 'like', "$baseCode%")
                ->pluck('code')
                ->map(function ($code) use ($baseCode) {
                    return str_replace("{$baseCode}", '', $code);
                })->toArray();

            // Find next available letter (A-Z) skipping excluded letters
            foreach (range('A', 'Z') as $char) {
                if (in_array($char, $excludedLetters)) continue;
                if (!in_array($char, $similarCodes)) {
                    return "{$baseCode}{$char}";
                }
            }

            return '';
    }
}
