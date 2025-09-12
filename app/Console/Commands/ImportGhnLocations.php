<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Services\GhnService;

class ImportGhnLocations extends Command
{
    protected $signature = 'ghn:import-locations';
    protected $description = 'Import provinces, districts and wards from GHN API';

    protected $ghnService;

    public function __construct(GhnService $ghnService)
    {
        parent::__construct();
        $this->ghnService = $ghnService;
    }

    public function handle()
    {
        $this->info('Start importing provinces...');
        $provinces = $this->ghnService->getProvinces();

        if (empty($provinces)) {
            $this->error('No provinces data received from API.');
            return 1;
        }

        foreach ($provinces as $provinceData) {
            if (!isset($provinceData['ProvinceID'], $provinceData['ProvinceName'])) {
                $this->warn('Province data is invalid: missing ProvinceID or ProvinceName.');
                continue;
            }

            $province = Province::updateOrCreate(
                ['province_code' => $provinceData['ProvinceID']],
                ['name' => $provinceData['ProvinceName']]
            );

            $this->info("Imported Province: {$province->name}");

            // Import districts for this province
            $districts = $this->ghnService->getDistricts($provinceData['ProvinceID']);

            if (empty($districts)) {
                $this->warn("No districts found for province {$province->name}");
                continue;
            }

            foreach ($districts as $districtData) {
                if (!isset($districtData['DistrictID'], $districtData['DistrictName'])) {
                    $this->warn('District data is invalid: missing DistrictID or DistrictName.');
                    continue;
                }

                $district = District::updateOrCreate(
                    ['district_code' => $districtData['DistrictID']],
                    [
                        'name' => $districtData['DistrictName'],
                        'province_id' => $province->id
                    ]
                );

                $this->info("  Imported District: {$district->name}");

                // Import wards for this district
                $wards = $this->ghnService->getWards($districtData['DistrictID']);

                if (empty($wards)) {
                    $this->warn("No wards found for district {$district->name}");
                    continue;
                }

                foreach ($wards as $wardData) {
                    if (!isset($wardData['WardCode'], $wardData['WardName'])) {
                        $this->warn('Ward data is invalid: missing WardCode or WardName.');
                        continue;
                    }

                    $ward = Ward::updateOrCreate(
                        ['ward_code' => $wardData['WardCode']],
                        [
                            'name' => $wardData['WardName'],
                            'district_id' => $district->id
                        ]
                    );

                    $this->info("    Imported Ward: {$ward->name}");
                }
            }
        }

        $this->info('Finished importing all locations.');

        return 0;
    }
}
