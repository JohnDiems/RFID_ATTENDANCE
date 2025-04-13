<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\User; 


class LoginController extends Controller
{
    // LOG IN FORM ADMIN/STUDENT/TEACHER
    public function login(Request $request)
    {   
        // VALIDATOR REQUEST REQUIRED
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // FAIL VALIDATOR REQUEST REDIRECT TO LOGIN
        if ($validator->fails()) {
            return redirect()->route('login')
            ->withErrors($validator)
            ->withInput();
        }

        // Attempt authentication with email
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Regenerate session to prevent session fixation
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect based on user role
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
                case 'teacher':
                    return redirect()->intended(route('teacher.dashboard'));
                default: // student or any other role
                    return redirect()->intended(route('student.attendance'));
            }
        }

        // EMAIL ERROR RETURN TO LOGIN
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->route('login')
                // EMAIL ERROR MESSAGE
                ->with('error', 'Email address not found')
                ->withInput();
        }
    
        // PASSWORD ERROR RETURN TO LOGIN
        return redirect()->route('login')
            // PASSWORD ERROR MESSAGE
            ->with('error', 'Incorrect password')
            ->withInput(['email' => $request->email]);
    }

    // LOG IN FORM 
    public function showLoginForm()
    {
        return view('Login.login');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
