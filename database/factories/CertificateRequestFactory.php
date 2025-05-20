<?php

namespace Database\Factories;

use App\Models\Certificate_request;
use App\Models\resident_info;
use App\Models\Certificate_list;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Certificate_request::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $resident = resident_info::inRandomOrder()->first();
        $certificate = Certificate_list::inRandomOrder()->first();
        
        return [
            'resident_id' => $resident->resident_id,
            'name' => $resident->firstname . ' ' . $resident->lastname,
            'description' => $this->faker->sentence(),
            'age' => $resident->age,
            'gender' => $resident->gender,
            'paid' => $this->faker->randomElement(['Yes', 'No']),
            'price' => $certificate->price,
            'cert_id' => $certificate->certificate_list_id,
            'request_type' => $certificate->certificate_type
        ];
    }
}
