/**
 * Created by Muhammad Ali on 30/5/17.
 */
var app = {
    ajaxElement: null,
    init: function () {
        this.ajaxSettings();

        $(document).on('click', 'body', function (e) {
            app.ajaxElement = $(e.target);

        });

    },
    getTodaySMSCount: function (){
        var url = ajaxUrl+'/todaySMSCount';
        $.get(url, function (response, status, xhr) {
            $('.smsCount').text(response.message);
        }).always(function () {
            //btn.attr('disabled', false);
        });
        return false;
    },
    getTodaySMS: function (){
        var url = ajaxUrl+'/todaySMS';
        $('.main-friend-list').html("<center><b>Loadings Messages... Please Wait...</b></center>");
        $.get(url, function (response, status, xhr) {
            $('.main-friend-list').html(response.message);
        }).always(function () {
            //btn.attr('disabled', false);
        });
        return false;
    },
    /**
     * Get CSRF token of the page
     * @returns Returns the token
     */
    getToken: function () {
        return $('[name="csrf_token"]').attr('content');
    },
    /**
     * Attach CSRF token to header of all ajax requests
     * @returns void
     */
    ajaxSettings: function () {
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': this.getToken()},
            beforeSend: function () {

                /*var anchor = app.ajaxElement.closest('a');
                if (anchor.find(app.ajaxElement).length) {
                    alert('a');
                }
                var button = app.ajaxElement.closest('button');
                if (button.find(app.ajaxElement).length) {
                    alert('b');
                }*/


            }
        });
        $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
            if (settings.url.search(adminUrl) >= 0) {
                httpMessages.byHttpStatus(jqxhr.status);
            }
        });
    },
    /**
     * This function can use to display a notification
     * @param title Specify title of the notification
     * @param message Specify message for the notification
     * @param type Type of notification. Eg.: info, success, danger, warning, primary
     * @param icon Specify the icon you need to display with notification
     * @param from Specify the location of placement. Eg.: top, bottom
     * @param align Specify the location of placement. Eg.: center, left, right
     * @param animIn Specify coming animation. [refer CSS3 animation library - animate.css]
     * @param animOut Specify going animation. [refer CSS3 animation library - animate.css]
     */
    notify: function (title, message, type, icon, from, align, animIn, animOut) {

        from = (typeof from === typeof undefined) ? 'top' : from;
        align = (typeof align === typeof undefined) ? 'right' : align;
        icon = (typeof icon === typeof undefined) ? '' : icon;
        type = (typeof type === typeof undefined) ? 'info' : type;
        animIn = (typeof animIn === typeof undefined) ? 'animated fadeIn' : animIn;
        animOut = (typeof animOut === typeof undefined) ? 'animated fadeOut' : animOut;

        var titleHtml = '';
        if (title !== '') {
            titleHtml = '<span data-growl="title"></span>: ';
        }

        $.growl({
            icon: icon,
            title: title + ' ',
            message: message,
            url: ''
        }, {
            element: 'body',
            type: type,
            allow_dismiss: true,
            placement: {
                from: from,
                align: align
            },
            offset: {
                x: 30,
                y: 30
            },
            spacing: 10,
            z_index: 999999,
            delay: 2500,
            timer: 5000,
            url_target: '_blank',
            mouse_over: false,
            animate: {
                enter: animIn,
                exit: animOut
            },
            icon_type: 'class',
            template: '<div data-growl="container" class="alert" role="alert">' +
            '<button type="button" class="close" data-growl="dismiss">' +
            '<span aria-hidden="true">&times;</span>' +
            '<span class="sr-only">Close</span>' +
            '</button>' +
            '<span data-growl="icon"></span>' +
            titleHtml +
            '<span data-growl="message"></span>' +
            '<a href="#" data-growl="url"></a>' +
            '</div>'
        });
    },

    /**
     * Returns random string
     * @param length Specify the length required for string
     * @returns {string}
     */
    rand: function (length) {
        length = (typeof length === typeof undefined) ? 6 : length;
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < length; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return text;
    },
    randomBox: function (length, target) {
        var code = this.rand(length);
        $(target).val(code.toUpperCase());
    },
    

    showModalAlert: function (type, id, body, label) {
        if (typeof label === typeof undefined) {
            label = 'Error!';
        }
        $(id).find('.modal-body').prepend('<div class="alert alert-' + type + '">\n' +
            '  <strong>' + label + '</strong> ' + body + '\n' +
            '</div>');
    },
    clearModalAlerts: function (id) {
        $(id).find('.modal-body .alert').remove();
    },
    getInputFeedback: function (msg) {
        return '<div class="form-control-feedback">' + msg + '</div>';
    },
    scroll: function (e) {
        $('html,body').animate({
                scrollTop: $(e).offset().top - 75
            },
            'slow');
    },
    slugify: function (string) {
        return string
            .toString()
            .trim()
            .toLowerCase()
            .replace(/\s+/g, "-")
            .replace(/[^\w\-]+/g, "")
            .replace(/\-\-+/g, "-")
            .replace(/^-+/, "")
            .replace(/-+$/, "");
    },
    buttonLoader: function () {
        return '<svg id="loader2" viewBox="0 0 100 100">\n' +
            '                <circle id="circle-loader2" cx="50" cy="50" r="45"></circle>\n' +
            '            </svg>';
    }
};

