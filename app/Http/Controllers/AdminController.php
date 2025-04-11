<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\attendances;
use App\Models\profiles;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Card;
use App\Models\Lunch;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Throwable;

class AdminController extends Controller
{

    public function LunchAttendance()
    {
        if(Auth::check()){
            $user = Auth::user();
            $lunchs = Lunch::All();
        }

        return view('Admin.LunchAttendance', compact('user', 'lunchs'));
    }

    public function WhiteCard()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $profiles = profiles::all();
            $cards = Card::all();
        }
        return view('Admin.WhiteCard', compact('user', 'profiles', 'cards'));
    }

    public function WhiteCardiD($id)
    {
      if (Auth::check()) {
        $user = Auth::user();
        $profiles = profiles::findOrFail($id);
      }
      return view('Admin.WhiteCard', compact('user', 'profiles'));
    }

    public function WhiteCardDelete($id)
    {
        DB::delete('delete from cards where id = ?', [$id]);
        return redirect()->back()->with('success', 'User Card has success delete.');

    }

    public function WhiteCardSchedule(Request $request, $id){
      try{
            $this->validate($request, [
                'FullName' => 'required',
                'Lunch_in' => 'required',
                'Lunch_out' => 'required',
            ]);

            $card = Card::findOrFail($id);
            $card->lunch_in = $request->input('Lunch_in');
            $card->lunch_out = $request->input('Lunch_out');
            $card->save();

            $profiles = profiles::findOrFail($card->profile_id);
            $profiles->FullName =  $request->input('FullName');
            $profiles->save();

            return redirect()->route('WhiteCard')->with('success', 'White Card update successfully.');
      }catch(Throwable $e){
        return $e;
      }
    }

    public function WhiteCardEdit($id){
        if(Auth::check()){
            $user = Auth::user();
            $cards = Card::findOrFail($id);
        }

        return view('Admin.AdminEdit.WhiteCardEdit', compact('user', 'cards', 'id'));
    }

    public function CardStore(Request $request)
    {
        $this->validate($request,[
            'profile_id' => 'required',
            'Lunch_in' => 'required',
            'Lunch_out' => 'required',
        ]);

        // Check if the profile_id exists in the profiles table
        $existingProfile = Profiles::find($request->input('profile_id'));
        if (!$existingProfile) {
            return redirect()->route('WhiteCard')->with('error', 'Profile does not exist.');
        }

        // Check if the user has already been registered
        $existingCard = Card::where('profile_id', $request->input('profile_id'))->first();
        if ($existingCard) {
            return redirect()->route('WhiteCard')->with('error', 'User has already been registered.');
        }

        $card = new Card();
        $card->profile_id = $request->input('profile_id');
        $card->lunch_in = $request->input('Lunch_in');
        $card->lunch_out = $request->input('Lunch_out');
        $card->save();

        return redirect()->route('WhiteCard')->with('success', 'Register White Card successfully.');
    }

    public function deleteSchedule($id)
    {
        DB::delete('delete from schedule where id = ?',[$id]);

        return redirect()->back()->with('success', 'Delete schedule successfully.');
    }


    public function ScheduleUpdates(Request $request, $id)
    {
        $this->validate($request, [
            'EventSchedule' => 'required|string|max:255',
            'EventTimein' => 'required|string|max:255',
            'EventTimeout' => 'required|string|max:255',
            'EventDate' => 'required'
        ]);

        $schedule = Schedule::findOrFail($id);


        $schedule->EventSchedule = $request->input('EventSchedule');
        $schedule->EventTimein = $request->input('EventTimein');
        $schedule->EventTimeout = $request->input('EventTimeout');
        $schedule->EventDate = $request->input('EventDate');
        $schedule->save();

        return redirect()->route('Schedule')->with('success', 'Schedule updated successfully.');

    }


    public function scheduleEdit($id) {
        if(Auth::check()){
            $user = Auth::user();
            $schedule = Schedule::findOrFail($id);
        }

        return view('Admin.AdminEdit.ScheduleEdit', compact('user', 'id', 'schedule'));
    }

    public function StoreSchedule(Request $request)
    {
        $this->validate($request, [
            'EventSchedule' => 'required|string|max:255',
            // 'EventTimein' => 'required|string|max:255',
            'EventTimeout' => 'required|string|max:255',
            'EventDate' => 'required'
        ]);

        // $existingSchedule = Schedule::first();

        // if ($existingSchedule) {
        //     return redirect()->back()->with('error', 'An Schedule already exists. You cannot create another one.');
        // }

        $schedule = new Schedule();

        if ($request->has('EventTimein')) {
            $schedule->EventTimein = $request->input('EventTimein');
        } else {
            $schedule->EventTimeout = null;
        }

        $schedule->EventSchedule = $request->input('EventSchedule');
        $schedule->EventTimein = $request->input('EventTimein');
        $schedule->EventTimeout = $request->input('EventTimeout');
        $schedule->EventDate = $request->input('EventDate');
        $schedule->save();

        return redirect()->back()->with('success', 'Schedule added successfully');
    }


    public function Schedule(){

        if(Auth::check()) {
            $user = Auth::user();
            $profiles = profiles::all();
            $schedule = Schedule::all();
        }

        return view('Admin.Schedule', compact('user','profiles','schedule'));
    }



    // ADMIN STUDENT PROFILE
    public function studentlist()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $profiles = profiles::all();
        }

        return view('Admin.studentlist', compact('profiles','user'));
    }

    // ADMIN STUDENT PROFILES MODEL STORE DATA WITH VALIDATION
    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'StudentRFID' => 'required|string|max:10|unique:profiles,StudentRFID',
            'FullName' => 'required|string|max:255|unique:profiles,FullName',
            'Gender' => 'required|string|max:255',
            'Parent' => 'required|string|max:255',
            'EmergencyAddress' => 'required|string|max:255',
            'EmergencyNumber' => 'required|string|max:255',
            'CompleteAddress' => 'required|string|max:255',
            'ContactNumber' => 'required|string|max:255',
            'EmailAddress' => 'nullable|email|max:255',
            'YearLevel' => 'required|string|max:255',
            'Course' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store the uploaded photo
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('upload', $filename);
            $validatedData['photo'] = 'upload/' . $filename;
        }

        // Create a new user
        $user = new User();
        $user->name = $request->FullName;
        $user->username = $request->StudentRFID;
        $user->password = bcrypt($request->StudentRFID);
        $user->role = "user";
        $user->save();

        // Create a new profile and associate it with the user
        $profiles = new profiles($validatedData);
        $profiles->user_id = $user->id;
        $profiles->save();

        return redirect()->back()->with('success', 'Profile successfully created.');
    }

    // ADMIN STUDENT ATTENDANCE RECORD
    public function monitoring()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $attendances = attendances::all();
        }
        return view('Admin.monitoring', compact('attendances','user'));
    }

    // ADMIN STUDENT SECTION LIST
    public function SectionList()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $profiles = profiles::all();
        }
        return view('Admin.SectionList', compact('profiles','user'));
    }

    // ADMIN DASHBOARD
    public function Dashboard()
    {


        if (Auth::check()) {
            $user = Auth::user();
            $profiles = profiles::all();
            $users = User::all();
            $profilesCount = profiles::count();
            $currentMonth = Carbon::now()->startOfMonth();
            $UserCount = User::count();
            $time_in = attendances::whereNotNull('time_in')->count(); // Count time_in
            $time_out = attendances::whereNotNull('time_out')->count(); // Count time_out
        }


        return view('Admin.Dashboard', compact('profiles', 'profilesCount', 'UserCount','users','user','time_in','time_out','currentMonth'));
    }


    // ADMIN REGISTRATION ACCOUNT USER/TEACHER
    public function RegistrationForm()
    {
        if (Auth::check()) {
            $user  = Auth::user();
            $users = User::all();
        }
        return view('Login.register', compact('users','user'));

    }

    // ADMIN REGISTRATION ACCOUNT POST REQUEST
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:user,teacher,admin',
        ]);

        // Create a new user
        $user = new User();
        $user->name= $request->name;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->save();


        // Fetch all registered users from the database
        $users = User::all();

        return redirect()->back()->with('success', 'Registration successful!');
    }

    // ADMIN DELETE USER ACCOUNT TEACHER ACCOUNT (PROFILE, RECORD, SECTION)
    public function UserDelete($id)
    {
        DB::delete('delete from users where id = ?',[$id]);

        return redirect()->back()->with('success', 'Delete updated successfully.');
    }

    // ADMIN STUDENTLIST PROFILES EDIT
    public function edit($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $profiles = Profiles::findOrFail($id);
        }

        return view('Admin.AdminEdit.studentEdit', compact('profiles', 'id', 'user'));
    }

    // ADMIN STUDNET LIST PROFILE UPDATE POST REQUEST
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'StudentRFID' => 'required|string|max:10|unique:profiles,StudentRFID',
            'StudentRFID' => 'required|string|max:10|unique:profiles,StudentRFID,' . $id,
            'FullName' => 'required|string|max:255',
            'Gender' => 'required|string|max:255',
            'EmailAddress' => 'nullable|email|max:255',
            'ContactNumber' => 'required|string|max:255',
            'CompleteAddress' => 'required|string|max:255',
            'Course' => 'required|string|max:255',
            'YearLevel' => 'required|string|max:255',
            'Parent' => 'required|string|max:255',
            'EmergencyAddress' => 'required|string|max:255',
            'EmergencyNumber' => 'required|string|max:255',
        ]);

        $profiles = profiles::findOrFail($id);


        // Store the uploaded photo if available
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('upload', $filename);
            $profiles->photo = 'upload/' . $filename;
        }


        // Update the profile data based on the form input
        $profiles->StudentRFID = $request->input('StudentRFID');
        $profiles->FullName = $request->input('FullName');
        $profiles->Gender = $request->input('Gender');
        $profiles->EmailAddress = $request->input('EmailAddress');
        $profiles->ContactNumber = $request->input('ContactNumber');
        $profiles->CompleteAddress = $request->input('CompleteAddress');
        $profiles->Course = $request->input('Course');
        $profiles->YearLevel = $request->input('YearLevel');
        $profiles->Parent = $request->input('Parent');
        $profiles->EmergencyAddress = $request->input('EmergencyAddress');
        $profiles->EmergencyNumber = $request->input('EmergencyNumber');
        $profiles->save();

        return redirect()->route('studentlist')->with('success', 'Profile updated successfully.');
    }



    // ADMIN STUDNET PROFILE CHANGE STATUS ACTIVE TO INACTIVE
    public function changeStatus(Request $request, $id)
    {
        $profiles = Profiles::find($id);

        if (!$profiles) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        // Update the status based on the request data
        $newStatus = $request->input('status');
        $profiles->Status = $newStatus;
        $profiles->save();

        return response()->json(['message' => 'Status updated successfully']);
    }

    // ADMIN REGISTRATION ACCOUNT EDIT ACCOUNT
    public function edits($id) {
        if (Auth::check()) {
            $user = Auth::user();
            $user = User::findOrFail($id);
        }
        return view('Admin.AdminEdit.RegisterEdit', compact('id', 'user'));
    }

    // ADMIN REGISTRATION ACCOUNT USER/TEACHER UPDATE ACCOUNT POST REQUEST
    public function updates(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->password = bcrypt($request->input('password'));
        $user->role = $request->input('role');
        $user->save();

        return redirect()->route('register')->with('success', 'Profile updated successfully.');
    }

    //ADMIN CHANGE PASSWORD GET RETRIVE DATA
    public function AdminChange()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $profiles = $user->profiles;
            $attendances = $user->attendances;
        }
        return view('Admin.AdminChange', compact('profiles','user','attendances'));
    }

    // ADMIN CHANGE PASSWORD PROCESSING DATA REQUEST
    public function ChangeAdmin(Request $request)
    {
        // AUTH USER
        $user = Auth::user();

        // REQUEST VALIDATOR
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }],

            //NEW PASSWORD REQUIRED
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // ENCRYPT PASSWORD HASH
        $updateUser = User::findOrFail($user->id);
        $updateUser->password = Hash::make($request->new_password);
        $updateUser->save();

        // SUCCESS MESSAGE
        return redirect()->back()->with('success', 'Password changed successfully.');

    }

    // ADMIN ACTIVITY MONITOR DEVICE USE/ LAST LOGIN TIME / CURRENT LOGIN TIME
    public function AdminActivity()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $profiles = $user->profiles;
            $attendances = $user->attendances;

        }
        return view('Admin.AdminActivity', compact('user', 'profiles', 'attendances'));

    }

    // ADMIN LOG OUT POST REQUEST
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }

}
