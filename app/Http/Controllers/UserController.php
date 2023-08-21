<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // storeAvatar
    public function storeAvatar(Request $request) {
        $request->validate([
            'avatar' => 'required|image|max:3000' // max:3000 KB
        ]);

        $user = auth()->user();

        $filename = $user->id . '-' . uniqid() . '.jpg';
        // uniqid(): generated string of text


        // $request->file('avatar')->store('public/avatars');
        
        // The third 'Intervention Image Package'
        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('public/avatars/' . $filename, $imgData);
        
        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save(); // save image in database
        
        if ($oldAvatar != "/fallback-avatar.jpg") {
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        } // delete previous avatar

        return back()->with('success', "Congrats on the new avatar.");

    }

    // showAvatarForm
    public function showAvatarForm() {
        return view('avatar-form');
    }

    // getSharedData
    private function getSharedData($user) {
        $currentlyFollowing = 0;

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        // If View:share called, will provide that data for the object to use
        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing, 'avatar' => $user->avatar, 'username' => $user->username, 'posts' => $user->posts()->latest()->get(), 'postCount' => $user->posts()->count()]);
    }

    // profile
    public function profile(User $user) {
        $this->getSharedData($user);
        return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
        // post() function in User Model
    }

    // profileFollowers
    public function profileFollowers(User $user) {
        $this->getSharedData($user);
        return view('profile-followers', ['posts' => $user->posts()->latest()->get()]);
    }

    // profileFollowing
    public function profileFollowing(User $user) {
        $this->getSharedData($user);
        return view('profile-following', ['posts' => $user->posts()->latest()->get()]);
    }

    // logout
    public function logout()
    {
        auth()->logout(); // verify correct user account want to logout
        // https://stackoverflow.com/questions/43585416/how-to-logout-and-redirect-to-login-page-using-laravel-5-4 
        return redirect('/')->with('success', 'You are now logged out!');
    }

    // showCorrectHomepage
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

    // login
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

    // register
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
