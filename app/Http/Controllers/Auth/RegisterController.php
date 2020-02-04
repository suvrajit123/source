<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Subscriber;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Role;
use App\RoleUser;
use App\Events\UserActivationEvent;
use Newsletter;
use Session;


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
    protected $redirectTo = '/register';

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
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username'      => ['required', 'string', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user =  User::create([
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'],
            'email'         => $data['email'],
            'username'      => $data['username'],
            'password'      => Hash::make($data['password']),
            'validate'      => '0'
        ]);

        if($user)
        {
            //Newsletter::subscribe($data['email'],[],'registeredUsersList');
            RoleUser::create(['role_id' => '2', 'user_id' => $user->id]);
            if (isset($data['subscribe'])) {
                try {
                    Subscriber::updateOrCreate(['email'=>$data['email'], ['status' => '1']]);
                } catch (\Exception $e) {
                    
                }
            }
            return $user;
        }
        else
        {
            abort(500,'Something went wrong');
        }
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //$this->guard()->login($user);
        event(new UserActivationEvent($user));
        Session::flash('reg_success','A confirmation e-mail has been sent to your e-mail address. Validate the e-mail before login!');
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
