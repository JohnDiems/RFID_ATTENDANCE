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
            'username' => 'required',
            'password' => 'required',

        ]);

        // FAIL VALIDATOR REQUEST REDIRECT TO LOGIN
        if ($validator->fails()) {
            return redirect()->route('login')
            ->withErrors($validator)
            ->withInput();
        }

        // CREDENTIALS REQUEST USERNAME, PASSWORD
        $credentials = $request->only('username', 'password');

        // CREDENTIALS ROLES ADMIN -> ADMIN / TEACHER -> REPORTS / STUDENT -> LOG //
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            //ADMIN ROLE
            if ($user->role === 'admin') {
                return redirect()->route('Dashboard');
                // TEACHER ROLE
            } else if ($user->role === 'teacher') {
                return redirect()->route('TeacherDashboard');
                // STUDENT ROLE
            } else {
                return redirect()->route('StudentAttendanceRecord');
            }
            
        }

        // USERNAME ERROR RETURN TO LOGIN
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return redirect()->route('login')
                // USERNAME ERROR MESSAGE
                ->with('error', 'Username does not exist')
                ->withInput();
        }
    
        // PASSWORD ERROR RETURN TO LOGIN
        return redirect()->route('login')
            // PASSWORD ERROR MESSAGE
            ->with('error', 'Incorrect password')
            ->withInput();
    }

    // LOG IN FORM 
    public function showLoginForm()
    {
        return view('Login.login');
    }




   
}
