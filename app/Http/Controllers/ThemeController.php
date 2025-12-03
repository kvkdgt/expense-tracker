<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'theme' => ['required', 'in:light,dark'],
        ]);

        $user = Auth::user();
        $user->theme_preference = $request->theme;
        $user->save();

        return response()->json([
            'success' => true,
            'theme' => $user->theme_preference,
        ]);
    }
}






