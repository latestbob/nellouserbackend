<?php

use Illuminate\Support\Str;
use App\Models\PharmacyDrug;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PharmacyDrug::whereNotNull('name')->delete();
        $fileContent = Storage::disk('local')->get('drugs.json');
        $drugs = json_decode($fileContent, true);

        echo gettype($drugs);
        
        foreach($drugs as $drug) {
            PharmacyDrug::create([
                "drug_id" => $drug['id'],
                'name' => trim($drug['name']),
                'vendor_id' => 1,
                //'brand' => trim($drug['Brand']),
                'category' => trim($drug['category']),
                //'image'   => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1579802365/pill_ce6l0g.png',
                'price' => (double) str_replace(',', '', $drug['price']),
                'uuid' => Str::uuid()->toString()
            ]);
        }
    }
}
