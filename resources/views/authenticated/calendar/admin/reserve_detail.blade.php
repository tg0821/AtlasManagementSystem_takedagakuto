<x-sidebar>
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="w-50 m-auto h-75">
    <p><span>{{ $date }}日</span><span class="ml-3">{{$part}}部</span></p>
    <div class="h-75 border">
      <table class="table table-bordered">
        <thead class="text-center">
          <tr>
            <th class="w-25">ID</th>
            <th class="w-25">名前</th>
            <th class="w-25">場所</th>
          </tr>
        </thead>
        <tbody>
          <!-- コントローラーのpublic function reserveDetailから値持ってきた -->
          @foreach ($users as $user)
            <tr class="text-center">
              <td class="w-25">{{ $user->id }}</td>
              <td class="w-25">{{ $user->over_name }} {{ $user->under_name }}</td>
              <td class="w-25">リモート</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
</x-sidebar>
