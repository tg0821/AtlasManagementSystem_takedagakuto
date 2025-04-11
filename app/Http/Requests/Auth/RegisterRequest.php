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
        'over_name_kana'=>'required|string|regex:/^[ァ-ンヴー]+$/|max:10',
        'under_name_kana'=>'required|string|regex:/^[ァ-ンヴー]+$/|max:10',
        'mail_address'=>'required|max:100|email|unique:users,mail_address',
        'sex'=>'required|in:1,2,3',
        'role'=>'required|in:1,2,3,4',
        'password'=>'required|min:8|max:30|confirmed',
        'old_year'=>'required|integer|min:2000|max:'. $yearNow,
        'old_month'=>'required|integer|between:1,12',
        'old_day'=>'required|integer|between:1,31',

        ];
    }

    public function messages(): array
    {
        return [
            'over_name_kana.regex' => '姓（カナ）はカタカナで入力してください。',
            'mail_address.unique' => 'このメールアドレスはすでに使われています。',
            'old_year.min' => '2000年以降の年を入力してください。',
            'old_month.integer' => '月は整数で入力してください。',
            'old_day.integer' => '正しい日付を入力してください。',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $year = $this->input('old_year');
            $month = $this->input('old_month');
            $day = $this->input('old_day');

            if (!checkdate((int)$month, (int)$day, (int)$year)) {
                $validator->errors()->add('old_day', '正しい日付を入力してください。');
            }

            $date = strtotime("$year-$month-$day");
            if ($date < strtotime('2000-01-01') || $date > time()) {
                $validator->errors()->add('old_year', '2000年1月1日から今日までの間で入力してください。');
            }
        });
    }
}
