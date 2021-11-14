<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
class Loan extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'user_id',
        'repayment_frequency',
        'interest_rate',
        'arrangement_fee',
        'amount_repayment',
        'amount_need_repayment',
        'current_month_repayment',
        'status',
        'deleted'
    ];
}