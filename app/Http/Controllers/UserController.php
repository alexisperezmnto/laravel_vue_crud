<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request) {
        
        $fields = $request->validate([
            'nombre' => 'required|string|min:2|max:50',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ], [
            'nombre.required' => 'El campo nombre es obligatorio',
            'nombre.max' => 'El campo nombre debe tener entre 2 hasta 50 caracteres',
            'nombre.min' => 'El campo nombre debe tener entre 2 hasta 50 caracteres',
            'email.required' => 'La dirección de correo electrónico es obligatoria',
            'email.email' => 'El correo electrónico no tiene un formato válido',
            'email.unique' => 'La dirección de correo electrónico se encuentra registrada',
            'password.required' => 'El campo password es obligatorio',
            'password.min' => 'El campo password debe tener entre 2 hasta 50 caracteres',
            'password.max' => 'El campo password debe tener entre 6 hasta 30 caracteres'
        ]);

        $user = User::create([
            'nombre' => $fields['nombre'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password'])
        ]);

        $response = [
            'user' => $user,
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ], [
            'email.required' => 'La dirección de correo electrónico es obligatoria',
            'email.email' => 'La dirección de correo electrónico no tiene un formato válido',
            'password.required' => 'El campo password es obligatorio',
        ]);

        //Check email
        $user = User::where('email', '=', $fields['email'])->first();
        
        //Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'error' => 'Email o password incorrecto'
            ], 401);
        };
        
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
