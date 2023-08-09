<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function profile(User $user) {
        return view('profile-posts', ['username' => $user->username, 'posts' => $user->posts()->latest()->get(), 'postCount' => $user->posts()->count()]);
    }

    public function logout()
    {
        auth()->logout(); // verify correct user account want to logout
        // https://stackoverflow.com/questions/43585416/how-to-logout-and-redirect-to-login-page-using-laravel-5-4 
        return redirect('/')->with('success', 'You are now logged out!');
    }

    public function showCorrectHomepage()
    {
        if (auth()->check()) {
            // check: true/false
            return view('homepage-feed');
        }
        else {
            return view('homepage');
        }
    }

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) { // attempt method will return true or false
            $request->session()->regenerate(); // @session directive is a Blade directive used within your Blade views to work with session data. session()->regenerate() is used to regenerate the session ID after a successful login attempt, ensuring that the session ID changes and enhancing security.
            return redirect('/')->with('success', 'You have successfully logged in!');
        }
        else {
            return redirect('/')->with('failure', 'Invalid login!');
        }

    }

    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed']
        ]); // Verify that the fields are filled in correctly. If not it will show error notification when user register successfully.

        $incomingFields['password'] = bcrypt($incomingFields['password']); // encrypt password by 'bcrypt'

        $user = User::create($incomingFields);
        auth()->login($user); // 
        return redirect('/')->with('success', 'Thank you for creating an account!'); 
    }
}