var messages = {
    message: {
        saved: {
            title: '',
            message: 'Data saved successfully!'
        },
        updated: {
            title: '',
            message: 'Data updated successfully!'
        },
        deleted: {
            title: '',
            message: 'Data deleted successfully!'
        },
        warning: {
            title: '',
            message: 'Data deleted successfully!'
        },
        undone: {
            title: '',
            message: 'Data recovered successfully!'
        },
        approve: {
            title: '',
            message: 'Data approved successfully!'
        },
        deny: {
            title: '',
            message: 'Data denied successfully!'
        },
        error: {
            title: '',
            message: 'Try again!',
        },
        default: {
            title: '',
            message: 'Success!',
        }
    },
    byStatus: function (status, msg, title) {
        switch (status) {
            case 1:
                this.saved(title, msg)
                break;
            case 2:
                this.updated(title, msg);
                break;
            case 3:
                this.deleted(title, msg);
                break;
            case 4:
                this.undone(title, msg);
                break;
            case 5:
                this.approve(title, msg);
                break;
            case 6:
                this.deny(title, msg);
                break;
            case 0:
                this.error(title, msg);
                break;
            default:
                this.default(title, msg);
                break;
        }
    },
    saved: function (title, msg) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.saved.message : msg;
        title = (typeof title === typeof undefined || title === '') ? this.message.saved.title : title;
        app.notify(title, msg, 'success');
    },
    updated: function (title, msg) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.updated.message : msg;
        title = (typeof title === typeof undefined || title === '') ? this.message.updated.title : title;
        app.notify(title, msg, 'info');
    },
    deleted: function (title, msg) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.deleted.message : msg;
        title = (typeof title === typeof undefined || title === '') ? this.message.deleted.title : title;
        app.notify(title, msg, 'warning');
    },
    warning: function (title, msg) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.warning.message : msg;
        title = (typeof title === typeof undefined || title === '') ? this.message.warning.title : title;
        app.notify(title, msg, 'warning');
    },
    undone: function (title, msg) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.undone.message : msg;
        title = (typeof title === typeof undefined || title === '') ? this.message.undone.title : title;
        app.notify(title, msg, 'info');
    },
    approve: function (title, msg) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.approve.message : msg;
        title = (typeof title === typeof undefined || title === '') ? this.message.approve.title : title;
        app.notify(title, msg, 'info');
    },
    deny: function (title, msg) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.deny.message : msg;
        title = (typeof title === typeof undefined || title === '') ? this.message.deny.title : title;
        app.notify(title, msg, 'info');
    },
    error: function (title, msg) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.error.message : msg;
        title = (typeof title === typeof undefined || title === '') ? this.message.error.title : title;
        app.notify(title, msg, 'danger');
    },
    default: function (title, msg) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.default.message : msg;
        title = (typeof title === typeof undefined || title === '') ? this.message.default.title : title;
        app.notify(title, msg, 'info');
    },
}

var httpMessages = {
    message: {
        badRequest: {
            title: '[400]',
            message: 'The server cannot process your request.',
        },
        unauthorized: {
            title: '[401]',
            message: 'You are not allowed to perform this request.',
        },
        forbidden: {
            title: '[403]',
            message: 'You are not allowed to perform this request.',
        },
        notFound: {
            title: '[404]',
            message: 'Requested resource cannot be found.',
        },
        methodNotAllowed: {
            title: '[405]',
            message: 'Requested action not allowed.',
        },
        internalServerError: {
            title: '[500]',
            message: 'The server encountered an internal error.',
        },
        badGateway: {
            title: '[502]',
            message: 'The server encountered a temporary error.',
        },
        serviceUnavailable: {
            title: '[503]',
            message: 'Service temporarily unavailable.',
        },
        gatewayTimeout: {
            title: '[504]',
            message: 'A gateway timeout occurred.',
        },
        unknown: {
            title: '',
            message: 'Unknown error occurred during your request.',
        },

    },

    byHttpStatus: function (status) {
        status = status * 1;
        switch (status) {
            case 400:
                httpMessages.badRequest();
                break;
            case 401:
                httpMessages.unauthorized();
                break;
            case 403:
                httpMessages.forbidden();
                break;
            case 404:
                httpMessages.notFound();
                break;
            case 405:
                httpMessages.methodNotAllowed();
                break;
            case 500:
                httpMessages.internalServerError();
                break;
            case 502:
                httpMessages.badGateway();
                break;
            case 503:
                httpMessages.serviceUnavailable();
                break;
            case 504:
                httpMessages.gatewayTimeout();
                break;
            default:
                //httpMessages.unknown('', '', status)
                break;
        }
    },
    badRequest: function (msg, title) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.badRequest.message : msg;
        title = (typeof title === typeof undefined || title === '') ? this.message.badRequest.title : title;
        app.notify(title, msg, 'danger');
    },
    unauthorized: function (msg, title) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.unauthorized.message : msg;
        title = (typeof title === typeof undefined) ? this.message.unauthorized.title : title;
        app.notify(title, msg, 'danger');
    },
    forbidden: function (msg, title) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.forbidden.message : msg;
        title = (typeof title === typeof undefined) ? this.message.forbidden.title : title;
        app.notify(title, msg, 'danger');
    },
    notFound: function (msg, title) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.notFound.message : msg;
        title = (typeof title === typeof undefined) ? this.message.notFound.title : title;
        app.notify(title, msg, 'danger');
    },
    methodNotAllowed: function (msg, title) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.methodNotAllowed.message : msg;
        title = (typeof title === typeof undefined) ? this.message.methodNotAllowed.title : title;
        app.notify(title, msg, 'danger');
    },
    internalServerError: function (msg, title) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.internalServerError.message : msg;
        title = (typeof title === typeof undefined) ? this.message.internalServerError.title : title;
        app.notify(title, msg, 'danger');
    },
    badGateway: function (msg, title) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.badGateway.message : msg;
        title = (typeof title === typeof undefined) ? this.message.badGateway.title : title;
        app.notify(title, msg, 'danger');
    },
    serviceUnavailable: function (msg, title) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.serviceUnavailable.message : msg;
        title = (typeof title === typeof undefined) ? this.message.serviceUnavailable.title : title;
        app.notify(title, msg, 'danger');
    },
    gatewayTimeout: function (msg, title) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.gatewayTimeout.message : msg;
        title = (typeof title === typeof undefined) ? this.message.gatewayTimeout.title : title;
        app.notify(title, msg, 'danger');
    },
    unknown: function (msg, title, status) {
        msg = (typeof msg === typeof undefined || msg === '') ? this.message.unknown.message : msg;
        app.notify('[' + status + ']', msg, 'warning');
    }
};

