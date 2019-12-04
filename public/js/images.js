$(document).ready(function() {
    $('#published_at').datetimepicker({
        minDate:0,
        minTime:0,
        format:'Y-m-d H:i:s P'
    });
    $('#birthdate').datetimepicker({
        maxDate:0,
        timepicker:false,
        format:'Y-m-d'
    });
})

var id = 0;

function sendForm(url, type, data, success) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: url,  //Server script to process data
        type: type,
        data: data,
        contentType: false,
        processData: false,
        //Ajax events
        success: success
    });
};

$('#image_upload').on('change', function(e){
    var formData = new FormData();
    formData.append('image', this.files[0]);
    var creatingSucces = function(res){
        $('#image').attr('src', res.image);
        id = res.id;
        $('#id').val(id);
    };
    var changePhotoSuccess = function(res) {
        $('#image').attr('src', res.image);
    }
    if(id > 0) {
        formData.append('id', + id );
        sendForm('/images/'+id, 'POST', formData, changePhotoSuccess);
    }
    else {
        var url = '/images';
        if ($('#form').attr('action')) url = $('#form').attr('action');
        sendForm(url, 'POST', formData, creatingSucces);
    }
});

