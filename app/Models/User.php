<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // Login
        'name',
        'username',
        'email',
        'password',

        // Step 1: Account
        'atm_account_no',
        'atm_pin',
        'jai',
        'enable_fund_transfer',

        // Step 2: Personal
        'first_name',
        'middle_name',
        'last_name',
        'mother_first_name',
        'mother_middle_name',
        'mother_last_name',
        'spouse_first_name',
        'spouse_middle_name',
        'spouse_last_name',
        'gender',
        'civil_status',
        'birthday',
        'tin',

        // Step 2: Address
        'street',
        'barangay',
        'city',
        'province',
        'zip',
        'country',

        // Step 2: Contact
        'alt_email',
        'mobile_country',
        'mobile',
        'home_tel',
        'office_tel',

        // Step 3: Security
        'sec_q1',
        'sec_a1',
        'sec_q2',
        'sec_a2',
        'sec_q3',
        'sec_a3',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'atm_pin',
        'sec_a1',
        'sec_a2',
        'sec_a3',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Masked ATM account number, e.g. ******3123
     * Use in Blade as: {{ auth()->user()->masked_atm_account_no }}
     */
    public function getMaskedAtmAccountNoAttribute(): ?string
    {
        $no = $this->atm_account_no;

        return $no
            ? str_repeat('*', max(strlen($no) - 4, 0)) . substr($no, -4)
            : null;
    }
}