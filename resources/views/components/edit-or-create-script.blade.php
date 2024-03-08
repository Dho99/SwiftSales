<script>
    let uploadedFile = [];

    $().ready(function() {
        $('#supplier, #category').select2({
            theme: 'bootstrap-5'
        });
        $('#buyPrice, #sellPrice').mask('000.000.000.000.000,000', {
            reverse: true
        });
    });

    Dropzone.options.myDropzone = {
    init: function() {
        var myDropzone = this; // Merujuk ke instance Dropzone saat ini

        this.on("success", function(file, response) {
            // Memasukkan nama file yang diunggah ke dalam array uploadedFile
            uploadedFile.push('/storage/uploads/productImage/' + response.file);
             console.log(response.file);
            // Membuat tombol hapus
            var removeButton = Dropzone.createElement(
                "<button class='btn btn-danger' type='button'>Hapus</button>");

                // Menambahkan event listener untuk tombol hapus
                removeButton.addEventListener("click", function() {
                const formData = new FormData();
                 uploadedFile.filter(file => {
                    formData.append('filename', file);
                });
                $.ajax({
                    url: '/image/remove',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Hapus file dari tampilan Dropzone hanya jika berhasil dihapus dari server
                        myDropzone.removeFile(file);
                        removeFromArray(response.file);
                    },
                    error: function(xhr, status, error) {
                        swalErrror(xhr.responseText)
                        console.log(error.message);
                        // Handle error jika terjadi kesalahan
                    }
                });
            });

            // Menambahkan tombol hapus ke tampilan file di Dropzone
            file.previewElement.appendChild(removeButton);
        });

        this.on("error", function(file, xhr) {
            // let message = xhr.responseText;
            if (file.previewElement) {
                file.previewElement.classList.add("dz-error");
                console.log(xhr.responseText)
                swalError(xhr.responseText);
                // if (typeof message !== "string" && message.error) {
                //     message = message.error;
                // }
                // for (let node of file.previewElement.querySelectorAll(
                //         "[data-dz-errormessage]"
                //     )) {
                //     node.textContent = message;
                // }
            }
        });
    }
};


    function removeFromArray(file) {
        const arrayIndex = uploadedFile.indexOf(file);
        uploadedFile.splice(arrayIndex, 1);
        console.log(uploadedFile);
    }


    $('#sellPrice').on('input', function() {
        let buyprice = parseInt($('#buyPrice').cleanVal());
        let sellPrice = parseInt($('#sellPrice').cleanVal());
        if (sellPrice == 0) {
            $(this).val('');
        }
        if (sellPrice <= buyprice || isNaN(sellPrice)) {
            $('#sellPrice').addClass('is-invalid');
            $('.invalid-feedback').addClass('d-block');
            $('button#submitbutton').attr('disabled', 'disabled');
        } else {
            $('#sellPrice').removeClass('is-invalid');
            $('.invalid-feedback').removeClass('d-block');
            $('button#submitbutton').removeAttr('disabled', 'disabled');
        }
    });
    // let url =  $('#submitbutton').attr('data-link');

    function submitForm(url) {
        let formData = new FormData();

        formData.append('code', $('#code').val());
        formData.append('name', $('#name').val());
        formData.append('supplierId', $('#supplier').val());
        formData.append('categoryId', $('#category').val());
        formData.append('expiredDate', $('#expiredDate').val());
        formData.append('buyPrice', $('#buyPrice').cleanVal());
        formData.append('sellPrice', $('#sellPrice').cleanVal());
        formData.append('images', JSON.stringify(uploadedFile));
        // formData.append('actualImages', uploadedFile);
        formData.append('description', $('#x').val());

        storeData(url, formData).then(function(response) {
            swalSuccess(response.message);
            uploadedFile = [];
        }).catch(function(xhr, error) {
            swalError(xhr.responseText);
            console.log(error);
        });

    }
</script>