var _ajax = {
    confirmDelete: function (url, callback) {
        swal({
                title: "Are you sure?",
                text: "Your will not be able to recover this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                showLoaderOnConfirm: (url === '#') ? false : true
            },
            function () {
                if (url === '#') {
                    swal.close();
                    callback();
                } else {
                    $.post(url, function (response, status, xhr) {
                        if (response.status === 200) {
                            swal.close();
                            if (typeof callback !== typeof undefined) {
                                callback();
                            }
                        }
                    });
                }

            });
    },
    confirmAction: function (url, title, text, callback) {
        if (typeof title === typeof undefined) {
            title = "Are you sure?";
        }
        if (typeof text == typeof undefined) {
            title = "Do you really want to continue?";
        }
        swal({
                title: title,
                text: text,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Okay",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                $.post(url, function (response, status, xhr) {
                    if (response.status === 200 || response.status === 1) {
                        swal.close();
                        if (typeof callback !== typeof undefined) {
                            callback(response);
                        }
                    } else {
                        swal.close();
                        messages.error('Error', response.message);
                    }
                });
            });
    },
    prompt: function (title, description, url, callback) {
        swal({
            title: title,
            text: description,
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            inputPlaceholder: "Type here..."
        }, function (inputValue) {
            if (inputValue === false) {
                return false;
            }
            if (inputValue === "") {
                swal.showInputError("You need to write something!");
                return false;
            }
            $.post(url, {inputData: inputValue}, function (response, status, xhr) {
                if (response.status === 200) {
                    swal.close();
                    if (typeof callback !== typeof undefined) {
                        callback(response);
                    }
                }
            });
        });
    },
    alert: function (msg) {
        swal(msg);
    }
}

var _app = _app || {};

(function (o) {
    "use strict";
    var ajax, getFormData, setProgress;
    ajax = function (data) {
        console.log("data", data);
        // return false;
        var xmlhttp = new XMLHttpRequest(), uploaded;
        xmlhttp.addEventListener('readystatechange', function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    //uploaded = JSON.parse(this.response);
                    uploaded = this.response;
                    if (typeof o.options.success === 'function') {
                        o.options.success(uploaded);
                    }
                } else {
                    if (typeof o.options.error === 'function') {
                        o.options.error(JSON.parse(this.response), this.status);
                    }
                }
            }
        });

        xmlhttp.upload.addEventListener("progress", function (event) {
            var percent;
            if (event.lengthComputable === true) {
                percent = Math.round((event.loaded / event.total) * 100);
                setProgress(percent);
            }

        });

        if (o.options.progressBar !== undefined) {
            o.options.progressBar.style.width = 0;
        }
        if (o.options.progressText !== undefined) {
            o.options.progressText.innerText = 0;
        }
        if ((o.options.progressText).length) {
            o.options.progressText.find('span').text(0 + '%');
        }

        xmlhttp.open("post", o.options.processor);
        xmlhttp.send(data);
    };

    getFormData = function (source) {
        var data = new FormData(), i;
        if (source.length <= 0) {
            return false;
        } else {
            data.append("_token", o.options.accessToken);
            if (o.options.formData !== undefined) {
                $.each(o.options.formData, function (k, v) {
                    data.append(k, v);
                });
            }
            for (i = 0; i < source.length; i++) {
                data.append('images[]', source[i]);
            }
            return data;
        }
    };

    setProgress = function (value) {
        if (o.options.progressBar !== undefined) {
            o.options.progressBar.style.width = value ? value + "%" : 0;
        }
        /*if (o.options.progressText !== undefined) {
            o.options.progressText.innerText = value ? value + "%" : 0;
        }*/
        if ((o.options.progressText).length) {
            o.options.progressText.find('span').text(value ? value + "%" : 0 + '%');
        }
    };

    o.uploader = function (options) {
        o.options = options;
        if (o.options.files !== undefined) {
            var imageFormDataValue = getFormData(o.options.files.files);
            if (imageFormDataValue === false) {
                //alert("No Files Selected");
            }
            else {
                ajax(imageFormDataValue);
            }
        }
    };

}(_app));

