<?php

use App\Models\Benefit;
use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageBenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            'Single Plan' => [
                'Allows 1 accounts' => 1,
                "1 in-facility doctor's consultation" => 1,
                '6 online consultations per annum' => 6,
                'Unlimited online prescriptions' => -1,
                '10% discount on labouratory/diagnostics cost' => -1,
                '5% discount on medication & delivery cost' => -1,
                'Personalized health tips' => -1,
                'Annual health check' => 1
            ],
            'Family Plan' => [
                'Allows 2-5 accounts' => 5,
                "1 in-facility doctor's consultation per member" => 1,
                '6 online consultations per annum per member' => 6,
                'Unlimited online prescriptions' => -1,
                '10% discount on labouratory/diagnostics cost' => -1,
                '5% discount on medication & delivery cost' => -1,
                'Personalized health tips' => -1,
                'Annual health check' => 1
            ],
            'Basic Enterprise Plan' => [
                'Allows 10 accounts' => 10,
                "1 offline doctor's consultation per member" => 1,
                '6 online consultations per member per month' => 6,
                'Unlimited online prescriptions' => -1,
                '10% discount on labouratory/diagnostics cost' => -1,
                '5% discount on medication & delivery cost' => -1,
                '1 scheduled home visit for geriatric care per quarter' => 1,
                'Personalized health tips' => -1,
                'Annual health check' => 1
            ],
            'Premium Enterprise Plan' => [
                'Allows 50 accounts' => 50,
                "1 offline doctor's consultation per member" => 1,
                '3 online consultations per member per month' => 6,
                'Unlimited online prescriptions' => -1,
                '10% discount on labouratory/diagnostics cost' => -1,
                '5% discount on medication & delivery cost' => -1,
                'Onsite periodic general health talk' => -1,
                '1 scheduled home visit for geriatric care per quarter' => 1,
                'Personalized health tips' => -1,
                'Annual health check' => 1
            ],
            'Executive Enterprise Plan' => [
                'Allows 100 accounts' => 100,
                "2 offline doctor's consultation per member" => 2,
                '3 online consultations per member per month' => 6,
                'Unlimited online prescriptions' => -1,
                '10% discount on labouratory/diagnostics cost' => -1,
                '5% discount on medication & delivery cost' => -1,
                'Onsite periodic general health talk' => -1,
                '1 scheduled home visit for geriatric care per quarter' => 1,
                'Personalized health tips' => -1,
                'Annual health check' => 1
            ]
        ];

        foreach($plans as $name => $benefits) {
            $plan = Package::where('name', $name)->first();
            foreach($benefits as $benefit => $qty) {
                $ben = Benefit::create([
                    'name' => $benefit,
                    'description' => $benefit,
                    'is_active' => true
                ]);
                $plan->benefits()->attach($ben->id, ['quantity' => $qty]);
            }
        }
    }
}
