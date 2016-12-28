<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\EventPlanner\ValidationData;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

abstract class RegisterController extends Controller
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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
    	/*
    	 * Get the validation messages for each input in the register form so they can be loaded and
    	 * shown by the frontend validation. 
    	 */
    	$validationData = new ValidationData();
    	$validationRules = $validationData->getData( 'register' );
    	
    	$messages = [];
    	foreach( $validationRules as $name => $ruleString ){    		
    		$messageTemplate = [ 'attribute' => $name ];    		
    		$rules = explode( '|', $ruleString );
    		foreach( $rules as $rule ){
    			if( strpos( $rule, ':' ) !== false ){
    				$rulename = explode( ':', $rule )[ 0 ];
    			} else {
    				$rulename = $rule;
    			}    			
    			$messageName = "validation.$rulename";
    			switch( $rulename ){
    				case 'unique':
    					continue;    				
    				case 'min':
    					$messageTemplate = $messageTemplate + ['min' => explode( ':', $rule )[ 1 ] ];
    				case 'max':
    					$messageTemplate = $messageTemplate + [ 'max' => explode( ':', $rule )[ 1 ] ];
    					$messageName .= ".string";
    			}
    			$messages[ $name ][ $rulename ] = trans( $messageName, $messageTemplate );
    		}
    	}
    	
    	$viewData = [
    		'validationMessages' => json_encode( $messages )
    	];
    	return view('auth.register', $viewData);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
    	$validationData = new ValidationData();
        return Validator::make($data, $validationData->getData('register'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
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