$(document).ready(function () {
    "use strict";
    /*activate select2 if element is available*/
    var $countrySelect = $('[data-resource=country]');
    if ($countrySelect.length) {
        var iso = 0;
        if (typeof $countrySelect.attr('data-iso') !== typeof undefined) {
            iso = 1;
        }
        $countrySelect.select2({
            ajax: {
                url: ajaxUrl + "/country",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function (params) {

                    return {
                        iso: iso,
                        term: params.term
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            page: (params.page * data.per_page) < data.total
                        }
                    };
                },
                cache: false
            },
            initSelection: function (element, callback) {
                setTimeout(function () {
                    $.ajax(ajaxUrl + "/country", {
                        dataType: 'json',
                        method: 'post',
                        data: {term: 'United States'}
                    }).done(function (data) {

                        callback(data.data[0]);
                    });
                }, 1000);

            },
            minimumInputLength: 1
        });
    }
    var $stateSelect = $('[data-resource=state]');
    var $citySelect = $('[data-resource=city]');
    if ($stateSelect.length) {
        $stateSelect.select2({
            ajax: {
                url: ajaxUrl + "/state",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            page: (params.page * data.per_page) < data.total
                        }
                    };
                },
                cache: false
            },
            minimumInputLength: 1
        }).on('change', function () {

            if ($citySelect.length) {
                $.ajax({
                    url: ajaxUrl + '/city/' + $(this).val(),
                    type: 'post',
                    success: function (data) {
                        console.log(data)
                        $citySelect.empty();
                        $.each(data.data, function (key, value) {
                            $citySelect.append($("<option></option>").attr("value", value.id).text(value.text));
                        });
                        $citySelect.select2();
                    }
                });
            }
        });
    }

    if ($citySelect.length) {
        $citySelect.select2({
            ajax: {
                url: ajaxUrl + "/city",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            page: (params.page * data.per_page) < data.total
                        }
                    };
                },
                cache: false
            },
            minimumInputLength: 1
        });
    }

    var $userSelect = $('[data-resource=user]');
    var user_type = $('#order_type').val();
    if ($userSelect.length) {
        $userSelect.select2({
            ajax: {
                url: ajaxUrl + "/users",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function (params) {
                    return {
                        term: params.term,
                        user_type: user_type
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            page: (params.page * data.per_page) < data.total
                        }
                    };
                },
                cache: false
            },
            minimumInputLength: 1,
            dropdownParent: (typeof $userSelect.attr('data-parent') == typeof undefined && $userSelect.attr('data-parent') !== '') ? '' : $($userSelect.attr('data-parent')),
            templateResult: function (user) {
                if (!user.id) {
                    return user.first_name + ' ' + user.last_name;
                }
                var $state = $(
                    '<span clas="user-list">' + user.full_name + '<em>' + user.email + '</em></span>'
                );
                return $state;
            },
            templateSelection: function (user) {
                if (!user.id) {
                    return 'Select User';
                }
                var $state = $(
                    '<span>' + user.full_name + ' (' + user.email + ')</span>'
                );
                if (typeof user.full_name === typeof undefined && typeof user.email === typeof undefined) {
                    $state = $(
                        '<span>' + user.text + '</span>'
                    );
                }

                return $state;
            }
        }).on('change', function () {
            //alert($(this).val());
        });
        $userSelect.change();
    }

    var $productSelect = $('[data-resource=product]');
    var order_type = $('#order_type').val();
    if ($productSelect.length) {
        $productSelect.select2({
            placeholder: 'Select product or package',
            ajax: {
                url: ajaxUrl + "/products",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function (params) {
                    return {
                        term: params.term,
                        order_type:order_type,
                        state : $('#order_state').val()
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    var nData = $.map(data.data, function (obj) {
                        obj.id = obj.sku; // replace pk with your identifier
                        return obj;
                    });

                    return {
                        results: nData,
                        pagination: {
                            page: (params.page * data.per_page) < data.total
                        }
                    };
                },
                cache: false
            },
            minimumInputLength: 1,
            dropdownParent: (typeof $productSelect.attr('data-parent') == typeof undefined && $productSelect.attr('data-parent') !== '') ? '' : $($productSelect.attr('data-parent')),
            templateResult: function (product) {
                if (!product.id) {
                    return product.sku;
                }
                var $state = $(
                    '<span clas="user-list">[' + (product.source).toUpperCase() + '] -> ' + product.name + ' #ID: ' + product.id + '</span>'
                );
                return $state;
            },
            templateSelection: function (product) {
                if (!product.id) {
                    return 'Select product or package';
                }
                var $state = $(
                    '<span>[' + (product.source).toUpperCase() + '] -> ' + product.name + ' #ID' + product.id + '</span>'
                );
                if (typeof product.name === typeof undefined && typeof product.id === typeof undefined) {
                    $state = $(
                        '<span>[' + (product.source).toUpperCase() + '] -> ' + product.name + '</span>'
                    );
                }

                return $state;
            }
        }).on('change', function () {

        });
        $productSelect.change();
    }

    var $productOnlySelect = $('[data-resource=product-only]');
    if ($productOnlySelect.length) {
        $productOnlySelect.select2({
            placeholder: 'Select product',
            ajax: {
                url: ajaxUrl + "/productsOnly",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function (params) {
                    return {
                        term: params.term,
                        state : $('#order_state').val()
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    var nData = $.map(data.data, function (obj) {
                        obj.id = obj.sku; // replace pk with your identifier
                        return obj;
                    });

                    return {
                        results: nData,
                        pagination: {
                            page: (params.page * data.per_page) < data.total
                        }
                    };
                },
                cache: false
            },
            minimumInputLength: 1,
            dropdownParent: (typeof $productOnlySelect.attr('data-parent') == typeof undefined && $productOnlySelect.attr('data-parent') !== '') ? '' : $($productOnlySelect.attr('data-parent')),
            templateResult: function (product) {
                if (!product.id) {
                    return product.sku;
                }
                var $state = $(
                    '<span class="user-list">[' + (product.source).toUpperCase() + '] -> ' + product.name + ' #ID: ' + product.id + '</span>'
                );
                return $state;
            },
            templateSelection: function (product) {
                if (!product.id) {
                    return 'Select product';
                }
                var $state = $(
                    '<span>[' + (product.source).toUpperCase() + '] -> ' + product.name + ' #ID' + product.id + '</span>'
                );
                if (typeof product.name === typeof undefined && typeof product.id === typeof undefined) {
                    $state = $(
                        '<span>[' + (product.source).toUpperCase() + '] -> ' + product.name + '</span>'
                    );
                }

                return $state;
            }
        }).on('change', function () {
            //alert($(this).val());
        });
        $productOnlySelect.change();
    }

    var $orders = $('[data-resource=orders]');
    if ($orders.length) {
        $orders.select2({
            placeholder: 'Select order',
            ajax: {
                url: ajaxUrl + "/orders",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function (params) {
                    return {
                        term: params.term,
                        user : $('#ddlUser').val()
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    var nData = $.map(data.data, function (obj) {
                        obj.id = obj.reference; // replace pk with your identifier
                        return obj;
                    });

                    return {
                        results: nData,
                        pagination: {
                            page: (params.page * data.per_page) < data.total
                        }
                    };
                },
                cache: false
            },
            minimumInputLength: 1,
            dropdownParent: (typeof $orders.attr('data-parent') == typeof undefined && $orders.attr('data-parent') !== '') ? '' : $($orders.attr('data-parent')),
            templateResult: function (order) {
                if (!order.id) {
                    return order.first_name + ' ' + order.last_name;
                }
                var $state = $(
                    '<span class="user-list">[' + order.reference + '] -> ' + order.first_name + ' ' + order.last_name + '</span>'
                );
                return $state;
            },
            templateSelection: function (order) {
                if (!order.id) {
                    return 'Select order';
                }
                var $state = $(
                    '<span>[' + order.reference + '] -> ' + order.first_name + ' ' + order.last_name + '</span>'
                );
                if (typeof order.first_name === typeof undefined && typeof order.reference === typeof undefined) {
                    $state = $(
                        '<span>' + order.text + '</span>'
                    );
                }
                return $state;
            }
        }).on('change', function () {
            //alert($(this).val());
        });
        $orders.change();
    }

    var $skuFamily = $('[data-resource=sku-family]');
    if ($skuFamily.length) {
        $skuFamily.select2({
            placeholder: 'Select family',
            ajax: {
                url: ajaxUrl + "/skuFamilies",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    var nData = $.map(data.data, function (obj) {
                        obj.id = obj.fid; // replace pk with your identifier
                        return obj;
                    });

                    return {
                        results: nData,
                        pagination: {
                            page: (params.page * data.per_page) < data.total
                        }
                    };
                },
                cache: false
            },
            minimumInputLength: 1,
            dropdownParent: (typeof $skuFamily.attr('data-parent') == typeof undefined && $skuFamily.attr('data-parent') !== '') ? '' : $($skuFamily.attr('data-parent')),
            templateResult: function (data) {
                //console.log(data);
                if (!data.fid) {
                    return data.fid;
                }
                var $state = $(
                    '<span class="user-list">' + data.sku_family_name + '</span>'
                );
                return $state;
            },
            templateSelection: function (data) {
                if (!data.fid) {
                    return 'Select family';
                }
                var $state = $(
                    '<span>' + data.sku_family_name + '</span>'
                );
                if (typeof data.sku_family_name === typeof undefined && typeof data.fid === typeof undefined) {
                    $state = $(
                        '<span>' + data.sku_family_name + '</span>'
                    );
                }
                return $state;
            }
        }).on('change', function () {
            //alert($(this).val());
        });
        $skuFamily.change();
    }

    var $productAttributeSelect = $('[data-resource=product_attribute]');
    //alert($productAttributeSelect.length)
    if ($productAttributeSelect.length) {
        $productAttributeSelect.each(function () {
            var $sb = $(this);
            $sb.select2({
                placeholder: 'Select product',
                ajax: {
                    url: ajaxUrl + "/product/attribute",
                    dataType: 'json',
                    delay: 250,
                    method: 'post',
                    data: function (params) {
                        return {
                            term: params.term,
                            state : $('#order_state').val()
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        var nData = $.map(data.data, function (obj) {
                            //obj.id = obj.sku; // replace pk with your identifier
                            return obj;
                        });

                        return {
                            results: nData,
                            pagination: {
                                page: (params.page * data.per_page) < data.total
                            }
                        };
                    },
                    cache: false
                },
                minimumInputLength: 1,
                dropdownParent: (typeof $sb.attr('data-parent') == typeof undefined && $sb.attr('data-parent') !== '') ? '' : $($sb.attr('data-parent')),
                templateResult: function (product) {
                    if (!product.id) {
                        return product.sku;
                    }
                    var status=(product.status==0?'<span class="text-danger">Inactive</span>':'Active');
                    var $state = $(
                        '<span clas="user-list">' + product.name + ' (' + product.attribute_info + ')  ProductID : '+ product.product_sku +' SKU : '+ product.sku +'</span> '+status
                    );
                    return $state;
                },
                templateSelection: function (product) {
                    if (!product.id) {
                        return 'Select product';
                    }
                    if(typeof product.name == typeof undefined){
                        product.name = product.text;
                    }
                    var $state = $(
                        '<span>' + product.name + ' (' + product.attribute_info + ') ProductID : '+ product.id +' SKU : '+ product.sku +'</span>'
                    );
                    if(typeof product.attribute_info == typeof undefined){
                        $state = $(
                            '<span>' + product.name + ' ProductID : '+ product.id +' SKU : '+ product.sku +'</span>'
                        );
                    }
                    if (typeof product.name === typeof undefined && typeof product.id === typeof undefined) {

                        $state = $(
                            '<span>' + product.name + ' (' + product.attribute_info + ') ProductID : '+ product.id +' SKU : '+ product.sku +'</span>'
                        );
                    }
                    return $state;
                }
            }).on('change', function () {
                //alert($(this).val());
            }).on('select2:open', function(){
                var $md = $($(this).attr('data-parent'));
                if($md.hasClass('modal')){
                    var tr = $(this).closest('tr').position();
                    var el = $(this).position();
                    $('.select2-dropdown--below').parent().css('top', (tr.top + 190));
                    $('.select2-dropdown--above').parent().css('top', (tr.top + 80));
                    $('.select2-dropdown--below, .select2-dropdown--above').parent().css('position', 'fixed');
                }
            });
            $sb.change();
        });
    }

    var $productAttributeSelectWOState = $('[data-resource=product_attribute_wostate]');
    //alert($productAttributeSelect.length)
    if ($productAttributeSelectWOState.length) {
        $productAttributeSelectWOState.each(function () {
            var $sb = $(this);
            $sb.select2({
                placeholder: 'Select product',
                ajax: {
                    url: ajaxUrl + "/product/attribute",
                    dataType: 'json',
                    delay: 250,
                    method: 'post',
                    data: function (params) {
                        return {
                            term: params.term,
                            state : 0
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        var nData = $.map(data.data, function (obj) {
                            //obj.id = obj.sku; // replace pk with your identifier
                            return obj;
                        });

                        return {
                            results: nData,
                            pagination: {
                                page: (params.page * data.per_page) < data.total
                            }
                        };
                    },
                    cache: false
                },
                minimumInputLength: 1,
                dropdownParent: (typeof $sb.attr('data-parent') == typeof undefined && $sb.attr('data-parent') !== '') ? '' : $($sb.attr('data-parent')),
                templateResult: function (product) {
                    if (!product.id) {
                        return product.sku;
                    }
                    var status=(product.status==0?'<span class="text-danger">Inactive</span>':'Active');
                    var $state = $(
                        '<span clas="user-list">' + product.name + ' (' + product.attribute_info + ')  ProductID : '+ product.product_sku +' SKU : '+ product.sku +'</span> '+status
                    );
                    return $state;
                },
                templateSelection: function (product) {
                    if (!product.id) {
                        return 'Select product';
                    }
                    if(typeof product.name == typeof undefined){
                        product.name = product.text;
                    }
                    var $state = $(
                        '<span>' + product.name + ' (' + product.attribute_info + ') ProductID : '+ product.id +' SKU : '+ product.sku +'</span>'
                    );
                    if(typeof product.attribute_info == typeof undefined){
                        $state = $(
                            '<span>' + product.name + ' ProductID : '+ product.id +' SKU : '+ product.sku +'</span>'
                        );
                    }
                    if (typeof product.name === typeof undefined && typeof product.id === typeof undefined) {

                        $state = $(
                            '<span>' + product.name + ' (' + product.attribute_info + ') ProductID : '+ product.id +' SKU : '+ product.sku +'</span>'
                        );
                    }
                    return $state;
                }
            }).on('change', function () {
                //alert($(this).val());
            }).on('select2:open', function(){
                var $md = $($(this).attr('data-parent'));
                if($md.hasClass('modal')){
                    var tr = $(this).closest('tr').position();
                    var el = $(this).position();
                    $('.select2-dropdown--below').parent().css('top', (tr.top + 190));
                    $('.select2-dropdown--above').parent().css('top', (tr.top + 80));
                    $('.select2-dropdown--below, .select2-dropdown--above').parent().css('position', 'fixed');
                }
            });
            $sb.change();
        });
    }

    //Added By Krunal
    var $vendorSelect = $('[data-resource=vendors]');
    if ($vendorSelect.length) {
        $vendorSelect.select2({
            ajax: {
                url: ajaxUrl + "/vendors",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            page: (params.page * data.per_page) < data.total
                        }
                    };
                },
                cache: false
            },
            minimumInputLength: 1,
            dropdownParent: (typeof $vendorSelect.attr('data-parent') == typeof undefined && $vendorSelect.attr('data-parent') !== '') ? '' : $($vendorSelect.attr('data-parent')),
            templateResult: function (vendor) {
                if (!vendor.id) {
                    return vendor.vendor_name;
                }
                var $vendors = $(
                    '<span clas="user-list">' + vendor.vendor_name + '</span>'
                );
                return $vendors;
            },
            templateSelection: function (vendor) {
                if (!vendor.id) {
                    return 'Select Vendor';
                }
                var $vendors = $(
                    '<span>' + vendor.vendor_name +'</span>'
                );
                if (typeof vendor.vendor_name === typeof undefined && typeof vendor.email === typeof undefined) {
                    $vendors = $(
                        '<span>' + vendor.text + '</span>'
                    );
                }

                return $vendors;
            }
        }).on('change', function () {
            //alert($(this).val());
        });
        $vendorSelect.change();
    }

    var $vendorpoSelect = $('[data-resource=vendor-po]');
    if ($vendorpoSelect.length) {

        $vendorpoSelect.select2({
            ajax: {
                url: ajaxUrl + "/vendorpo",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            page: (params.page * data.per_page) < data.total
                        }
                    };
                },
                cache: false
            },
            minimumInputLength: 1,
            dropdownParent: (typeof $vendorpoSelect.attr('data-parent') == typeof undefined && $vendorpoSelect.attr('data-parent') !== '') ? '' : $($vendorpoSelect.attr('data-parent')),
            templateResult: function (vendorpo) {
                if (!vendorpo.id) {
                    return vendorpo.vendor_name;
                }
                var $vendorpos = $(
                    '<span clas="user-list">' + vendorpo.vendor_name + '<em>' + vendorpo.vendor_supply_order_id + '</em></span>'
                );
                return $vendorpos;
            },
            templateSelection: function (vendorpo) {
                if (!vendorpo.id) {
                    return 'Select Vendor PO';
                }
                var $vendorpos = $(
                    '<span>' + vendorpo.vendor_name + ' (' + vendorpo.vendor_supply_order_id + ')</span>'
                );
                if (typeof vendorpo.vendor_name === typeof undefined && typeof vendorpo.vendor_supply_order_id === typeof undefined) {
                    $vendorpos = $(
                        '<span>' + vendorpo.text + '</span>'
                    );
                }

                return $vendorpos;
            }
        }).on('change', function () {
            //alert($(this).val());
        });
        $vendorpoSelect.change();
    }

    if ($(".js-select-single").length) {
        $(".js-select-single").select2({
            minimumResultsForSearch: 10,
            placeholder: 'Select One Item'
        });
    }
    /*activate select2 if element is available*/
    if ($(".js-select-single").length) {
        $(".js-select-multiple").select2({
            minimumResultsForSearch: 10,
            placeholder: 'Select Multiple Items'
        });
    }

    /*trigger click when clicking caption on check box*/
    $('body').on('click', '.rkmd-checkbox div.captions', function () {
        $(this).parent().find('[type="checkbox"]').click();
    });

    
    $('body').on('focus', '.__simple_date_time', function () {
        $(this).datetimepicker({
            daysOfWeekDisabled: blockDays,
            disabledDates: dates
        });
    });
    $('body').on('click', '.input-group-addon', function () {
        if ($(this).parent().find('.__simple_date_time')) {
            $(this).parent().find('.__simple_date_time').focus();
        }
    });

    $('body').on('click', '.table_status .dropdown-menu button', function () {
        var url = $(this).attr('data-url');
        var data = $.parseJSON($(this).attr('data-json'));
        var $dd = $(this).parent();
        var $trigger = $dd.parent().find('[data-toggle="dropdown"]');
        $trigger.attr('disabled', true);
        var active = $trigger.attr('data-item');
        var $e = $(this);

        $.post(url, data, function (response, status, xhr) {
            if (response.status === 200) {
                messages.saved('Success', 'Status updated successfully');
                $dd.find('[data-item=' + active + ']').show();
                $e.hide();
                $trigger.attr('class', $e.attr('data-class'));
                $trigger.text($e.text());
                $trigger.attr('data-item', $e.attr('data-item'));
            }
        }).always(function () {
            $trigger.attr('disabled', false);
        });

    });

    /*create simple CK Editor without tool bar*/
    if ($('.ck-editor-simple').length) {
        $('.ck-editor-simple').each(function () {
            var id = $(this).attr('id');
            if (typeof id !== typeof undefined) {
                cke.simple(id);
            }
        });
    }

    $(".md-form-control").each(function () {
        $(this).parent().append('<span class="md-line"></span>');
    });
    $(".md-form-control").change(function () {
        if ($(this).val() === "") {
            $(this).removeClass("md-valid");
        } else {
            $(this).addClass("md-valid");
        }
    });


    /**
     * Enable tooltip for all elements if available
     */
    $('body').tooltip({
        selector: '[data-toggle=tooltip]'
    });


    app.init();


    if ($('.__property_status').length) {
        $('.__property_status input').change(function () {
            if ($(this).val() == 3) {
                $('.__choose_filled_by').show();
            } else {
                $('.__choose_filled_by').hide();
            }
        });
    }

    if ($('.__property_fee_config').length) {
        $('.__property_fee_config input').change(function () {
            if ($(this).val() == 1) {
                $('.__property_fee_config_target').show();
            } else {
                $('.__property_fee_config_target').hide();
            }
        });
        if ($('.__property_fee_config input:checked').val() == 1) {
            $('.__property_fee_config_target').show();
        } else {
            $('.__property_fee_config_target').hide();
        }
    }

    if ($('textarea.max-textarea').length) {
        $('textarea.max-textarea').maxlength({
            alwaysShow: true
        });
    }

    if ($('button.disabled').length) {
        $('button.disabled').attr('disabled', true);
    }

});

