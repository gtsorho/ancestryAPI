<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;


class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $Request)
    {
        $fields = $Request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);
        $user =  $fields['password'] = bcrypt($fields['password']);
        $user = User::create($fields);

        
        $token = $user->createToken('myapptoken') -> plainTextToken;
        
        $response = [
            'user' => $user,
            'token' => $token   
        ];
        
        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function login(request $Request)
    {
        // return $Request->image;

        $fields = $Request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response()->json(['message'=> 'invalid credentials'], 401);
        }
        $token = $user->createToken('myapptoken') -> plainTextToken;
        
        $response = [
            'user' => $user,
            'token' => $token   
        ];

        return response()->json($response, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $fields = $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|string|unique:users,email',
            'password' => 'nullable|string|confirmed'
        ]);

        if($Request->has('password')){
            $fields['password'] = bcrypt($fields['password']);
        }

        $user = $user->update($fields);
        return response()->json(['message'=>$user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $deleted = $user->delete();

        return response()->json($deleted, 200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json(['message'=>'logged out']);
    }

    public function test(Request $request){
        return $request;
    }

// reset password----------------------------------------------------------------

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        }
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function reset(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message'=> 'Password reset successful'
            ]);
        }

        return response([
            'message'=> __($status)
        ], 500);

    }

    // // // **************************** Email Verificatation ***************************************
    // public function sendVerificationEmail(Request $request)
    // {
    //     if ($request->user()->hasVerifiedEmail()) {
    //         return response()->json([
    //             'message' => 'Already Verified'
    //         ]);
    //     }

    //     $request->user()->sendEmailVerificationNotification();

    //     return response()->json(['status' => 'verification-link-sent']);
    // }

    // public function verify(EmailVerificationRequest $request)
    // {
    //     if ($request->user()->hasVerifiedEmail()) {
    //         return response()->json([
    //             'message' => 'Email already verified'
    //         ]);
    //     }

    //     if ($request->user()->markEmailAsVerified()) {
    //         event(new Verified($request->user()));
    //     }

    //     return response()->json([
    //         'message'=>'Email has been verified'
    //     ]);
    // }

}



