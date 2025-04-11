<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use DB;

use App\Models\Users\Subjects;
use App\Models\Users\User;
use Illuminate\Support\Facades\Validator; // 忘れずにインポート
use App\Http\Requests\Auth\RegisterRequest; //バリデーションが書かれている場所

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
{
    DB::beginTransaction();
    $yearNow = now()->year;
    try {
        $data = $request->old_year . '-' . $request->old_month . '-' . $request->old_day;
        $birth_day = date('Y-m-d', strtotime($data));
        $subjects = $request->subject;

        $user_get = User::create([
            'over_name' => $request->over_name,
            'under_name' => $request->under_name,
            'over_name_kana' => $request->over_name_kana,
            'under_name_kana' => $request->under_name_kana,
            'mail_address' => $request->mail_address,
            'sex' => $request->sex,
            'birth_day' => $birth_day,
            'role' => $request->role,
            'password' => bcrypt($request->password)
        ]);

        if ($request->role == 4) {
            $user = User::findOrFail($user_get->id);
            $user->subjects()->attach($subjects);
        }

        DB::commit();
        return view('auth.login.login');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->route('loginView');
    }
}}
