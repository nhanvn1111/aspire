<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class LoanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => 10000000,
            'repayment_frequency' => 12,
            'interest_rate' => 9,
            'arrangement_fee' => 1800000,
            'amount_need_repayment'=> 12700000,
            'amount_repayment'=> 12700000,
            'current_month_repayment'=> 1058333,
            'status'=> 'LOAN_STATUS_NONE',
            'deleted'=> 0,

        ];
    }

}
