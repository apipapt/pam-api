<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request){
        $rule = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $this->validate($request, $rule);

        $client = \DB::table('oauth_clients')
                    ->where('password_client', true)
                    ->first();

        // $form = [
        //     'grant_type' => 'password',
        //     'client_id' => $request->client_id,
        //     'client_secret' => $request->client_secret,
        //     'username' => $request->email,
        //     'password' => $request->password,
        // ];

        $form = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request->email,
            'password' => $request->password,
        ];

        $request_auth = Request::create('/oauth/token', 'POST', $form);
        $response_auth = app()->handle($request_auth);

        // Get the data from the response
        $data = json_decode($response_auth->getContent());

        // if login process was error
        $response_status = $response_auth->getStatusCode();
        if ($response_status != 200) {
            // if the login was incorrect
            if(!empty($data->error) && $data->error == 'invalid_grant') {
                throw ValidationException::withMessages(['email' => 'Incorrect Email or Password.']);
            }
            // if something else error
            else {
                return response()->json($data, $response_status);
            }
        }

        // Format the final response in a desirable format
        return response()->json([
            'message' => 'Logged in, Redirecting....',
            'data' => $data,
        ]);
    }

    public function logout(){
        if(auth()->guard('api')->check()){
            // auth()->guard('api')->user()->token()->revoke();
            // auth()->guard('api')->user()->token()->delete();
            foreach (auth()->guard('api')->user()->tokens as $token) {
                // $token->revoke();
                $token->delete();
            }
        }

        return response()->json(['message' => 'Logged out, Redirecting....']);
    }

    public function userProfile(){
        $user = auth()->user();
        
        $data = (object) [];
        $data->id = $user->id;
        $data->name = $user->name;
        $data->email = $user->email;
        $data->email_verified_at = $user->email_verified_at;
        $data->photo = $user->photo;
        $data->created_at = $user->created_at;
        $data->updated_at = $user->updated_at;
        $data->roles = $user->getRoleNames();
        $data->permissions = $user->getAllPermissions()->pluck('name');

        // $user_group = UserGroup::where('user_id', $user->id)->first();
        // if($user_group){
        //     $data->group = $user_group->group;
        //     $data->group_id = $user_group->group_id;
        // }

        return response()->json($data);
    }

    
}
