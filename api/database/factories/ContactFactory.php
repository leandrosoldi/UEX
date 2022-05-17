<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /** 
    * Get a new Faker instance.
    *
    * @return \Faker\Generator
    */
    public function withFaker()
    {
        return \Faker\Factory::create('pt_BR');
    }


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cpf' => $this->faker->cpf(false),
            'nome' => $this->faker->name(),
            'telefone' => $this->faker->phone(false),
            'cep' => $this->faker->postcode(false),
            'logradouro' => $this->faker->streetName(),
            'numero' => $this->faker->buildingNumber,
            'bairro' => $this->faker->citySuffix,
            'localidade' => $this->faker->city,
            'uf' => $this->faker->stateAbbr,
            'latitude' => $this->faker->latitude(-25.300,-25.999),
            'longitude' => $this->faker->longitude(-49.200,-49.999),
        ];
    }
}
