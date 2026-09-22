<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function storeRole(Request $request)
    {
        $role = $request->json('is_admin', 'client');

        $request->session()->put('is_admin', $role);

        return response()->json([
            'status'  => 'success',
            'message' => 'El rol ' . $role . ' s\'ha emmagatzemat correctament a la sessió de Laravel.'
        ], 200);
    }

    public function showProfileView()
    {
        return view('profile');
    }

}
