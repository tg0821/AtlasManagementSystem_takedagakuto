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
    public function store(Request $request)
{
    DB::beginTransaction();
    $yearNow = now()->year;

    // Validatorオブジェクトを使ってバリデーション定義
    $validator = Validator::make($request->all(), [
        'over_name'=>'required|string|max:10',
        'under_name'=>'required|string|max:10',
        'over_name_kana'=>'required|string|regex:/^[ァ-ンヴー]+$/|max:10',
        'under_name_kana'=>'required|string|regex:/^[ァ-ンヴー]+$/|max:10',
        'mail_address'=>'required|max:100|email|unique:users,mail_address',
        'sex'=>'required|in:1,2,3',
        'role'=>'required|in:1,2,3,4',
        'password'=>'required|min:8|max:30|confirmed',
        'old_year'=>'required|integer|min:2000|max:'. $yearNow,
        'old_month'=>'required|integer|between:1,12',
        'old_day'=>'required|integer|between:1,31',
    ],
     [
    'over_name_kana.regex' => '姓（カナ）はカタカナで入力してください。',
    'mail_address.unique' => 'このメールアドレスはすでに使われています。',
    'old_year.min' => '2000年以降の年を入力してください。',
    'old_month.integer' => '月は整数で入力してください。',
    'old_day.integer' => '日付は整数で入力してください。',
     ]);

    // 追加チェック：存在する日付か、かつ範囲内か
    $validator->after(function ($validator) use ($request) {
        $date = $request->old_year . '-' . $request->old_month . '-' . $request->old_day;

        if (!checkdate((int)$request->old_month, (int)$request->old_day, (int)$request->old_year)) {
            $validator->errors()->add('old_day', '正しい日付を入力してください。');
        }

        $parsedDate = strtotime($date);
        if ($parsedDate < strtotime('2000-01-01') || $parsedDate > time()) {
            $validator->errors()->add('old_year', '2000年1月1日から今日までの間で入力してください。');
        }
    });

    // バリデーション失敗時の処理
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

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
