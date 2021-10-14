<?php

namespace App\Http\Controllers;

use App\Models\User as UserModel;
use Illuminate\Http\Request;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\UserResource;

class User extends Controller
{
    private $status;
    private $response;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string|Unique:users,phone',
            'password' => 'required|string',
            'document_number' => 'required|string|size:10',
        ]);
        $errors = $validator->errors();

        if ($errors->messages()) {
            return new ErrorResource($errors);
        }
        else {
            $user = new UserModel();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone = $request->phone;
            $user->password = $request->password;
            $user->document_number = $request->document_number;
            $user->save();

            return response()->json([], 204);
        }
    }

    public function login(Request $request, Faker $faker)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);
        $errors = $validator->errors();

        if ($errors->messages()) {
            $this->status = 422;
            $this->response = new ErrorResource($errors);
        }
        else {
            $user = UserModel::where('password', $request->password)->where('phone', $request->phone)->first();

            if($user){
                $token = $faker->uuid();
                $user->api_token = $token;
                $user->save();
                $this->status = 200;
                $this->response = new UserResource($user);;
            }else{
                $this->status = 401;
                $this->response = [
                    'error' => [
                        'code' => $this->status,
                        'message' => 'Unauthorized',
                        'errors' => [
                            'phone' => ['phone or password incorrect']
                        ]
                    ]
                ];
            }
        }
        return response()->json($this->response, $this->status);
    }
}
