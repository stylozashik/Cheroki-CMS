<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class TailorSuperAdminController extends Controller
{
    public function index(){
        $this->AdminCheckAuth();
        return view('backend.tailor.dashboard');
    }

    public function logout(){
        Session::forget('tailor_admin_id');
        return redirect('/dashboard/tailor');
    }

    public function AdminCheckAuth(){
        $id = Session::get('tailor_admin_id');
        if($id != null){
            return;
        }else{
            return redirect('tailor/admin-login')->send();
        }
    }

    public function admin_profile(){
        $admin_profile = DB::table('admin_profiles')->latest()->first();
        return view('backend.tailor.admin.profile',compact('admin_profile'));
    }

    public function update_admin_profile(Request $request){
        $profile = DB::table('admin_profiles')->latest()->first();

        $find_profile = \App\AdminProfile::findOrFail($profile->admin_id) ;

        //validate form data field
        $validatedData = $request->validate([
            'admin_email' => 'required',
            'admin_password' => 'required',
            'admin_name' => 'required', 
        ]);
        $find_profile->admin_email = request('admin_email');
        $find_profile->admin_password = md5(request('admin_password'));
        $find_profile->admin_name = request('admin_name');

        $find_profile->save();

        Session::put('admin_name',$find_profile->admin_name);

        return Redirect::to('/dashboard/tailor/profile/admin');
    }
}
