<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminApiController extends Controller
{
    public function getUsers()
    {
        if(!auth()->user()->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $users = User::latest()->get();
        return response()->json(['success' => true, 'data' => $users]);
    }

    public function deleteUser($id)
    {
        if(!auth()->user()->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if(auth()->id() == $id) {
            return response()->json(['success' => false, 'message' => 'Cannot delete your own account'], 400);
        }

        $user = User::find($id);
        if(!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true, 
            'message' => 'User and all associated orders deleted successfully'
        ]);
    }
}
