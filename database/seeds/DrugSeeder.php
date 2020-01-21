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
        $fileContent = Storage::disk('local')->get('drugs.json');
        $drugs = json_decode($fileContent, true);
        
        foreach($drugs as $drug) {
            PharmacyDrug::create([
                'name' => trim($drug['Name']),
                'brand' => trim($drug['Brand']),
                'category' => trim($drug['Category']),
                'price' => (double) $drug['Price'],
                'uuid' => Str::uuid()->toString()
            ]);
        }
    }
}
