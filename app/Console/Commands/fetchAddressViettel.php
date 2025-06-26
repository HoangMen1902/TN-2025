<?php

namespace App\Console\Commands;

use App\Models\Provider;
use App\Models\providerDistrict;
use App\Models\providerProvinces;
use App\Models\providerWard;
use App\Services\ViettelPostService;
use Illuminate\Console\Command;

use function PHPUnit\Framework\isNull;

class fetchAddressViettel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'viettel:fetch-address-viettel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(ViettelPostService $vtps)
    {
        $provinceData = $vtps->fetchProvince();
        $districtData = $vtps->fetchDistrict();
        $wardData = $vtps->fetchWard();


        $providerId = Provider::where('provider_name', '=', 'Viettel Post')->first();
        if (is_null($providerId)) {
            Provider::updateOrCreate([
                'provider_name' => 'Viettel Post',
            ], [
                'provider_status' => 'active'
            ]);
        } else {
            $providerId = $providerId->id;
        }

        foreach ($provinceData as $province) {
            $result = providerProvinces::updateOrCreate(
                ['provider_province_code' => $province['PROVINCE_ID']],
                [
                    'provider_province_name' => $province['PROVINCE_NAME'],
                    'provider_id' => $providerId
                ]
            );
            if ($result) {
                $this->info('Imported Province: ' . $province['PROVINCE_NAME']);
            } else {
                $this->warn('Failed: ' . $province['PROVINCE_NAME']);
            }
        }

        foreach ($districtData as $district) {
            $result = providerDistrict::updateOrCreate(
                ['provider_district_code' => $district['DISTRICT_ID']],
                [
                    'provider_district_name' => $district['DISTRICT_NAME'],
                    'provider_id' => $providerId
                ]
            );
            if ($result) {
                $this->info('Imported District: ' . $district['DISTRICT_NAME']);
            } else {
                $this->warn('Failed: ' . $district['DISTRICT_NAME']);
            }
        }

        foreach ($wardData as $ward) {
            $result = providerWard::updateOrCreate(
                ['provider_ward_code' => $ward['WARDS_ID']],
                [
                    'provider_ward_name' => $ward['WARDS_NAME'],
                    'provider_id' => $providerId
                ]
            );
            if ($result) {
                $this->info('Imported Ward: ' . $ward['WARDS_NAME']);
            } else {
                $this->warn('Failed: ' . $ward['WARDS_NAME']);
            }
        }
        $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        $pythonPath = $isWin
            ? base_path('.venv_clip/Scripts/python.exe')
            : base_path('.venv_clip/bin/python3');

        $scriptPath = base_path('app/Tools/AddressConstraint/database.py');
        $output = shell_exec("\"$pythonPath\" \"$scriptPath\"");

        echo $output;
    }
}
