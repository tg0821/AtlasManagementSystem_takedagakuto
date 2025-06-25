<x-sidebar>
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="w-50 m-auto h-75">
    <p><span>{{ $date }}</span><span class="ml-3">{{$part}}部</span></p>
    <div style="background-color: white; border-radius: 20px; height: auto; padding: 10px;">
      <table class="table table-bordered" style="border: none; margin:0px">
        <thead class="text-center">
          <tr style="background-color: #09a9d2; color: white;">
            <th class="reserve-id" style="border:none;">ID</th>
            <th class="reserve-name" style="border:none;">名前</th>
            <th class="reserve-place" style="border:none;">場所</th>
          </tr>
        </thead>
        <tbody>
          <!-- コントローラーのpublic function reserveDetailから値持ってきた -->
          @foreach ($users as $user)
            <tr class="text-center">
              <td class="reserve-id" style="border:none;border-bottom: 1px solid #ccc;">{{ $user->id }}</td>
              <td class="reserve-name" style="border:none;border-bottom: 1px solid #ccc;">{{ $user->over_name }} {{ $user->under_name }}</td>
              <td class="reserve-place" style="border:none;border-bottom: 1px solid #ccc;">リモート</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
</x-sidebar>
