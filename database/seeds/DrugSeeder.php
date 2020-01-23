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
                'image'   => 'https://res.cloudinary.com/dq1zd0mue/image/upload/v1579802365/pill_ce6l0g.png',
                'price' => (double) $drug['Price'],
                'uuid' => Str::uuid()->toString()
            ]);
        }
    }
}
