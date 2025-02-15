<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    public function index(){
        $datas = User::with('role:id,name')
            ->select('id', 'name', 'email', 'image_path', 'telephone_number', 'address','role_id')
            ->where('role_id', 2)
            ->get();
        return view('admin.staff', compact('datas'));
    }
    public function edit($id){
        $data=User::withoutTrashed()->find($id);
        return view('admin/staff_edit',compact('data'));
    }
    public function update(Request $request,$id){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => ['nullable', 'min:8','confirmed', Rules\Password::defaults()],
            'userImg'=>['nullable','image','mimes:jpeg,png,jpg','max:2048'],
            'telephone'=>['required','string','min:11','max:13'],
            'address'=>['required','string'],
        ]);

//        $user = Auth::user();   will add later to have history who has changed the data record
        $data = User::findOrFail($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->telephone_number = $request->telephone;
        $data->address = $request->address;
        if ($request->filled('password')) {
            $data->password = Hash::make($request->password);
        }
        if ($request->hasFile('userImg')) {
            $imagePath = $request->file('userImg')->store('profile_images', 'public');
            $data->image_path = $imagePath;
        }
        $data->save();
        return redirect()->route('staff.index')->with('success', 'Staff item updated successfully');
    }
    public function remove($id){
        $data=User::findorfail($id);
        $data->delete();
        return redirect()->back();
    }
    public function trash(){
        $datas=User::onlyTrashed()->get();
        return view('admin/staff_trash',compact('datas'));
    }
    public function back($id){
        $data=User::onlyTrashed()->where('id',$id);
        $data->restore();
        return redirect()->back();
    }
}
