<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $yearNow = now()->year;
        return [
        'over_name'=>'required|string|max:10',
        'under_name'=>'required|string|max:10',
        'over_name_kana'=>'required|string|regex:/\A[ァ-ヴー]+\z/u|max:10',
        'under_name_kana'=>'required|string|regex:/\A[ァ-ヴー]+\z/u|max:10',
        'mail_address'=>'required|max:100|email|unique:users,mail_address',
        'sex'=>'required|in:1,2,3',
        'role'=>'required|in:1,2,3,4',
        'password'=>'required|min:8|max:30|confirmed',
         // 仮想的なフィールドとしてまとめる
        'old_year' => 'required|integer',
        'old_month' => 'required|integer',
        'old_day' => 'required|integer',
        ];
    }
public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $year = $this->input('old_year');
        $month = $this->input('old_month');
        $day = $this->input('old_day');

        if (!checkdate((int)$month, (int)$day, (int)$year)) {
            $validator->errors()->add('birth_date', '正しい日付を入力してください。');
            return;
        }

        $date = strtotime("$year-$month-$day");
        if ($date < strtotime('2000-01-01') || $date > time()) {
            $validator->errors()->add('birth_date', '2000年1月1日から今日までの間で入力してください。');
        }
    });
}

    public function messages(): array
    {
        return [
            'over_name_kana.regex' => '姓（カナ）はカタカナで入力してください。',
            'mail_address.unique' => 'このメールアドレスはすでに使われています。',
            'old_year.min' => '2000年以降の年を入力してください。',
            'old_month.integer' => '月は整数で入力してください。',
            'old_day.integer' => '正しい日付を入力してください。',
            // 姓・名
        'over_name.required' => '姓は必須項目です。',
        'over_name.string' => '姓は文字列で入力してください。',
        'over_name.max' => '姓は10文字以内で入力してください。',

        'under_name.required' => '名は必須項目です。',
        'under_name.string' => '名は文字列で入力してください。',
        'under_name.max' => '名は10文字以内で入力してください。',

        // カナ
        'over_name_kana.required' => '姓（カナ）は必須項目です。',
        'over_name_kana.string' => '姓（カナ）は文字列で入力してください。',
        'over_name_kana.regex' => '姓（カナ）はカタカナで入力してください。',
        'over_name_kana.max' => '姓（カナ）は10文字以内で入力してください。',

        'under_name_kana.required' => '名（カナ）は必須項目です。',
        'under_name_kana.string' => '名（カナ）は文字列で入力してください。',
        'under_name_kana.regex' => '名（カナ）はカタカナで入力してください。',
        'under_name_kana.max' => '名（カナ）は10文字以内で入力してください。',

        // メール
        'mail_address.required' => 'メールアドレスは必須項目です。',
        'mail_address.email' => '有効なメールアドレス形式で入力してください。',
        'mail_address.max' => 'メールアドレスは100文字以内で入力してください。',
        'mail_address.unique' => 'このメールアドレスはすでに使われています。',

        // 性別・役割
        'sex.required' => '性別を選択してください。',
        'sex.in' => '選択された性別は無効です。',

        'role.required' => '役割を選択してください。',
        'role.in' => '選択された役割は無効です。',

        // パスワード
        'password.required' => 'パスワードは必須項目です。',
        'password.min' => 'パスワードは8文字以上で入力してください。',
        'password.max' => 'パスワードは30文字以内で入力してください。',
        'password.confirmed' => '確認用パスワードが一致しません。',

        // 生年月日
        'old_year.required' => '生年を入力してください。',
        'old_year.integer' => '生年は整数で入力してください。',
        'old_year.min' => '2000年以降の年を入力してください。',
        'old_year.max' => '今年（:max）以下の年を入力してください。',

        'old_month.required' => '生月を入力してください。',
        'old_month.integer' => '生月は整数で入力してください。',
        'old_month.between' => '生月は1〜12の間で入力してください。',

        'old_day.required' => '生日を入力してください。',
        'old_day.integer' => '生日は整数で入力してください。',
        'old_day.between' => '生日は1〜31の間で入力してください。',

        // 日付チェック
        'old_day.date_check' => '正しい日付を入力してください。'
        ];
    }
       /**
     * Get custom attribute names for validation error messages.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'over_name' => '姓',
            'under_name' => '名',
            'over_name_kana' => '姓（カナ）',
            'under_name_kana' => '名（カナ）',
            'mail_address' => 'メールアドレス',
            'sex' => '性別',
            'role' => '役割',
            'password' => 'パスワード',
            'old_year' => '生年',
            'old_month' => '生月',
            'old_day' => '生日',
        ];
    }
}
