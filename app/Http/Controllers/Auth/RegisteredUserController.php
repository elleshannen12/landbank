<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('registration');
    }

    public function check(Request $request): JsonResponse
    {
        $data = $request->validate([
            'field' => ['required', 'in:atm_account_no,email,username'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $value = $data['field'] === 'email'
            ? strtolower($data['value'])
            : $data['value'];

        return response()->json([
            'taken' => User::where($data['field'], $value)->exists(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
        public function store(Request $request): RedirectResponse
{
    $request->validate([
        // Step 4: Login
        'name'     => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'alpha_num', 'min:6', 'max:20', 'unique:users,username'],
        'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', 'string', 'min:7', 'max:15'],

        // Step 1: Account
        'atm_account_no'       => ['required', 'string', 'max:20', 'unique:users,atm_account_no'],
        'atm_pin'              => ['required', 'string', 'size:4'],
        'jai'                  => ['required', 'in:0,1,2'],
        'enable_fund_transfer' => ['nullable', 'in:0,1'],

        // Step 2: Personal
        'first_name'         => ['required', 'string', 'max:80'],
        'middle_name'        => ['nullable', 'string', 'max:80'],
        'last_name'          => ['required', 'string', 'max:80'],
        'mother_first_name'  => ['required', 'string', 'max:80'],
        'mother_middle_name' => ['nullable', 'string', 'max:80'],
        'mother_last_name'   => ['required', 'string', 'max:80'],
        'gender'             => ['required', 'in:Male,Female'],
        'civil_status'       => ['required', 'string', 'max:20'],
        'spouse_first_name'  => ['nullable', 'string', 'max:80'],
        'spouse_middle_name' => ['nullable', 'string', 'max:80'],
        'spouse_last_name'   => ['nullable', 'string', 'max:80'],
        'birthday'           => ['required', 'date'],
        'tin'                => ['nullable', 'string', 'max:20'],

        // Step 2: Address
        'street'   => ['required', 'string', 'max:255'],
        'barangay' => ['required', 'string', 'max:100'],
        'city'     => ['required', 'string', 'max:100'],
        'province' => ['required', 'string', 'max:100'],
        'zip'      => ['nullable', 'string', 'max:10'],
        'country'  => ['required', 'string', 'max:100'],

        // Step 2: Contact
        'alt_email'      => ['nullable', 'email', 'max:255'],
        'mobile_country' => ['required', 'string', 'max:10'],
        'mobile_number'  => ['required', 'string', 'max:20'],
        'home_tel'       => ['nullable', 'string', 'max:20'],
        'office_tel'     => ['nullable', 'string', 'max:20'],

        // Step 3: Security 
        'sec_q1' => ['required', 'string', 'max:255'],
        'sec_a1' => ['required', 'string', 'max:255'],
        'sec_q2' => ['required', 'string', 'max:255'],
        'sec_a2' => ['required', 'string', 'max:255'],
        'sec_q3' => ['required', 'string', 'max:255'],
        'sec_a3' => ['required', 'string', 'max:255'],
], [
        'atm_account_no.unique' => 'Account number already registered.',
        'email.unique'          => 'Email already registered.',
        'username.unique'       => 'iAccess ID is already taken.',
    ]);

    $user = User::create([
        'name'     => $request->name,
        'username' => $request->username,
        'email'    => $request->email,
        'password' => Hash::make($request->password),

        'atm_account_no'       => $request->atm_account_no,
        'atm_pin'              => Hash::make($request->atm_pin),
        'jai'                  => $request->jai,
        'enable_fund_transfer' => $request->enable_fund_transfer ?? 0,

        'first_name'         => $request->first_name,
        'middle_name'        => $request->middle_name,
        'last_name'          => $request->last_name,
        'mother_first_name'  => $request->mother_first_name,
        'mother_middle_name' => $request->mother_middle_name,
        'mother_last_name'   => $request->mother_last_name,
        'gender'             => $request->gender,
        'civil_status'       => $request->civil_status,
        'spouse_first_name'  => $request->spouse_first_name,
        'spouse_middle_name' => $request->spouse_middle_name,
        'spouse_last_name'   => $request->spouse_last_name,
        'birthday'           => $request->birthday,
        'tin'                => $request->tin,

        'street'   => $request->street,
        'barangay' => $request->barangay,
        'city'     => $request->city,
        'province' => $request->province,
        'zip'      => $request->zip,
        'country'  => $request->country,

        'alt_email'      => $request->alt_email,
        'mobile_country' => $request->mobile_country,
        'mobile'         => $request->mobile_number,  
        'home_tel'       => $request->home_tel,
        'office_tel'     => $request->office_tel,

        'sec_q1' => $request->sec_q1,
        'sec_a1' => Hash::make($request->sec_a1),
        'sec_q2' => $request->sec_q2,
        'sec_a2' => Hash::make($request->sec_a2),
        'sec_q3' => $request->sec_q3,
        'sec_a3' => Hash::make($request->sec_a3),
    ]);

    event(new Registered($user));

    return redirect()->route('login')->with('registered', true);
}

}
