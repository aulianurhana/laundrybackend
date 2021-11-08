<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'nama' => 'required',
			'username' => 'required',
			'password' => 'required|string|min:6',
			'role' => 'required'
		]);

		if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
		}

		$user = new User();
		$user->nama 	= $request->nama;
		$user->username = $request->username;
		$user->password = Hash::make($request->password);
		$user->role 	= $request->role;

		$user->save();

		$token = JWTAuth::fromUser($user);

        $data = User::where('username','=', $request->username)->first();

        return response()->json(['message' => 'Berhasil menambah user baru', 'data' => $data]);
	}    
}
