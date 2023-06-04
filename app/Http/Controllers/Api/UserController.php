<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(Request $request)
    {

        // return response()->json([
        //     'status'=>true,
        //     'data'=>'mydata',
        //     'message'=>'new msg',
        // ], 200);
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                //'avatar' => 'required',
                'type' => 'required',
                'open_id' => 'required',
                'name' => 'required',
                'email' => 'required',
                //'password' => 'required|min:6',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            //validated will have all user fields vaules
            //we can save it in db
            $validated = $validateUser -> validated();
            $map =[];
            //email, phone, google, facebook, apple
            $map['type'] = $validated['type'];
            $map['open_id'] = $validated['open_id'];
            $user = User::where($map)->first();
            return response()->json([
                'status'=>true,
                'data'=>'mydata',
                'message'=>'new msg',
            ], 200);
            //whether user has already logged or not
            //empty means doesnt exist then save user in the db for first time
            if(empty($user->id)){
                //this certain user has never been in db
                //out job assign user in db
                //this token is user id
                $validated['token'] = md5(uniqid().rand(10000, 99999));
                //user first time created
                $validated['created_at']= Carbon::now();
                //encrypt pass
                //$validated['password']=Hash::make($validated['password']);
                //returns the id of the row after saving
                $userID = User::insertGetId($validated);
                //user's all information
                $userInfo = User::where('id', '=', $userID)->first();
                //
                $accessToken = $userInfo->createToken(uniqid())->plainTextToken;

                $userInfo->access_token = $accessToken;

                User::where('id', '=', $userID)->update(['access_token'=>$accessToken]);

                return response()->json([
                    'status' => true,
                    'message' => 'User Created Successfully',
                    'data' => $userInfo
                    
                ], 200);
            }
            //user previously logged in
            $accessToken = $user->createToken(uniqid())->plainTextToken;
            $user->access_token = $accessToken;
            User::where('open_id', '=', $validated['open_id'])->update(['access_token'=>$accessToken]);

            // $user = User::create([
            //     'name' => $request->name,
            //     'email' => $request->email,
            //     'password' => Hash::make($request->password)
            // ]);

            return response()->json([
                'status' => true,
                'message' => 'User logged in Successfully',
                'token' => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}