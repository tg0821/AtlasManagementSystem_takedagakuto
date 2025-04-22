<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories\SubCategory;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories(){
    return $this->belongsToMany(\App\Models\Categories\SubCategory::class, 'post_sub_categories', 'post_id', 'sub_category_id');
    }                                                                        //中間テーブル名, 自分のIDが入ってるカラム, 相手のIDが入ってるカラム


    // コメント数
    public function commentCounts($post_id){
        return Post::with('postComments')->find($post_id)->postComments();
    }

    // いいね数
    public function likes(){
    return $this->hasMany(Like::class,'like_post_id');
}
}
