 {{-- Modal confirm --}}
 <div id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="exampleModalLabel" class="modal-title">Cảnh báo</h5>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body" style="text-align: center">
          <div class="alert alert-danger" role="alert">
            Bạn có thật sự muốn xóa ?
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-secondary">Đóng</button>
          <button type="button" class="btn btn-danger" id="confirm">Xác nhận</button>
        </div>
      </div>
    </div>
  </div>