function updateDeliveryQuestion(orderid,question,answer,pagetype,ordertype,unitnumber){
    if(confirm('Are you sure want to update delivery question '+question+'?')){
        $.ajax({
            method: "Post",
            url: '/product/order/updatedeliveryquestion',
            dataType: 'html',
            data: {
                orderid: orderid,
                question:question,
                answer:answer,
                ordertype:ordertype,
                unitnumber:unitnumber
            },
            success:function(data){
                messages.saved('Success',"Data saved successfully");
                if(pagetype=='Calendar'){
                    refreshCalendar();
                }
                else if(pagetype=='OrderList'){
                    $('#refreshOrders').click();
                }
                else if(pagetype=='OrderDetail'){
                    window.location.reload();
                }
            }
        });
    }
}

function addHubspotNote(){
    if($('#notesText').val().trim()==""){
        messages.error("Required","Please enter notes text");
        return;
    }
    messages.default("","Adding Notes... Please wait...");
    $.ajax({
        type:'Post',
        url:"/addHubspotNote",
        datatype:'html',
        data:{vid:$('#vid').val(),note:$('#notesText').val()},
        success:function(data){
            var arr=data.split('@@');
            if(arr[0]=='success'){
                messages.saved("Success",arr[1]);
            }
            else{
                messages.error("Error",arr[1]);
            }
            $('#notesText').val('');
            getNotesHistory();
        }
    });
}


