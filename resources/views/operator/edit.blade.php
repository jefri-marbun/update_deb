@extends('app')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4>Otorisasi Temuan Operator</h4>
                        </div>
                        <div class="col-12 my-4">
                          <a class="btn bg-gradient-success btn-import-excel-file mx-2 rounded-5">
                            <i class="fa fa-upload me-2" aria-hidden="true"></i> 
                            Import Excel
                          </a>
                          <a href="{{ route ('export.operator.temuan') }}" class="btn bg-gradient-primary mx-2 rounded-5">
                            <i class="fa fa-download me-2" aria-hidden="true"></i> 
                            Download Excel
                          </a>

                        </div>
                        <div class="col-12 my-3" style="width:100%">
                          <div class="table-responsive">
                            <table class="table table-striped " id="table-temuan-operator">
                              <thead class="table-orange">
                                  <tr>
                                      <th>No</th>
                                      <th>Object Pemeriksaan</th>
                                      <th>Jenis Audit</th>
                                      <th>Auditor</th>
                                      <th>Risk</th>
                                      <th>Issue Summary</th>
                                      <th>Issue Detail</th>
                                      <th>Recomendation</th>
                                      <th>Corective Action Plan</th>
                                      <th>status</th>
                                      <th>Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                @foreach ($temuan as $item)                                    
                                <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $item->object_pemeriksaan }}</td>
                                  <td>{{ $item->jenis_audit }}</td>
                                  <td>{{ $item->auditor }}</td>
                                  <td>{{ $item->risk }}</td>
                                  <td>{{ $item->issue_summary }}</td>
                                  <td>{{ $item->issue_detail }}</td>
                                  <td>{{ $item->recomendation }}</td>
                                  <td>{{ $item->corrective_action_plan }}</td>
                                  <td>{{ $item->status }}</td>
                                  <td>
                                    <div class="d-grid gap-2 d-md-block">
                                      <a class="btn btn-success" type="button">Approved</a>
                                      <a class="btn btn-warning" type="button">Edit</a>
                                      <a class="btn btn-danger" type="button">Delete</a>
                                    </div>
                                  </td>
                                </tr>
                                @endforeach
                              </tbody>
                          </table>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer pt-3">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    © <script>
                        document.write(new Date().getFullYear())

                    </script>,
                    made with <i class="fa fa-heart"></i> by
                    <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Rayhan yuda
                        lesmana</a>
                </div>
            </div>
        </div>
        </div>
</footer>
</div>


{{-- Modal --}}
<div class="modal fade" id="importexcel-Modal" tabindex="-1" aria-labelledby="importExcelModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="importExcelModal">Import file excel</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-5">
              <form action="{{ route ('import.operator.temuan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn bg-gradient-primary mx-2 rounded-5">Import Temuan</button>
              </form>
          </div>
      </div>
  </div>
</div>
{{-- Modal --}}

@push('scripts')
  <script>
      $(document).ready(function () {
        $('#table-temuan-operator').DataTable({
            responsive: true,
            lengthMenu: [
                [5, 10, 25, -1],
                [5, 10, 25, 'All'],
            ],
            order: [[0, 'asc']],
        });

        $('.btn-import-excel-file').on('click', function() {
            $('#importexcel-Modal').modal('show');
        });
    });
  </script>
@endpush
@endsection
