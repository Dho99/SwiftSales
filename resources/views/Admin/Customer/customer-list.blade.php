@extends('Layouts.index')
@section('content')
    <div class="container bg-light py-3 px-4 rounded">
        <table class="table table-responsive" id="customersTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Bergabung Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>


    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Data Pelanggan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="changeToRole">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeRole" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="editRoleBtn">Edit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const currUrl = '{{ url()->current() }}';

        $().ready(function() {
            findDatas();
            // $('#customerModal').modal('show');
        });


        function findDatas() {
            findData(currUrl).then(function(response) {
                reinitializeTable('#customersTable', response.customers, [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "name"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return formatDate(row.created_at);
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                            <div class="d-flex flex-row">
                                <button class="btn btn-sm btn-primary" onclick="details('${row.id}')"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deleteCustomer('${row.id}')" data-id="${row.id}"><i class="bi bi-trash"></i></button>
                            </div>
                            `;
                        }
                    }
                ]);
            }).catch(function(xhr, error) {
                swalError(xhr.responseText);
            });
        }


        function details(id) {
            findData(`/user/${id}`).then(function(response) {
                showCustomerModal(response.data);
            }).catch(function(xhr, error) {
                swalError(xhr.responseText);
            });
        }

        const modal = $('#customerModal');

        function showCustomerModal(data) {
            modal.modal('show');
            modal.find('#modalBody').append(`
            <div class="row">
                <div class="col-12 form-group">
                    <img src="${data.profilePhoto}" class="img-fluid rounded w-50 d-flex mx-auto" alt="">
                </div>
                <div class="col-12 form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" readonly value="${data.name}">
                </div>
                <div class="col-12 form-group">
                    <label for="name">Email</label>
                    <input type="text" class="form-control" id="name" readonly value="${data.email}">
                </div>
                <div class="col-12 form-group">
                    <label for="name">No. Telp</label>
                    <input type="text" class="form-control" id="name" readonly value="${data.telephone}">
                </div>
                <div class="col-12 form-group">
                    <label for="name">Alamat</label>
                    <input type="text" class="form-control" id="name" readonly value="${data.address}">
                </div>
                <div class="col-12 form-group">
                    <label for="name">Bergabung Pada</label>
                    <input type="text" class="form-control" id="name" readonly value="${formatDate(data.created_at)}">
                </div>
            </div>
            `);
            modal.find('#changeToRole').attr('onclick', `editRoleUser('${data.id}', '${data.roles}')`);
        }

        $('#customerModal, #changeRole').on('hidden.bs.modal', function() {
            $(this).find('.modal-body').empty();
        });

        const userRoles = ['Admin', 'Cashier', 'Customer'];

        function editRoleUser(id, roles) {
            $('#changeRole').modal('show');
            modal.modal('hide');
            $('#changeRole').find('#modalBody').append(`
                <select name="roles" class="form-select" id="selectRoleUser" data-id="${id}" data-role="${roles}">
                    ${userRoles.map(role =>
                        `<option value="${role}" ${roles === role ? 'selected' : ''}>${role}</option>`
                    )}
                </select>
            `);
        }

        $('#editRoleBtn').on('click', function() {
            const selectRoles = $('#selectRoleUser');
            const rolesVal = selectRoles.val();
            const roles = selectRoles.attr('data-role');
            const custId = selectRoles.attr('data-id');
            if(roles !== rolesVal){
                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('roles', rolesVal);
                const url = '/admin/customer/' + custId;
                storeData(url, formData).then(function(response) {
                    swalSuccess(response.message);
                    setTimeout(() => {
                        findDatas();
                    }, 1000);
                    $('#changeRole').modal('hide');
                }).catch(function(xhr, error) {
                    swalError(xhr.responseText);
                });
            } else {
                $('#changeRole').modal('hide');
            }
        });

        function deleteCustomer(id){
            const url = '/user/'+id;
            deleteData(url, 'Apakah anda yakin akan menghapus data Customer ?').then(function(response){
                swalSuccess(response.message);
                setTimeout(() => {
                    findDatas();
                }, 1000)
            }).catch(function(xhr, error){
                swalError(xhr.responseText);
            });
        }

    </script>
@endpush
