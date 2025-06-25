<x-sidebar>
<div class="post_create_container d-flex">
  <div class="post_create_area border w-50 m-5 p-5">
    <div class="">
      <p class="mb-0">カテゴリー</p>
      <select class="w-100" form="postCreate" name="post_category_id">
        @foreach($main_categories as $main_category)
        <optgroup label="{{ $main_category->main_category }}">
        <!-- サブカテゴリー表示 -->
          @foreach($main_category->subCategories as $sub_category)
            <option value="{{ $sub_category->id }}">{{ $sub_category->sub_category }}</option>
          @endforeach
        </optgroup>
        @endforeach
      </select>
    </div>
    <div class="mt-3">
      @if($errors->first('post_title'))
      <span class="error_message">{{ $errors->first('post_title') }}</span>
      @endif
      <p class="mb-0">タイトル</p>
      <input type="text" class="w-100" form="postCreate" name="post_title" value="{{ old('post_title') }}">
    </div>
    <div class="mt-3">
      @if($errors->first('post_body'))
      <span class="error_message">{{ $errors->first('post_body') }}</span>
      @endif
      <p class="mb-0">投稿内容</p>
      <textarea class="w-100" form="postCreate" name="post_body">{{ old('post_body') }}</textarea>
    </div>
    <div class="mt-3 text-right">
      <input type="submit" class="btn btn-primary" value="投稿" form="postCreate">
    </div>
    <form action="{{ route('post.create') }}" method="post" id="postCreate">{{ csrf_field() }}</form>
  </div>
  @can('admin')
  <!-- <div class="w-25 ml-auto mr-auto" style="height:300px"> -->
    <div class="category_area ml-auto mr-auto mt-5 p-5" style="height:400px; width=350px">
      <div class="">
        @if ($errors->has('main_category_name'))
          <p class="text-danger" style="margin-bottom:0px">{{ $errors->first('main_category_name') }}</p>
        @endif
        <p class="m-0">メインカテゴリー</p>
        <input type="text" class="w-100" name="main_category_name" form="mainCategoryRequest">
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0" style="margin-top:15px;"form="mainCategoryRequest">
      </div>
      <!-- サブカテゴリー追加 -->
      <div class="">
        <p  style="margin-top:30px">サブカテゴリー</p>
           <!-- バリデーションエラー表示 -->
    @if ($errors->has('main_category_id'))
      <p class="text-danger" style="margin-bottom:0px">{{ $errors->first('main_category_id') }}</p>
    @endif
    <!-- メインカテゴリー選択 -->
    <select name="main_category_id" class="w-100 mb-2" form="subCategoryRequest">
      <option value="" disabled selected>---</option>
      @foreach($main_categories as $main_category)
        <option value="{{ old('main_category_id', $main_category->id) }}">{{ $main_category->main_category }}</option>
      @endforeach
    </select>
    @if ($errors->has('sub_category_name'))
      <p class="text-danger" style="margin-bottom:0px">{{ $errors->first('sub_category_name') }}</p>
    @endif
    <!-- サブカテゴリー入力 -->
    <input type="text" class="w-100 " name="sub_category_name" form="subCategoryRequest" value="{{ old('sub_category_name') }}">
    <input type="submit" value="追加" class="w-100 btn btn-primary p-0 " style="margin-top:15px;" form="subCategoryRequest">
  </div>
  <form action="{{ route('sub.category.create') }}" method="post" id="subCategoryRequest">
    @csrf
  </form>
      </div>
      <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">{{ csrf_field() }}</form>
    </div>
  </div>
  @endcan
</div>
</x-sidebar>
