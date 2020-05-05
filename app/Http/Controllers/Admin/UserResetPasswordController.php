<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserResetPasswordController extends Controller
{
    //
    /**
     * @param $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetPassword($data)
    {

        return view('user.reset_password')->with(['emailToken' => $data]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:2|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        $dataDecode = base64_decode($request->emailToken);
        $user = json_decode($dataDecode);
        $user = User::find($user->id);
        if (!empty($user->remember_token)) {
            $user->user_password = bcrypt($request->password);
            $user->remember_token = '';
            $user->save();
        } else {
            return redirect()->back()->with('error_message', 'Your password token has been expired.');
        }
        return redirect()->back()->with('message', 'Your password has been reset.');
    }
}
