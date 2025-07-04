<x-sidebar>
<p>ユーザー検索</p>
<div class="search_content  border d-flex">
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="border one_person">
      <div style="margin-bottom: 10px;">
        <span>ID : </span><span>{{ $user->id }}</span>
      </div>
      <div style="margin-bottom: 10px;"><span>名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
          <span>{{ $user->over_name }}</span>
          <span>{{ $user->under_name }}</span>
        </a>
      </div>
      <div style="margin-bottom: 10px;">
        <span>カナ : </span>
        <span>({{ $user->over_name_kana }}</span>
        <span>{{ $user->under_name_kana }})</span>
      </div>
      <div style="margin-bottom: 10px;">
        @if($user->sex == 1)
        <span>性別 : </span><span>男</span>
        @elseif($user->sex == 2)
        <span>性別 : </span><span>女</span>
        @else
        <span>性別 : </span><span>その他</span>
        @endif
      </div>
      <div style="margin-bottom: 10px;">
        <span>生年月日 : </span><span>{{ $user->birth_day }}</span>
      </div>
      <div style="margin-bottom: 10px;">
        @if($user->role == 1)
        <span>権限 : </span><span>教師(国語)</span>
        @elseif($user->role == 2)
        <span>権限 : </span><span>教師(数学)</span>
        @elseif($user->role == 3)
        <span>権限 : </span><span>講師(英語)</span>
        @else
        <span>権限 : </span><span>生徒</span>
        @endif
      </div>
      <div style="margin-bottom: 10px;">
       @if($user->role == 4)
         <span>選択科目 :</span>
        @if($user->subjects->isNotEmpty())
         @foreach($user->subjects as $subject)
         <span>{{ $subject->subject }}</span>
         @endforeach
        @else
         <span>（なし）</span>
        @endif
       @endif
      </div>
    </div>
    @endforeach
  </div>
  <div class="search_area w-25" style="background-color:transparent;">
    <div class="">
      <div style="display: flex; flex-direction: column;">
        <p style="margin-top:30px; color:#4a6a87">検索</p>
        <input type="text" class="free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
      </div>
      <div style="display: flex; flex-direction: column; color:#4a6a87">
        <lavel>カテゴリ</lavel>
        <select form="userSearchRequest" name="category" style="width: 25%; text-align: center;background-color: #e0e5e9;
  border: none;
  border-radius: 5px;
  margin-bottom: 15px; padding: 5px 0px;">
          <option value="name">名前</option>
          <option value="id">社員ID</option>
        </select>
      </div>
      <div style="display: flex; flex-direction: column;  color:#4a6a87">
        <label>並び替え</label>
        <select name="updown" form="userSearchRequest" style="width: 25%; text-align: center; background-color: #e0e5e9;
  border: none;
  border-radius: 5px;
  margin-bottom: 15px; padding: 5px 0px;">
          <option value="ASC">昇順</option>
          <option value="DESC">降順</option>
        </select>
      </div>
      <div class="">
        <p class="m-0 search_conditions" style="border_bottom:1px solid;" ><span style="color:#4a6a87">検索条件の追加<span class="search-icon"></span></p>
        <div class="search_conditions_inner">
          <div>
            <label class="choices" style="color: #4a6a87;margin-top:8px;">性別</label>
            <span>男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
            <span>女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
            <span>その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
          </div>
          <div>
            <label class="choices" style="color: #4a6a87;">権限</label>
            <select name="role" form="userSearchRequest" class="engineer">
              <option selected disabled>----</option>
              <option value="1">教師(国語)</option>
              <option value="2">教師(数学)</option>
              <option value="3">教師(英語)</option>
              <option value="4" class="">生徒</option>
            </select>
          </div>
          <label style="margin-top:5px; color: #4a6a87;">選択科目</label>
          <div class="selected_engineer">
              @foreach($subjects as $subject)
                <label style="display: block;">
                {{ $subject->subject }}
                <input type="checkbox" name="subject_id[]" value="{{ $subject->id }}" form="userSearchRequest">
                </label>
               @endforeach
          </div>
        </div>
      </div>
      <div >
        <input type="submit" name="search_btn" value="検索" form="userSearchRequest" style="width:85%;margin-top: 35px;border: none;
    background-color: #09a9d2;
    border-radius: 5px;
    color: white;
    padding: 7px 0px;">
      </div>
      <div>
        <input type="reset" value="リセット" form="userSearchRequest" style="    border: none;
    background-color: transparent;
    color: #09a9d2;
    margin-top: 20px;
    /* text-align: center; */
    width: 85%;">
      </div>
    </div>
    <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
  </div>
</div>
</x-sidebar>
