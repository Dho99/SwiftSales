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
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="modalTitle"></h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <div class="ms-auto">
                  <button type="button" class="btn btn-danger" id="deleteSupplierBtn">Hapus</button>
                  <button type="button" class="btn btn-primary" id="editSupplierBtn">Edit</button>
              </div>
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
                    <textarea name="" id="address" cols="30" readonly class="form-control" rows="">${data.address}</textarea>
                </div>
                `)
                modal.find('#editSupplierBtn').attr('onclick', `editSupplier('${id}')`)
                modal.find('#deleteSupplierBtn').attr('onclick', `deleteSupplier('${id}')`);
            }).catch(function(xhr){
                swalError(xhr.responseText);
            });
        }

        modal.on('hidden.bs.modal', function(){
            modal.find('.modal-body').empty();
            modal.find('#editSupplierBtn').text('Edit').removeAttr('onclick');
        });

        function editSupplier(id){
            modal.find('.modal-body input, textarea').removeAttr('readonly');
            modal.find('#editSupplierBtn').text('Simpan').attr('onclick', `updateSupplier('${id}')`);
        }

        function updateSupplier(id){
           let name = $('#name');
           let telephone = $('#telephone');
           let address = $('#address');

           if(name.val() === '' && telephone.val() === '' && address.val() === ''){
                swalError('Data yang diperlukan tidak boleh kosong');
           }else{
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', name.val());
            formData.append('telephone', telephone.val());
            formData.append('address', address.val());
            storeData(currentUrl+'/'+id, formData).then(function(response){
                modal.modal('hide');
                swalSuccess(response.message);
                findDatas();
            }).catch(function(xhr){
                swalError(xhr.responseText);
            });
           }
        }

        function deleteSupplier(id){
            swalConfirm('Apakah anda benar - benar akan menghapus data ini?').then(function(result){
                if(result){
                    deleteData(currentUrl+'/'+id, 'ss').then(function(response){
                        swalSuccess(response.message);
                        modal.modal('hide');
                        setTimeout(() => {
                            findDatas();
                        }, 1000);
                    });
                }
            }).catch(function(error){
                console.log(error);
            });
        }

        function createUser(){
            modal.modal('show');
            modal.find('#modalTitle').text('Tambah data Supplier');
            modal.find('.modal-body').append(`
                <div class="form-group">
                    <label for="name">Nama Supplier</label>
                    <input type="text" name="" id="name" class="form-control">
                </div>
                <div class="form-group mt-2">
                    <label for="telephone">No. Telephone</label>
                    <input type="text" name="" id="telephone" class="form-control">
                </div>
                <div class="form-group mt-2">
                    <label for="address">Alamat</label>
                    <textarea name="" id="address" cols="30" class="form-control" rows=""></textarea>
                </div>
                `);
            modal.find('#deleteSupplierBtn').hide();
            modal.find('#editSupplierBtn').attr('onclick', `createNewSupplier()`);
        }

        function createNewSupplier(){
            let name = $('#name');
           let telephone = $('#telephone');
           let address = $('#address');

           if(name.val() === '' && telephone.val() === '' && address.val() === ''){
                swalError('Data yang diperlukan tidak boleh kosong');
           }else{
            const formData = new FormData();
            formData.append('name', name.val());
            formData.append('telephone', telephone.val());
            formData.append('address', address.val());
            storeData(currentUrl, formData).then(function(response){
                modal.modal('hide');
                swalSuccess(response.message);
                setTimeout(() => {
                    findDatas();
                }, 1000);
            }).catch(function(xhr){
                swalError(xhr.responseText);
            });
           }
        }
    </script>
@endpush
