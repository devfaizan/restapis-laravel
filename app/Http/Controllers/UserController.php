<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //
    public function checkConnection(Request $request)
    {
        $isConnected = true;
        return response()->json([
            'connected' => $isConnected,
        ]);
    }
    public function create(Request $request)
    {
        try {
            $achaData = $request->validate([
                "email" => ["required", Rule::unique("users", "email"), 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
                "name" => ["required", 'regex:/^[a-zA-Z ]+$/'],
                "password" => ["required", 'min:8', 'max:16'],
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        }

        try {
            $user = User::create($achaData);
            return response()->json(['message' => 'User created successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create user'], 500);
        }
    }
    public function getUserData(Request $request)
    {
        $users = User::all();
        return response()->json($users);
    }

    public function editUserData(Request $request, $id)
    {
        try {
            $editInput = $request->validate([
                "name" => ["required", 'regex:/^[a-zA-Z ]+$/'],
            ]);
        } catch (ValidationException $ve) {
            return response()->json(['error' => $ve->errors()], 400);
        }

        try {
            $user = User::find($id);
            if (!$user) {
                abort(404, 'User not found');
            }
            $user->update($editInput);
            return response()->json(['message' => 'Updated']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to update'], 500);
        }
    }
    public function deleteByUserID(Request $request)
    {
        $userID = $request->input('id');
        try {
            User::destroy($userID);
            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete the user'], 500);
        }

    }

}
