<?php

namespace App\Console\Commands;

use App\Models\Provider;
use App\Models\providerDistrict;
use App\Models\providerProvinces;
use App\Models\providerWard;
use App\Services\ViettelPostService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
                    'provider_id' => $providerId,
                    'provider_province_code' => $district['PROVINCE_ID']
                ]
            );
            if ($result) {
                $this->info('Imported District: ' . $district['DISTRICT_NAME']);
            } else {
                $this->warn('Failed: ' . $district['DISTRICT_NAME']);
            }
        }

        foreach ($wardData as $ward) {
            try {
                $result = providerWard::updateOrCreate(
                    ['provider_ward_code' => $ward['WARDS_ID']],
                    [
                        'provider_ward_name' => $ward['WARDS_NAME'],
                        'provider_id' => $providerId,
                        'provider_district_code' => $ward['DISTRICT_ID'],
                    ]
                );

                $this->info('Imported Ward: ' . $ward['WARDS_NAME']);
            } catch (\Illuminate\Database\QueryException $e) {
                $this->warn('Skipped Ward (FK constraint): ' . $ward['WARDS_NAME']);
                Log::warning('Failed to import ward due to FK constraint', [
                    'ward' => $ward,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        $pythonPath = $isWin
            ? base_path(env('PYTHON_VENV') . '/Scripts/python.exe')
            : base_path(env('PYTHON_VENV') . '/bin/python3');

        $scriptPath = base_path('app/Tools/AddressConstraint/database.py');
        $output = shell_exec("\"$pythonPath\" \"$scriptPath\"");

        echo $output;
    }
}
