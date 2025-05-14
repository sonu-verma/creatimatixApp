var turf  = {
    listTable: "#turfListing",
    init: function (){
        var obj = this;
        $(document).on('change', '.uploadPhoto', function (e) {
            e.preventDefault();

            if(!$('.turfId').val()){
                alert("Please enter basic details first")
                return false;
            }
            console.log(1)
            var f = document.getElementById('uploadPhoto'),
                pt = $('.uploadProgress');
            pt.show();
            $('#packageImages .card-header .message-box').remove();
            _app.uploader({
                files: f,
                formData: {
                    id_turf: $('.turfId').val()
                },
                progressText: pt,
                processor: fileCreateURL,
                accessToken: app.getToken(),
                success: function (data) {
                    pt.hide();
                    $('.uploadedImages').prepend(data);
                },
                error: function (data, statusCode) {
                    var div = $('<div/>').addClass('message-box');
                    if (statusCode == 422) {
                        $.each(data.errors, function (k, v) {
                            $('<p>').text(v).addClass('text-danger').appendTo(div);
                        });
                    } else {
                        $('<p>').text('Error occurred. Try Again after page reload.').addClass('text-danger').appendTo(div);
                    }
                    $('#packageImages .card-header').append(div);
                    pt.hide();
                }
            });
            $(this).val('');
        });
    },
    disableSubmit: function (loader) {
        $('button[type=submit]').attr('disabled', true);
        // if (loader === true) {
        //     $('button[type=submit]').prepend(app.buttonLoader());
        // }
    },
    enableSubmit: function () {
        $('button[type=submit]').attr('disabled', false);
        // $('button[type=submit]').find('svg').remove();
    },
    removeImage: function (e, url) {
        var obj = this;
        turf.disableSubmit(true);
        $.ajax({
            url: url,
            type: 'POST',
            data: {},
            success: function (data) {
                console.log("data", data);
                $('.package_image_' + data.id).slideUp();
                setTimeout(function () {
                    $('.package_image_' + data.id).remove();
                }, 1000);
            },
            error: function (xhr, status, error) {
                console.log("Error:", xhr.responseText);
            },
            complete: function () {
                turf.enableSubmit();
            }
        });
        return false;
    },
    saveSportDetails: function (target) {
        // target.preventDefault();
        var $e = $('#frmSportDetails');
        $e.find('.has-danger').removeClass('has-danger');
        $e.find('.form-control-feedback').remove();
        var obj = this;
        obj.disableSubmit(true);
        if($e.find('#turfId').val() == '') {    
            alert("Please enter basic details first")
            return false;
        }

        $.ajax({
            url: $e.attr('action'),
            method: 'POST',
            data: $e.serialize(),
            success: function (data, status, xhr) {
                console.log("data", data);
                console.log("status", status);
                if (data.statusCode * 1 === 400) {
                    $.each(data.errors, function (i, v) {
                        var $fg = $e.find('[name="' + i + '"]').closest('.form-group');
                        $fg.addClass('has-danger');
                        $fg.append('<div class="form-control-feedback error">'+(v.toString()+'</div>'));
                        // _app.scroll($e.find('.has-danger:eq(0)'));
                    });
                } else {
                   $('.sportListDiv').html(data.html)
                   $("#id_sport").val('')
                   $("#capacity").val('')
                   $("#dimension").val('')
                   $("#rate_per_hour").val('')
                   $(".sportRules").val('')
                   $("#sportId").val('')
                   
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    console.log(xhr.responseJSON); // This will show the validation errors
                    $.each(xhr.responseJSON.errors, function (i, v) {
                        var $fg = $e.find('[name="' + i + '"]').closest('.form-group');
                        $fg.addClass('has-danger');
                        $fg.append('<div class="form-control-feedback error">'+(v.toString()+'</div>'));
                        // _app.scroll($e.find('.has-danger:eq(0)'));

                        $('html, body').animate(function(e){
                            scrollTop: e.find('.error').first().offset().top - 75
                        }, 'slow'); 
                    });
                }
            },
            complete: function () {
                obj.enableSubmit();
            }
        });
        
        return false;
    },
    editSport: function (sportId, url){
        var obj = this;
        obj.disableSubmit(true);
        $.ajax({
            url: url,
            type: 'GET',
            data: {id: sportId},
            success: function (data) {
                console.log("data", data);
                // console.log("decoded data", JSON.parse(data));
                if (data.statusCode * 1 === 200) {
                    $("#sportId").val(data.sport.id)
                    $("#id_sport").val(data.sport.id_sport)
                    $("#capacity").val(data.sport.capacity)
                    $("#dimension").val(data.sport.dimensions)
                    $("#rate_per_hour").val(data.sport.rate_per_hour)
                    $(".sportRules").val(data.sport.rules)
                    $("input[name='status'][value='" + data.sport.status + "']").prop('checked', true);
                    $('.cancelSportEdit').show();
                } else {
                    alert("Error occurred. Try Again after page reload.")
                }
            },
            error: function (xhr, status, error) {
                console.log("Error:", xhr.responseText);
            }
        });
        return false;
    },
    cancelSportEdit: function (e){
        e.preventDefault;
        $("#sportId").val('')
        $("#id_sport").val('')
        $("#capacity").val('')
        $("#dimension").val('')
        $("#rate_per_hour").val('')
        $(".sportRules").val('')
        $("input[name='status'][value='1']").prop('checked', true);
        $('.cancelSportEdit').hide();
    }
};


$(function () {
    turf.init();
});


document.getElementById('turfName').addEventListener('input', function () {

    let name = this.value;
    
    let slug = name
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')      // Remove invalid chars
        .replace(/\s+/g, '-')              // Replace spaces with -
        .replace(/-+/g, '-');              // Collapse multiple -
        $('.turfSlug').val(slug);
});