function getNotesHistory(){
    $('#tblNotesHistory').html('<tr><td colspan="3" align="center">Please Wait.. Loading notes and chat transcripts...</td></tr>');
    $.ajax({
        type:'GET',
        url:"/notesHistory",
        datatype:'html',
        data:{vid:$('#vid').val()},
        success:function(data){
            $('#tblNotesHistory').html(data);
        }
    });
}

function getSmsHistory(){
    $('#tblMessageHistory').html('<tr><td colspan="3" align="center">Please Wait.. Loading meaasge history...</td></tr>');
    $.ajax({
        type:'GET',
        url:"/smsHistory",
        datatype:'html',
        data:{phone:$('#customerSMSPhone').val()},
        success:function(data){
            $('#tblMessageHistory').html(data);
        }
    });
}


function getSMSDialog(userid,phone){
    $('#customerId').val(userid);
    $('#customerSMSPhone').val(phone);

    var $modal = $('#sendSMSBox');
    $modal.find('.modal-body').html('<p>Please wait...</p>');
    $('#sendSMSBox').modal('show');

    var url = '/sms/template/dialog';
    $.get(url, function (data) {
        $modal.find('.modal-body').html(data);
        document.getElementById('sms_template').selectedIndex = 0;
        $('#smsText').val('');
        getSmsHistory(this);
    });
}

