<x-sidebar>
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    <p class="w-75 m-auto">投稿一覧</p>
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <div class="post_bottom_area d-flex">
      <p><a href="{{ route('post.detail', ['id' => $post->id]) }}" class="small-ball">{{ $post->post_title }}</a></p>
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
  <div class="other_area w-25" style="background-color: transparent;">
  <div class="m-4">
    <!-- 投稿作成リンク -->
    <p class="tweet"><a href="{{ route('post.input') }}" style="color:white; display: block; width: 100%;">投稿</a></p>

    <!-- キーワード検索フォーム -->
    <div class="">
        <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest" style="border-radius: 8px;
    border-width: 1px;
    border: 1px solid #dee2e6;">
        <input type="submit" value="検索" form="postSearchRequest" class="search">
    </div>
    <div style="display: flex;">
    <!-- いいねした投稿や自分の投稿ボタン -->
    <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
    <input type="submit" name="my_posts" class="my_tl_btn" value="自分の投稿" form="postSearchRequest">
    </div>
    <!-- メインカテゴリーとそのサブカテゴリーを表示 -->
  <ul>
    <li style="margin: 10px 0px;">カテゴリー検索</li>
    <ul class="category-list">
  @foreach($categories as $category)
    <li class="main-categories">
      <div class="main-toggle" style="border-bottom: 1px solid #bec0c2;    width: 100%;
    justify-content: space-between;">
        {{ $category->main_category }}
        <p class="post-icon"></p>
      </div>
      <ul class="sub-category-list" style="display: none;">
        @foreach($category->subCategories as $sub)
          <li style="color:#bec0c2;">
            <form action="{{ route('post.show') }}" method="get" style="display:inline;">
              <input type="hidden" name="category_word" value="{{ $sub->sub_category }}">
              <button type="submit" class="btn btn-link p-0 " style="color: #212529;
    border-bottom: 1px solid #bec0c2;
    border-radius: 0px;width: 100%;
    text-align: left;margin-top:8px; font-size: 12px;margin-bottom: 8px;">{{ $sub->sub_category }}</button>
            </form>
          </li>
        @endforeach
      </ul>
    </li>
  @endforeach
</ul>


  </ul>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
</x-sidebar>

<script>
  $(function () {
    $('.main-toggle').click(function () {
      $(this).toggleClass('active'); // 矢印の回転制御
      $(this).next('.sub-category-list').slideToggle();
    });
  });
</script>
