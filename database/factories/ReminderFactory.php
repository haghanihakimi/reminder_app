<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reminder>
 */
class ReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $pid = Str::uuid()->getHex()->toString();

        return [
            'pid' => $pid,
            'user_id' => User::all()->random()->id,
            'reminder_title' => substr($this->faker->paragraph, 0,300),
            'reminder_descriptions' => substr($this->faker->paragraph, 0,850),
            'due_date' => $this->faker->dateTimeBetween('2022-05-01', '2022-07-25')
        ];
    }
}
