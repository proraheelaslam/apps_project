<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
    public function create()
    {
        $user = Auth::user();
        return view('admin.profile.edit',compact('user'));
    }
    public function updateProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        $admin = Admin::find(Auth::id());
        if ($request->has('profile_image')) {
            $file = $request->file('profile_image');
            $imagePath = public_path('upload/profile/'.$admin->profile_image);
            /*if (File::exists($imagePath)){
                unlink($imagePath);
            }*/
            $this->uploadFile($request, $file, 'admin_profile', Auth::id());
        }
        $admin->name = $request->name;
        $admin->save();
        Session::flash('success','Profile has been updated');
        return \redirect()->back();
    }



    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAllData()
    {
        PostCategory::query()->delete();
        return response()->json(['message'=>'Data has been deleted successfuly']);
    }
}
