<x-sidebar>
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">
      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div>
        {!! $calendar->render() !!}
      </div>
    </div>

    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
    </div>
  </div>
</div>

<!-- {{-- 削除確認モーダル --}} -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmModalLabel">予約キャンセル確認</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
      </div>

      <div class="modal-body">
        <p id="deleteModalDate"></p>
        <p id="deleteModalTime"></p>
        <p>上記の予約をキャンセルしてもいいですか？</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
        <form action="/delete/calendar" method="post" id="deleteParts">
          @csrf
          <input type="hidden" name="delete_date" id="modalDeleteDateValue">
          <button type="submit" class="btn btn-danger">キャンセルする</button>
        </form>
      </div>

    </div>
  </div>
</div>

<!-- {{-- JavaScript（Bootstrapのモーダル制御） --}} -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  // すべての削除ボタンにイベントを設定
  document.querySelectorAll('.delete-button').forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault(); // フォームの送信をキャンセル

      // ボタンの値を取得（例："2025-05-09 リモ2部"）
      const reserveDate = this.value; // yyyy-mm-dd
      const reservePart = this.dataset.part; // リモ⚪︎部

      // 日付部分と部（時間）を分割してモーダルに表示
      document.getElementById('deleteModalDate').textContent = `予約日：${reserveDate}`;
      document.getElementById('deleteModalTime').textContent = `時間：${reservePart}`;
      document.getElementById('modalDeleteDateValue').value = reserveDate;

      // モーダル表示
      const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
      modal.show();
    });
  });
});

</script>
  <!-- ✅ BootstrapのJS（最後に追加！） -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</x-sidebar>
