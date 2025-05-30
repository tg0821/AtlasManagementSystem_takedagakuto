<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;
use App\Models\Posts\Post; // ここで正しい名前空間を指定

class SubCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];
    public function mainCategory(){
        // リレーションの定義
         return $this->belongsTo(\App\Models\Categories\MainCategory::class, 'main_category_id');
    }

    public function posts(){
        // リレーションの定義
            return $this->belongsToMany(Post::class,'post_sub_categories','sub_category_id','post_id');
    }
}
