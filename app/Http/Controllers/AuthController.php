<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
//ana li ltht

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
   // Show login form
   public function showLoginForm()
   {
       return view('login');
   }

   // Handle login request
   public function login(Request $request)
   {
       // Validate the request data
       $validator = Validator::make($request->all(), [
           'email' => 'required|email',
           'password' => 'required|min:6'
       ]);
   
       if ($validator->fails()) {
           return redirect()->back()->withErrors($validator)->withInput();
       }
   
       $credentials = $request->only('email', 'password');
   
       if (Auth::attempt($credentials, $request->filled('remember'))) {
           $user = Auth::user();
   
           // Check if the user account is blocked
           if (!$user->status) {
               Auth::logout(); // Log the user out if their account is blocked
               return redirect()->route('account.blocked');
           }
   
           // Redirect to the intended page or a default page
           return redirect()->intended('/welcome');
       }
   
       return redirect()->back()->withErrors(['email' => 'Invalid email or password'])->withInput();
   }
   
   // Show registration form
   public function showRegistrationForm()
   {
       return view('register');
   }
     public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'auto_entrepreneur_number' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail(Auth::user()->id);
        $user->f_name = $request->input('name');
        $user->auto_entrepreneur_number = $request->input('auto_entrepreneur_number');
        $user->save();

        return redirect()->route('setting')->with('success', 'Settings updated successfully.');
    }

   
    // Handle registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'auto_entrepreneur_number' => 'nullable|string', // Validate the new field
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // Create a new user
        $user = User::create([
            'f_name' => $request->f_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null, // Set to null initially
            'auto_entrepreneur_number' => $request->auto_entrepreneur_number
          
        ]);

        // Optionally, log the user in after registration
      return view('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); 

        return redirect()->back();
    }
    public function setting(){
        $user=User::findOrFail(Auth::user()->id);
        return view('chef.setting', compact('user'));
    
        
    }
    public function changeStatus($id){
        $user = User::findOrFail($id);
        $user->status = $user->status == 1 ? 0 : 1;  // Toggle status
        $user->save();
        return redirect()->back();
    }
}
