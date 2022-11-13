<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Voter;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voter>
 */
class VoterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ic' => '123456078967',
            'name' => fake()->name(),
            'gender' => 'male',
            'race' => 'malay',
            'mobileNumber' => '+012345679945',
            'email' => 'abc@example.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'district' => 'Bayan Lepas',
            'state' => 'Penang',
            'postcode' => fake()->postcode(),
            'address' => fake()->address(),
            'parliamentalConstituency' => 'P053 Balik Pulau',
            'stateConstituency' => 'N38 Bayan Lepas',
            'parlimentVoteStatus' => '0',
            'stateVoteStatus' => '0',
            'is_parlimentvote_verified' => '0',
            'is_statevote_verified' => '0',
            'userPrivilege' => '0',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
