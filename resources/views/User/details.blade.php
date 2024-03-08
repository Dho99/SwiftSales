@extends('Layouts.index')
@section('content')
    <div class="bg-light mt-2 rounded py-3 px-4">
        <div class="profilePhotoDetails d-flex m-auto my-3 position-relative">
            <img src="{{ auth()->user()->profilePhoto ? auth()->user()->profilePhoto : asset('assets/images/Avatar/alex-suprun-1RAZcvtAxkk-unsplash.jpg') }}"
                class="img-fluid rounded d-flex" alt="" id="profilePhotoPreview">
            <div class="position-absolute top-50 start-50 translate-middle d-none" id="icon">
                <div class="d-flex">
                    {{-- <i class="bi bi-camera-fill"></i> --}}
                    <button class="btn btn-outline-primary" onclick="editProfilePhoto()">Edit Foto</button>
                    <button class="btn btn-danger d-none" onclick="resetPreview()" id="resetPhotoBtn">Reset Foto</button>
                    <input type="file" class="d-none" name="" id="profilePhotoInput">
                </div>
            </div>
        </div>
        <img src="" class="img-fluid border" id="preview" alt="">
        <div class="row">
            {{-- {{auth()->user()}} --}}
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="text" name="email" id="email" value="{{ $user->email }}" readonly
                        class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ $user->name }}" readonly
                        class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="telephone">Nomor Terdaftar</label>
                    <input type="text" name="telephone" id="telephone" value="{{ $user->telephone }}" readonly
                        class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="roles">Terdaftar Sebagai</label>
                    <input type="text" name="roles" id="roles" value="{{ $user->roles }}" disabled
                        class="form-control">
                </div>
            </div>
            <div id="passwordEdit" class="row m-0 p-0 w-100"></div>
            <div class="col-lg-12 row m-0 p-0">
                {{-- <div class="col-lg-3 col-md-6 col-12 order-lg-1 order-md-1 order-2">
                    <button class="btn btn-danger w-100 my-1" onclick="deleteAccount('{{ $user->id }}')"
                        data-id="{{ $user->id }}">Hapus Akun</button>
                </div> --}}
                <div class="col-lg-3 col-md-6 col-12 ms-auto order-lg-2 order-md-2 order-1">
                    <button class="btn btn-primary w-100 my-1" onclick="changeToEdit()" id="editBtn">Edit Akun</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const currentProfilePhoto = '{{ auth()->user()->profilePhoto }}';
        let uploadedImage = false;

        function editProfilePhoto() {
            $('#profilePhotoInput').click();
        }

        let passVal;

        function assignPass() {
            passVal = $('#password').val();
        };

        function validatePass() {
            const val = $('#confirmPassword').val();
            const errorMessage = $('.invalid-feedback');
            if (val !== passVal) {
                $('#confirmPassword').addClass('is-invalid');
                errorMessage.text('Password tidak sama, Masukkan password dengan benar dan teliti');
            } else {
                $('#confirmPassword').removeClass('is-invalid');
                errorMessage.text('');
                $('#editBtn').removeAttr('disabled');
            }
        };


        let click = 0;

        function changeToEdit() {
            click += 1;
            $('#passwordEdit').append(`
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="telephone">Ubah Password</label>
                    <input type="password" name="password" id="password" value="" onchange="assignPass()" class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="roles">Konfirmasi Password</label>
                    <input type="password" name="password" id="confirmPassword" oninput="validatePass()" value=""
                        class="form-control">
                    <div class="invalid-feedback"><div>
                </div>
            </div>
            `);

            $('.profilePhotoDetails').attr('id', 'profilePhoto');
            $('input').removeAttr('readonly');
            $('#profilePhoto').on('mouseenter', function() {
                $('#icon').removeClass('d-none');
                $(this).find('img').addClass('border border-primary');
            }).on('mouseleave', function() {
                $('#icon').addClass('d-none');
                $(this).find('img').removeClass('border border-primary');
            });
            $('#editBtn').attr('disabled', true);
            $('#editBtn').text('Simpan Perubahan');
            if (click > 1) {
                let imgFile;
                if (!uploadedImage) {
                    imgFile = currentProfilePhoto;
                } else {
                    imgFile = '/storage/uploads/profilePhoto/' + uploadedImage;
                }
                const emailInput = $('#email');
                const nameInput = $('#name');
                const telephoneInput = $('#telephone');

                // console.log(imgFile);
                if (imgFile.length < 1 && emailInput.val().length < 1 && nameInput.val().length < 1 &&
                    telephoneInput.val().length < 1) {
                    swalError('Data yang diperlukan tidak boleh kosong');
                } else {
                    const formData = new FormData();
                    const currUrl = '{{ url()->current() }}';
                    formData.append('_method', 'PUT');
                    formData.append('profilePhoto', imgFile);
                    formData.append('email', emailInput.val());
                    formData.append('name', nameInput.val());
                    formData.append('telephone', telephoneInput.val());
                    formData.append('password', passVal);

                    storeDataNoReset(currUrl, formData).then(function(response) {
                        swalSuccess(response.message);
                    }).catch(function(xhr, error) {
                        swalError(xhr.responseText);
                    });
                }
                $('input').attr('readonly', true);
                $('#editBtn').text('Edit Data');
                $('#passwordEdit').empty();
                $('#editBtn').attr('disabled', true);
                click = 0;
            }
        }



        $('#profilePhotoInput').on('change', function() {
            const file = $(this)[0].files[0];
            const reader = new FileReader();
            const url = "{{ route('uploadImage', ['dirname' => 'profilePhoto']) }}";
            const preview = $('#preview');
            if (file) {
                reader.readAsDataURL(file);
                reader.onload = function(event) {
                    $('#profilePhotoPreview').attr('src', event.target.result);
                    $('#resetPhotoBtn').removeClass('d-none');
                    const formData = new FormData();
                    formData.append("file", file);
                    storeDataNoReset(url, formData).then(function(response) {
                        uploadedImage = response.file;
                        console.log(uploadedImage);
                    }).catch(function(xhr, error) {
                        swalError(xhr.responseText);
                    });
                }
            }
        });

        function resetPreview() {
            $('#resetPhotoBtn').addClass('d-none');
            $('#profilePhotoPreview').removeAttr('src');
            $('#profilePhotoPreview').attr('src', currentProfilePhoto);
            const url = "{{ route('removeImage', ['dirname' => 'profilePhoto']) }}";
            const formData = new FormData()
            formData.append('filename', uploadedImage)
            storeDataNoReset(url, formData).then(function(response) {
                uploadedImage = currentProfilePhoto;
            }).catch(function(xhr, error) {
                swalError(xhr.responseText);
            });
        }

        function deleteAccount(id) {
            const url = '/user/' + id;
            swalConfirm('Apakah anda yakin akan menghapus data Akun ?').then(function(result) {
                if (result) {
                    swalConfirm('Semua data yang berkaitan dengan akun anda akan dihapus secara Permanen').then(
                        function(result) {
                            if (result) {
                                deleteData(url, '').then(function(response) {
                                    swalSuccess(response.message);
                                    setTimeout(() => {
                                        window.location.href = '/';
                                    }, 1000);
                                }).catch(function(xhr, error) {
                                    swalError(xhr.responseText);
                                });
                            }
                        });
                }
            });
        }
    </script>
@endpush