function getEmailsHistory(){
    $('#tblEmailHistory').html('<tr><td colspan="3" align="center">Please wait loading email history...</td></tr>');
    $.ajax({
        type:'GET',
        url:"/hubspotEmailHistory",
        datatype:'html',
        data:{whereField:$('#customerId').val()},
        success:function(data){
            $('#tblEmailHistory').html(data);
        }
    });
}

function getEmailsDialog(whereField){
    $('#customerId').val(whereField);
    /*var $modal = $('#emailConversationBoxDialog');
    $modal.find('.modal-body').html('<p>Please wait...</p>');*/
    $('#emailConversationBoxDialog').modal('show');
    getEmailsHistory(this);
    /*$.fn.modal.Constructor.prototype._enforceFocus = function () {
        var $modalElement = $modal;
        $(document).on('focusin.modal', function (e) {
            var $parent = $(e.target.parentNode);
            if ($modalElement[0] !== e.target && !$modalElement.has(e.target).length
                &&
                !$parent.hasClass('cke_dialog_ui_input_select') && !$parent.hasClass('cke_dialog_ui_input_text')) {
                $modalElement.focus()
            }
        })
    };*/
    /*var url = '/email/template/dialog';
    $.get(url, function (data) {
        $modal.find('.modal-body').html(data);
        document.getElementById('email_template').selectedIndex = 0;
        $('#email_subject').val('');
        $('#email_content').val('');
        CKEDITOR.replace( 'email_content' );
    }).done(function () {
        //getEmailsHistory(this);
    });*/
}

