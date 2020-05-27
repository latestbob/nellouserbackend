<?php

use App\Models\DrugCategory;
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
        $categories = [
            'Supplements',
            'Medicines',
            'Antibiotics',
            'Wellness Products',
            'Cold/Allergies',
            'Medical Devices',
            'Essential Oils'
        ];

        foreach ($categories as $category) {
            DrugCategory::create(['name' => $category]);
        }

        PharmacyDrug::whereNotNull('name')->update(['status' => false]);
        $fileContent = Storage::disk('local')->get('drugs.json');
        $drugs = json_decode($fileContent, true);

        echo gettype($drugs);

        foreach($drugs as $drug) {
            PharmacyDrug::create([
                "drug_id" => $drug['id'],
                'name' => trim($drug['name']),
                'vendor_id' => 1,
                'brand' => trim($drug['brand']),
                'category_id' => trim($drug['category_id']),
                'dosage_type' => trim($drug['dosage_type']),
                'image'   => mt_rand(0, 1) == 1 ? 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1579802365/pill_ce6l0g.png' : null,
                'price' => (double) str_replace(',', '', $drug['price']),
                'require_prescription' => $drug['require_prescription'] == 'YES' ? true : false,
                'uuid' => Str::uuid()->toString()
            ]);
        }
    }
}
