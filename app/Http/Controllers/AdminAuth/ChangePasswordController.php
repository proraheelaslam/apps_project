<?php

namespace App\Http\Controllers\AdminAuth;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    //
    public function edit()
    {
        return view('admin.changePassword.edit');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        try {
            $user =    Auth::guard('admin')->user();
            $userId  = $user->id;
            $validator = Validator::make($request->all(), [
                'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        return $fail(__('The current password is incorrect.'));
                    }
                }],
                'new_password' => 'required|min:2|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'required|min:2'
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }
            Admin::where('_id',$userId)->update(['password'=> bcrypt($request->new_password)]);
        } catch (\Exception $e) {
             return \redirect('admin/home');
        }
         return \redirect('admin/home');
    }

}