function sendMessage(){
    if($('#smsText').val().trim()==""){
        messages.error("Required","Please enter message text");
        return;
    }
    messages.default("","Sending messages... Please wait...");
    $.ajax({
        type:'Post',
        url:"/sendMessageToUser",
        datatype:'html',
        data:{userid:$('#customerId').val(),phone:$('#customerSMSPhone').val(),message:$('#smsText').val()},
        success:function(data){
            messages.saved("Success","Message sent successfully");
            $('#smsText').val($('#defaultSMSTemplate').val());
            $('#sms_template').val('');
            getSmsHistory(this);
        }
    });
}

function sendEmailViaHubspot(){
    if($('#email_subject').val().trim()==""){
        messages.error("Required","Please enter subject");
        return;
    }
    var email_content = CKEDITOR.instances.editor1.getData();
    /*var email_content = $('#email_content').val();*/
    if(email_content.trim()==""){
        messages.error("Required","Please enter email content");
        return;
    }
    messages.default("","Sending email... Please wait...");

    var alt_email = $('#alt_email').val();

    var formData = new FormData();
    var files = $("#uploadFile")[0].files;
    for (var i = 0; i < files.length; i++){
        formData.append('file[]', files[i]);
    }
    //formData.append('file', fileField);
    formData.append('whereField', $('#customerId').val());
    formData.append('email_subject', $('#email_subject').val());
    formData.append('email_content', email_content);
    formData.append('alt_email', alt_email);

    $.ajax({
        url:"/sendEmailToUser",
        //data:{uploadFile:$('#uploadFile').val(),whereField:$('#customerId').val(),email_subject:$('#email_subject').val(),email_content:email_content},
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success:function(data){
            if(data=="UploadFileError") {
                messages.error('Error', 'Please check attached file format and try again.');
            }
            else {
                messages.saved("Success","Email sent successfully");
                getEmailsHistory(this);
                $('#email_subject').val('');
                CKEDITOR.instances.editor1.setData('');
                $('#email_template').val("");
                $('#uploadFile').val("");
                $('#alt_email').val("");
            }

        }
    });
}

function getEmailTemplateContent(TemplateId){
    if(TemplateId.trim()==""){
        $('#email_subject').val('');
        $('#email_content').val('');
    }
    else{
        if(document.getElementById('customerId').value != '')
        { var email_id = document.getElementById('customerId').value; }
        else
        { var email_id=''; }
        $.ajax({
            type:'GET',
            url:"/email/template/get",
            data:{templateId:TemplateId,email_id:email_id},
            success:function(data){
                if(data.statusCode==201){
                    if(document.getElementById('customerId').value != '')
                    {
                        if(data.full_name != ''){
                            data.email_message = data.email_message.replace("#USERNAME",data.full_name);
                            data.email_subject = data.email_subject.replace("#USERNAME",data.full_name);
                        }

                    }
                    $('#email_subject').val(data.email_subject);
                    $('#email_content').val(data.email_message);
                    CKEDITOR.instances.editor1.setData(data.email_message);
                }
            }
        });
    }
}

function getSmsTemplateContent(TemplateId){
    if(TemplateId.trim()==""){
        $('#smsText').val('');
    }
    else{
        if(document.getElementById('customerSMSPhone').value != '')
        { var phone = document.getElementById('customerSMSPhone').value; }
        else
        { var phone=''; }
        $.ajax({
            type:'GET',
            url:"/sms/template/get",
            data:{templateId:TemplateId,phone:phone},
            success:function(data){
                if(data.statusCode==201){
                    $('#smsText').val(data.sms_content);
                }
            }
        });
    }
}

function selectAllStates(n){
    if(n==1){
        $('#state').val(0);
        $('#state').trigger('change');
    }
}

function track_click(routes,menus,e)
{
    e.preventDefault();
    /*$.ajax({
        url: ajaxUrl + "/dashboardTrack",
        data: {templateId: TemplateId, phone: phone},
        type: 'post',
        success: function (data) {
            if (data.statusCode == 201) {
                $('#smsText').val(data.sms_content);
            }
        }
    });*/
}

$('.contin').click(function (e) {
    var link = $(this).attr('href');
    var label = $(this).text();
    /*if(label == 'Log Issues')
    {
    var url = "https://www.google.com/url?q=https://inhabitr.atlassian.net/secure/RapidBoard.jspa?rapidView%3D12&sa=D&source=hangouts&ust=1571931086169000&usg=AFQjCNHdRddkUS3powjVgSWtUIOKHemXnQ";
    window.open(url, '_blank');
    return;
    }*/
    if(link == '#' || link == '')
        return;
    e.preventDefault(); // otherwise, it won't wait for the ajax response

    var menu_id = $(this).data('id');
    var p_id = $(this).data('pid');

    /*$.ajax({
        url: ajaxUrl + "/dashboardTrack",
        data: {routes: link, menu_id:menu_id},
        type: 'post',
        success: function (data) {
            window.location.href = link;
        }
    });*/
    window.location.href = link;
});
function getCities(){
    var state = $('#state').val();
    $('#city').html("");
    $('#warehouse').html("");
    if(state == '0'){
        $('#city').html("<option value='0'>Select City</option>");

        return;
    }
    $.ajax({
        type:'GET',
        url:"/resource/getcity",
        datatype:'html',
        data:{state:state},
        success:function(data){
            data="<option value='0'>Select City</option>"+data;
            $('#city').html(data);
        }
    });
}

/*
function getWarehouse(){
    var state = $('#state').val();
    if(state == '')
        return;
    $.ajax({
        type:'GET',
        url:"/resource/getwarehouse",
        datatype:'html',
        data:{state:state},
        success:function(data){
            $('#warehouse').html(data);
        }
    });
}
*/

function getWarehouseByCity(){
    var city = $('#city').val();
    $('#warehouse').html("");
    if(city == ''){
        return;
    }

    $.ajax({
        type:'GET',
        async: false,
        url:"/resource/getwarehousebycity",
        datatype:'html',
        data:{city:city},
        success:function(data){
            $('#warehouse').html(data);
        }
    });
}


var storage_list_dropdown = ["Storage", "In Transit", "B2B Storage","Solution Storage","B2B In Transit"];