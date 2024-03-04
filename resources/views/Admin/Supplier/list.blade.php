@extends('Layouts.index')
@section('content')
    <div class="container py-3 px-4 rounded bg-light mt-2">
        <table class="table table-responsive table-bordered" id="supplierTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Supplier</th>
                    <th>Telephone</th>
                    <th>Alamat</th>
                    <th>Dibuat Pada</th>
                    <th>Aksi</th>
                </tr>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="supplierModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="modalTitle"></h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Understood</button>
            </div>
          </div>
        </div>
      </div>
@endsection
@push('scripts')
    <script>
        $().ready(function(){
            findDatas();
        });

        function findDatas(){
            findData('{{url()->current()}}').then(function(response){
                reinitializeTable('#supplierTable', response.data, [
                    {
                        data: null,
                        render: function(data, type, row, meta){
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "name"
                    },
                    {
                        data: "telephone"
                    },
                    {
                        data: "address"
                    },
                    {
                        data: null,
                        render: function(data, type, row){
                            return formatDate(row.created_at);
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row){
                            return `<button class="badge btn btn-primary" onclick="detailSupplier('${row.id}')"><i class="bi bi-eye"></i></button>`
                        }
                    }
                ]);
            }).catch(function(xhr, error){
                swalError(xhr.responseText);
            });
        }

        const currentUrl = '{{url()->current()}}';
        const modal = $('#supplierModal');


        function detailSupplier(id){
            findData(currentUrl+'/'+id).then(function(response){
                const data = response.data;
                modal.modal('show');
                modal.find('#modalTitle').text('Detail Supplier '+data.name);
                modal.find('.modal-body').append(`
                <div class="form-group">
                    <label for="name">Nama Supplier</label>
                    <input type="text" name="" id="name" readonly class="form-control" value="${data.name}">
                </div>
                <div class="form-group mt-2">
                    <label for="telephone">No. Telephone</label>
                    <input type="text" name="" id="telephone" readonly class="form-control" value="${data.telephone}">
                </div>
                <div class="form-group mt-2">
                    <label for="address">Alamat</label>
                    <textarea name="" id="address" cols="30" class="form-control" rows="">${data.address}</textarea>
                </div>
                `)
            }).catch(function(xhr){
                swalError(xhr.responseText);
            });
        }

        modal.on('hidden.bs.modal', function(){
            modal.find('.modal-body').empty();
        });
    </script>
@endpush
