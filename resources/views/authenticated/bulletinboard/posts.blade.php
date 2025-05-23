<x-sidebar>
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    <p class="w-75 m-auto">投稿一覧</p>
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area d-flex">
        <div class="d-flex post_status">
          <div class="mr-5">
           <i class="fa fa-comment"></i><span class="ml-1">{{ $post->postComments->count() }}</span>
          </div>
          <div>
            @if(Auth::user()->is_Like($post->id))
            <p class="m-0">
             <i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
             <span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span>
            </p>
            @else
            <p class="m-0">
             <i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i>
             <span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span>
            </p>
            @endif
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="other_area border w-25">
  <div class="border m-4">
    <!-- 投稿作成リンク -->
    <div><a href="{{ route('post.input') }}">投稿</a></div>

    <!-- キーワード検索フォーム -->
    <div class="">
        <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
        <input type="submit" value="検索" form="postSearchRequest">
    </div>

    <!-- いいねした投稿や自分の投稿ボタン -->
    <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
    <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest">

    <!-- メインカテゴリーとそのサブカテゴリーを表示 -->
  <ul>
    @foreach($categories as $category)
      <li class="main_categories">
        <span>{{ $category->main_category }}</span>
        <ul>
          @foreach($category->subCategories as $sub)
            <li>
              <form action="{{ route('post.show') }}" method="get" style="display:inline;">
                <input type="hidden" name="category_word" value="{{ $sub->sub_category }}">
                <button type="submit" class="btn btn-link p-0 m-0">{{ $sub->sub_category }}</button>
              </form>
            </li>
          @endforeach
        </ul>
      </li>
    @endforeach
  </ul>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
</x-sidebar>
