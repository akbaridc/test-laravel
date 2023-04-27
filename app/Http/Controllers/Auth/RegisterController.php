<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;
use App\Mail\DemoMail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register_custom(Request $request)
    {

        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
        ]);

        DB::beginTransaction();

        try {
            $token6Digit = mt_rand(100000, 999999);

            $mailData = [
                'title' => 'Kode Verifikasi',
                'body' => $token6Digit
            ];

            Mail::to($request->email)->send(new DemoMail($mailData));

            $chkToken = DB::table('register_token_confirmation as rtc')->where('rtc.email', $request->email)->first();


            if ($chkToken == null) {
                DB::table('register_token_confirmation')->insert([
                    'email' => $request->email,
                    'nama' => $request->name,
                    'password' => Hash::make($request->password),
                    'token' => $token6Digit,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                DB::table('register_token_confirmation')
                    ->where('id', $chkToken->id)
                    ->update([
                        'nama' => $request->name,
                        'password' => Hash::make($request->password),
                        'token' => $token6Digit,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }

            DB::commit();

            return redirect()->route('register.confirmation', ['email' => $request->email])->with('success', "register has ben successfuly, please check your email to verfication code");
        } catch (\Throwable $th) {

            DB::rollback();
            return redirect()->back()->with('failed', "register failed, please try again");
            //throw $th;
        }

        // dd($request->all());
    }

    public function confirmasi()
    {
        $email = $_GET['email'];
        return view('auth.confirmation', compact('email'));
    }

    public function confirmasi_process(Request $request)
    {
        $chkData = DB::table('register_token_confirmation as rtc')
            ->where('rtc.email', $request->email)
            ->where('rtc.token', $request->code)
            ->first();

        DB::beginTransaction();


        try {
            if ($chkData == null) {
                return redirect()->back()->with('failed', "Code verifivation not same record in database");
            } else {
                $user = User::create([
                    'name' => $chkData->nama,
                    'email' => $chkData->email,
                    'password' => $chkData->password,
                    'is_active' => 0
                ]);

                $role = Role::where('name', 'User')->first();

                $user->assignRole([$role->id]);

                DB::table('register_token_confirmation')
                    ->where('email', $request->email)
                    ->where('token', $request->code)->delete();

                DB::commit();

                return redirect()->route('login')->with('success', "registrasi Successfully");
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('failed', $th);
        }
    }
}
