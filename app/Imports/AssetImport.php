<?php

namespace App\Imports;

use App\Models\Asset;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class AssetImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Require at least a code and name to create an asset
            if (!isset($row['asset_code']) || !isset($row['asset_name'])) {
                continue;
            }

            // Handle potential excel date formats
            $tanggal = null;
            if (isset($row['acquisition_date'])) {
                if (is_numeric($row['acquisition_date'])) {
                    try {
                        $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['acquisition_date']);
                    } catch (\Exception $e) {
                        $tanggal = now();
                    }
                } else {
                    try {
                        $tanggal = Carbon::parse($row['acquisition_date']);
                    } catch (\Exception $e) {
                        $tanggal = now();
                    }
                }
            }

            // Handle Category
            $categoryName = $row['category'] ?? 'Lainnya';
            $category = \App\Models\Category::firstOrCreate(['category_name' => $categoryName]);

            // Handle Location
            $locationName = $row['location'] ?? 'Gudang Utama';
            $location = \App\Models\Location::firstOrCreate(['location_name' => $locationName]);

            // Handle User (Penanggung Jawab)
            $userName = $row['person_in_charge'] ?? null;
            $userId = null;
            if ($userName) {
                $user = \App\Models\User::where('name', $userName)->first();
                if ($user) {
                    $userId = $user->id;
                }
            }

            // Update or create the record in the database
            Asset::updateOrCreate(
                ['asset_code' => $row['asset_code']], // Atribut pencarian (unik)
                [
                    'asset_name'         => $row['asset_name'],
                    'category_id'       => $category->id,
                    'condition'           => $row['condition'] ?? 'Baik',
                    'status'            => strtolower($row['status'] ?? 'active'),
                    'location_id'       => $location->id,
                    'acquisition_cost'   => is_numeric($row['acquisition_cost'] ?? null) ? $row['acquisition_cost'] : 0,
                    'acquisition_date' => $tanggal ?? now(),
                    'user_id'           => $userId,
                    'description'         => $row['description'] ?? null,
                ]
            );
        }
    }
}
