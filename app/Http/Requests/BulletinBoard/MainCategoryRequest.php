<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class MainCategoryRequest extends FormRequest
{
    /**
     * 認可ロジック（常に許可）
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category',
        ];
    }

    /**
     * カスタムエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'main_category_name.required' => 'メインカテゴリーは必ず入力してください。',
            'main_category_name.string'   => 'メインカテゴリー名は文字列で入力してください。',
            'main_category_name.max'      => 'メインカテゴリー名は100文字以内で入力してください。',
            'main_category_name.unique'   => '同じ名前のメインカテゴリーはすでに登録されています。',
        ];
    }
}
