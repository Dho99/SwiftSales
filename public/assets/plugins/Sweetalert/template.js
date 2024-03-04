function swalBeforeSend(){
    Swal.fire({
        title: "Mohon tunggu sebentar ...",
        icon: "info"
    });
}

function swalError(message){
    Swal.fire({
        title: "Terjadi Kesalahan",
        text: message,
        icon: "error"
    });
}

function swalSuccess(message){
    Swal.fire({
        title: "Kerja bagus !",
        text: message,
        icon: "success"
    });
}


function swalConfirm(message){
    return new Promise(function(resolve, reject){
        Swal.fire({
            title: "Apakah anda benar - benar yakin ?",
            text: message,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya"
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "Deleted!",
                text: "Data telah berhasil dihapus !",
                icon: "success"
              });
                resolve(true);
            }else{
                reject(false);
            }
          });
    });
}

function swalConfirmWithoutDelete(message){
    return new Promise(function(resolve, reject){
        Swal.fire({
            title: "Apakah anda benar - benar yakin ?",
            text: message,
            icon: "question",
            showCancelButton: true,
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya"
          }).then((result) => {
            if (result.isConfirmed) {
                resolve(true);
            }else{
                reject(false);
            }
          });
    });
}
