<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffRegisterController extends RegisteredUserController
{
    public function create(): View
    {
        return view('admin/staff_register');
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'min:8','confirmed', Rules\Password::defaults()],
            'userImg'=>['required','image','mimes:jpeg,png,jpg','max:2048'],
            'telephone'=>['required','string','min:11','max:13'],
            'address'=>['required','string'],
        ]);
        $imagePath = $request->file('userImg')->store('profile_images', 'public');
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id'=>"2",
            'password' => Hash::make($request->password),
            'image_path'=>$imagePath,
            'telephone_number'=>$request->telephone,
            'address'=>$request->address,
        ]);
        event(new Registered($user));
        return redirect(route('staff.index', absolute: false))->with('success', 'Food item added successfully');
    }
}
