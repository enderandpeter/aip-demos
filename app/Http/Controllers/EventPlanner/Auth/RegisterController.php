<?php

namespace App\Http\Controllers\EventPlanner\Auth;

use App\Http\Controllers\Auth\RegisterController as SiteRegisterController;
use App\Http\Controllers\EventPlanner\ValidatesEventPlannerRequests;
use App\Models\EventPlanner\User;
use App\Models\EventPlanner\ValidationData;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends SiteRegisterController
{
    use ValidatesEventPlannerRequests;

    /**
     * The authentication guard that should be used.
     */
    protected function guard()
    {
        return Auth::guard('eventplanner');
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->redirectTo = route('event-planner');
    }

    /**
     * Show the application registration form.
     *
     * @return Application|Factory|View|Response
     */
    public function showRegistrationForm()
    {
        /*
    	 * Get the validation messages for each input in the register form so they can be loaded and
    	 * shown by the frontend validation.
    	 */
        $messages = $this->getValidationMessagesArray('register');

        $viewData = [
            'validationMessages' => json_encode($messages),
        ];

        return view('auth.register', $viewData);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'regex' => trans('validation.specialchars'),
        ];
        $validationData = new ValidationData;

        return Validator::make($data, $validationData->getData('register'), $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
