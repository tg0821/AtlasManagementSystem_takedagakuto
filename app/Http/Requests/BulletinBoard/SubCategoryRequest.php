<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
    public function rules()
    {
        return [
            'main_category_id' => 'required|exists:main_categories,id',
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category',
            'sub_category_name' => 'required|string|max:100|unique:sub_categories,sub_category',//バリデーションサブカテゴリー用
        ];
    }
        public function messages()
    {
        return [
            'main_category_name.required' => 'メインカテゴリー名は必須です。',
            'main_category_name.string'   => 'メインカテゴリー名は文字列で入力してください。',
            'main_category_name.max'      => 'メインカテゴリー名は100文字以内で入力してください。',
            'main_category_name.unique'   => '同じ名前のメインカテゴリーはすでに登録されています。',
            'main_category_id.required' => 'メインカテゴリーを選択してください。',
            'main_category_id.exists' => '選択されたメインカテゴリーが存在しません。',
            'sub_category_name.required' => 'サブカテゴリー名は必須項目です。',
            'sub_category_name.string' => 'サブカテゴリー名は文字列で入力してください。',
            'sub_category_name.max' => 'サブカテゴリー名は100文字以内で入力してください。',
            'sub_category_name.unique' => '同じ名前のサブカテゴリーはすでに存在します。',
        ];
    }
}
