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
            <div class="col-lg-12 row m-0 p-0">
                <div class="col-lg-3 col-md-6 col-12 ms-auto">
                    <button class="btn btn-primary w-100" onclick="changeToEdit()" id="editBtn">Edit Data</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const currentProfilePhoto = '{{ auth()->user()->profilePhoto }}';
        let uploadedImage;

        function editProfilePhoto() {
            $('#profilePhotoInput').click();
        }


        let click = 0;

        function changeToEdit() {
            click += 1;
            $('.profilePhotoDetails').attr('id', 'profilePhoto');
            $('input').removeAttr('readonly');
            $('#profilePhoto').on('mouseenter', function() {
                $('#icon').removeClass('d-none');
                $(this).find('img').addClass('border border-primary');
            }).on('mouseleave', function() {
                $('#icon').addClass('d-none');
                $(this).find('img').removeClass('border border-primary');
            });
            $('#editBtn').text('Simpan Perubahan');
            if (click > 1) {
                let imgFile;
                if(uploadedImage.length < 1){
                    imgFile = currentProfilePhoto;
                }else{
                    imgFile = '/storage/uploads/profilePhoto/'+uploadedImage;
                }
                const emailInput = $('#email');
                const nameInput = $('#name');
                const telephoneInput = $('#telephone');

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

                    storeDataNoReset(currUrl, formData).then(function(response) {
                        swalSuccess(response.message);
                    }).catch(function(xhr, error) {
                        swalError(xhr.responseText);
                    });
                }
                $('input').attr('readonly', true);
                $('#editBtn').text('Edit Data');
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
    </script>
@endpush