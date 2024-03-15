$(document).ready(function () {
    $('#newDatabase').submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: '/packages/exercises/store/database',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            processData: false,
            contentType: false,
            success: function (response) {
                $("#database").append(`<option value="${response.name}" selected>${response.name}</option>`);
                $("#database").trigger("change");
                $('#database_id').val(response.id);
                $("#name").val("");
                $("#sql_file").val("");
                $('#modal-create-database').modal('hide');
            },
            error: function (xhr, status, error) {
                var errorMessage = '';
                $.each(xhr.responseJSON.errors, function (key, error) {
                    errorMessage += error[0] + '\n';
                });
                alert(errorMessage);
            }
        });
    });
});
