<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use App\Http\Requests\BulletinBoard\CommentFormRequest;
// 上記コメントフォームリクエストは新たに追加
use App\Http\Requests\BulletinBoard\SubCategoryRequest;
// サブカテゴリー用
use App\Http\Requests\BulletinBoard\MainCategoryRequest;
//メインカテゴリー用
use Auth;


class PostsController extends Controller
{

public function show(Request $request)
{
    $like = new Like;
    $post_comment = new Post;

    // 全カテゴリーを取得（メイン + サブ）
    $categories = MainCategory::with('subCategories')->get();

    // 投稿取得の初期状態
    $posts = Post::with('user', 'postComments', 'likes','subCategories');

    // ① 検索キーワードがサブカテゴリー名と完全一致
    if (!empty($request->keyword)) {
        $subCategory = SubCategory::where('sub_category', $request->keyword)->first();
        if ($subCategory) {
            // サブカテゴリーが見つかった場合、そのサブカテゴリーに属する投稿のみ取得
            $posts = $subCategory->posts()->with('user', 'postComments', 'likes', 'subCategories');
        } else {
            // タイトルまたは本文で部分一致検索
            $posts = $posts->where('post_title', 'like', '%' . $request->keyword . '%')
                           ->orWhere('post', 'like', '%' . $request->keyword . '%');
        }
    }

    // ③ サブカテゴリークリック（category_word にサブカテゴリー名が入る）
    if ($request->category_word) {
        $posts = $posts->whereHas('subCategories', function ($query) use ($request) {
            // サブカテゴリー名で絞り込み
            $query->where('sub_category', $request->category_word);
        });
    }

    // いいねした投稿
    if ($request->like_posts) {
        $like_ids = Auth::user()->likePostId()->pluck('like_post_id');
        $posts = $posts->whereIn('id', $like_ids);
    }

    // 自分の投稿
    if ($request->my_posts) {
        $posts = $posts->where('user_id', Auth::id());
    }

    // クエリビルダ完了 → 実行
    $posts = $posts->get();

    return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
}



    public function postDetail($post_id){
        $post = Post::with('user', 'postComments', 'subCategories')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        // $main_categories = MainCategory::get();
        $main_categories = MainCategory::with('subCategories')->get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);
        // サブカテゴリーとの関連付け
        $post->subCategories()->attach($request->post_category_id);
        // dd($post->subCategories); // ← 保存直後に確認
        return redirect()->route('post.show');
    }

    public function postEdit(PostFormRequest $request){
        // RequestからPostFormRequestに変更
            // バリデーション
        //  $request->validate([
        // 'post_title' => 'required|string|max:100', // post_titleが必須、文字列で最大100文字
        // 'post_body' => 'required|string|max:2000', // post_bodyが必須、文字列で最大2000文字
        // ]);
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    public function mainCategoryCreate(MainCategoryRequest $request){
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    // サブカテゴリーの追加
    public function subCategoryCreate(SubCategoryRequest $request){
    SubCategory::create([
        'main_category_id' => $request->main_category_id,
        'sub_category' => $request->sub_category_name,
    ]);
    return redirect()->route('post.input');
}
    public function commentCreate(CommentFormRequest $request){
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }


    public function postLike(Request $request){
         $user_id = Auth::id();
         $post_id = $request->post_id;

         $like = new Like;

         $like->like_user_id = $user_id;
         $like->like_post_id = $post_id;
         $like->save();

         return response()->json();
     }

     public function postUnLike(Request $request){
         $user_id = Auth::id();
         $post_id = $request->post_id;

         $like = new Like;

         $like->where('like_user_id', $user_id)
              ->where('like_post_id', $post_id)
              ->delete();

         return response()->json();
     }

}
