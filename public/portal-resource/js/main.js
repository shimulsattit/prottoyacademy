$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('keydown', function(e) {
    if (e.altKey && e.key === 's') {
        $('#search_modal').modal('show');
        $('#searchInput').focus();
    }
});

$(document).on('click', '.show-search-modal', function() {
    $('#search_modal').modal('show');
    $('#searchInput').focus();
});

$(document).on('keyup', '#searchInput', function() {
    fetchResults($(this).val());
})

// Function to fetch search results
function fetchResults(query) {
    $.ajax({
        url: '/admin/search-menu',
        method: 'GET',
        data: { query: query },
        success: function(results) {
            displayResults(results);
        },
        error: function(error) {
            console.error('Error fetching search results:', error);
        }
    });
}

// Display search results
function displayResults(results) {
    $('#resultsContainer').empty(); // Clear previous results
    if (results.length > 0) {
        results.forEach(function(item) {
            const resultItem = $('<div>').addClass('result-item').text(item.name);
            resultItem.on('click', function() {
                window.location.href = '/' + item.slug; 
            });
            $('#resultsContainer').append(resultItem);
        });
    } else {
        $('#resultsContainer').html('<p>No results found.</p>');
    }
}

toastr.options = {
    preventDuplicates: true,
    timeOut: 3000,
    progressBar: true,
    closeButton: true
};

// select2
var _componentSelect2Normal = function () {
    $('.select').select2({
        width: '100%',
        dropdownParent: $('#modal_remote')
    });
}
var _componentSelect = function () {
    $('.select').select2({
        width: '100%',
    });
}

function allowOnlyNumbers(value) {
    return value
        .replace(/[^0-9.]/g, '') // শুধুমাত্র সংখ্যা এবং দশমিক বিন্দু রাখুন
        .replace(/(\..*?)\..*/g, '$1'); // একাধিক দশমিক সরান
}

$(document).on('input', '.number', function() {
    let value = $(this).val();
    $(this).val(allowOnlyNumbers(value));
});


function allowOnlyNumbers(input) {
    return input.replace(/\D/g, '');
}

// Is featured Update
var _isfeaturedUpdate = function(){
    $(document).on('change', 'input[name="is_featured"]', function() {
        var id = $(this).data('id'); // Get the ID from data-id attribute
        var status = this.checked ? 1 : 0; // Determine the status (1 or 0)
        var url = $(this).data('url');

        $.ajax({
            url: url, // Generate the URL
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // Include CSRF token
                is_featured: status
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message); // Show error message
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred while updating the status.');
            }
        });
    });
};
// Is featured Update
var _ispublicUpdate = function(){
    $(document).on('change', 'input[name="is_public"]', function() {
        var id = $(this).data('id'); // Get the ID from data-id attribute
        var status = this.checked ? 1 : 0; // Determine the status (1 or 0)
        var url = $(this).data('url');

        $.ajax({
            url: url, // Generate the URL
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // Include CSRF token
                is_featured: status
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message); // Show error message
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred while updating the status.');
            }
        });
    });
};
// Status Update
var _statusUpdate = function(){
    $(document).on('change', 'input[name="status"]', function() {
        var id = $(this).data('id'); // Get the ID from data-id attribute
        var status = this.checked ? 1 : 0; // Determine the status (1 or 0)
        var url = $(this).data('url');

        $.ajax({
            url: url, // Generate the URL
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // Include CSRF token
                status: status
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message); // Show error message
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred while updating the status.');
            }
        });
    });
};

// For Opening Modal
var _componentRemoteModalLoadAfterAjax = function () {
    $(document).on('click', '#content_management', function (e) {
        e.preventDefault();
        $('#modal_remote').modal('toggle');
        var url = $(this).data('url');
        $('.modal-content').html('');
        $('#modal-loader').show();
        $.ajax({
            url: url,
            type: 'Get',
            dataType: 'html'
        })
        .done(function (data) {
            $('.modal-content').html(data);
            _componentSelect2Normal();
            _modalClassFormValidation();
        })
        .fail(function (data) {
            $('.modal-content').html('<span style="color:red; font-weight: bold;"> Something Went Wrong. Please Try again later.......</span>');
            $('#modal-loader').hide();
        });
    });
};

// For Generating Slug
var _slugify = function(text) {
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

// For Submitting Modal Form
var _modalClassFormValidation = function () {
    if ($('.ajax-form').length > 0) {
        $('.ajax-form').parsley().on('field:validated', function () {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        });
    }
    $('.ajax-form').on('submit', function (e) {
        e.preventDefault();
        $('#submit').hide();
        $('#submitting').show();
        $(".ajax_error").remove();
        var submit_url = $('.ajax-form').attr('action');
        var formData = new FormData($(".ajax-form")[0]);
        $.ajax({
            url: submit_url,
            type: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'JSON',
            success: function (data) {
                if (data.status) {

                    toastr.success(data.message);
                    $('#submit').show();
                    $('#submitting').hide();

                    if(!data.stay) {
                        $('#modal_remote').modal('toggle');
                    }

                    if (data.goto) {
                        setTimeout(function () {
                            window.location.href = data.goto;
                        }, 1000);
                    }

                    if (data.load) {
                        setTimeout(function () {
                            window.location.href = "";
                        }, 1000);
                    }
                } else {
                    if (data.validator) {
                        for (const [key, messages] of Object.entries(data.message)) {
                            messages.forEach(message => {
                                toastr.error(message);
                            });
                        }
                    } else {
                        toastr.error(data.message);
                    }
                }
            },
            error: function (data) {
                console.log(data.responseJSON);
                var jsonValue = data.responseJSON;
                const errors = jsonValue.errors;
                if (errors) {
                    var i = 0;
                    $.each(errors, function (key, value) {
                        const first_item = Object.keys(errors)[i];
                        const message = errors[first_item][0];
                        if ($('#' + first_item).length > 0) {
                            $('#' + first_item).parsley().removeError('required', {
                                updateClass: true
                            });
                            $('#' + first_item).parsley().addError('required', {
                                message: value,
                                updateClass: true
                            });
                        }

                        toastr.error(value);
                        i++;
                    });
                } else {
                    toastr.error(jsonValue.message);
                }

                $('#submit').show();
                $('#submitting').hide();
            }
        });
    });
};


// Clear Cache Functionality
$(document).on('click', '#clearCache', function() {
    const button = $(this);
    const url = button.data('url');

    // Disable the button
    button.prop('disabled', true);

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(response) {
            if (response.status) {
                toastr.success(response.message);
                // Reload the page or perform an action if specified in the response
                if (response.load) {
                    setTimeout(function () {
                        window.location.reload(); // Reload the current page
                    }, 1000);
                }
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            toastr.error('An error occurred while clearing the cache.');
        }
    });
});


 // For Submitting Multiple Modal Forms
 var _initializeMultipleFormsValidation = function () {
    // Initialize validation for each form
    $('.nested-form').each(function () {
        var form = $(this);
        form.parsley().on('field:validated', function () {
            var ok = form.find('.parsley-error').length === 0;
            form.find('.bs-callout-info').toggleClass('hidden', !ok);
            form.find('.bs-callout-warning').toggleClass('hidden', ok);
        });

        // Bind the submit event for each form
        form.on('submit', function (e) {
            e.preventDefault();
            console.log("Form submitted"); // Debugging log

            // Handle showing/hiding submit buttons
            const submitButton = form.find('.submit-btn');
            submitButton.prop('disabled', true).text('Submitting...');

            // Clear previous errors
            $(".ajax_error").remove();

            // Prepare AJAX submission
            var submit_url = form.attr('action');
            var formData = new FormData(this);

            $.ajax({
                url: submit_url,
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'JSON',
                success: function (data) {
                    if (data.status) {
                        toastr.success(data.message);
                        submitButton.prop('disabled', false).text('Update');
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function (data) {
                    var jsonValue = data.responseJSON;
                    const errors = jsonValue.errors;
                    if (errors) {
                        $.each(errors, function (key, value) {
                            const message = value[0];
                            const inputField = form.find('#' + key);
                            if (inputField.length > 0) {
                                inputField.parsley().removeError('required', { updateClass: true });
                                inputField.parsley().addError('required', {
                                    message: message,
                                    updateClass: true
                                });
                            }
                            toastr.error(message);
                        });
                    } else {
                        toastr.error(jsonValue.message);
                    }
                    submitButton.prop('disabled', false).text('Update');
                }
            });
        });
    });
};
// form submit
var _formValidation = function () {
    if ($('.content_form').length > 0) {
        $('.content_form').parsley().on('field:validated', function () {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        });
    }

    $('.content_form').on('submit', function (e) {
        e.preventDefault();

        $('#submit').hide();
        $('#submitting').show();

        $(".ajax_error").remove();

        var submit_url = $('.content_form').attr('action');
        var formData = new FormData($(".content_form")[0]);
        var editor = $('.content_form').data('editor');
        if (editor && editor != '') {
            const descriptionData = CKEDITOR.instances[editor].getData();
            console.log(descriptionData);
            formData.append('description', descriptionData);
        }

        //Start Ajax
        $.ajax({
            url: submit_url,
            type: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'JSON',
            success: function (data) {
                if (!data.status) {
                    if (data.validator) {
                        for (const [key, messages] of Object.entries(data.message)) {
                            messages.forEach(message => {
                                toastr.error(message);
                            });
                        }
                    } else {
                        toastr.error(data.message);
                    }

                    if (data.errors) {
                        for (const [key, message] of Object.entries(data.errors)) {
                            toastr.error(message);
                        }
                    }
                } else {
                    toastr.success(data.message);

                    // CKEDITOR.instances.editor.setData('');
                    // var preview = document.getElementById("preview");
                    // preview.innerHTML = "";

                    $('.content_form')[0].reset();
                    if (data.goto) {
                        setTimeout(function () {

                            window.location.href = data.goto;
                        }, 500);
                    }

                    if (data.load) {
                        setTimeout(function () {

                            window.location.href = "";
                        }, 500);
                    }

                    if (data.window) {
                        $('.content_form')[0].reset();
                        window.open(data.window, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=auto,left=auto,width=700,height=400");
                        setTimeout(function () {
                            window.location.href = '';
                        }, 1000);
                    }

                    if (data.load) {
                        setTimeout(function () {

                            window.location.href = "";
                        }, 1000);
                    }
                }

                $('#submit').show();
                $('#submitting').hide();
            },
            error: function (data) {
                var jsonValue = $.parseJSON(data.responseText);
                const errors = jsonValue.errors;
                if (errors) {
                    var i = 0;
                    $.each(errors, function (key, value) {
                        const first_item = Object.keys(errors)[i]
                        const message = errors[first_item][0];
                        if ($('#' + first_item).length > 0) {
                            $('#' + first_item).parsley().removeError('required', {
                                updateClass: true
                            });
                            $('#' + first_item).parsley().addError('required', {
                                message: value,
                                updateClass: true
                            });
                        }
                        // $('#' + first_item).after('<div class="ajax_error" style="color:red">' + value + '</div');
                        toastr.error(value);
                        i++;

                    });
                } else {
                    toastr.warning(jsonValue.message);

                }

                $('#submit').show();
                $('#submitting').hide();
            }
        });
    });
};

// For Restore items
$(document).on('click', '.restore', function(e) {
    e.preventDefault();

    var id = $(this).data('id');
    var url = $(this).data('url');

    $('#restore_button_'+id).html('<i class="fas fa-spinner fa-spin"></i>')

    $.ajax({
        url: url,
        method: 'Delete',
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'JSON',
        success: function(data) {

            if (data.status) {

                toastr.success(data.message);
                
                if (data.load) {
                    setTimeout(function() {
                        window.location.href = "";
                    }, 1000);
                }

            } else {
                $('#restore_button_'+id).html('<i class="fas fa-backspace"></i>')
                toastr.warning(data.message);
            }
        },
        error: function(data) {
            var jsonValue = $.parseJSON(data.responseText);
            const errors = jsonValue.errors
            var i = 0;
            $.each(errors, function(key, value) {
                toastr.error(value);
                i++;
            });
        }
    });

});

// For delete items
$(document).on('click', '#delete_item', function(e) {
    e.preventDefault();
    var row = $(this).data('id');
    var url = $(this).data('url');
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                method: 'Delete',
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'JSON',
                success: function(data) {

                    if (data.status) {

                        toastr.success(data.message);
                        if (data.load) {
                            setTimeout(function() {

                                window.location.href = "";
                            }, 1000);
                        }

                    } else {
                        toastr.warning(data.message);
                    }
                },
                error: function(data) {
                    var jsonValue = $.parseJSON(data.responseText);
                    const errors = jsonValue.errors
                    var i = 0;
                    $.each(errors, function(key, value) {
                        toastr.error(value);
                        i++;
                    });
                }
            });
        }
    });

});

// Delete Specification

$(document).on('click', '#delete_specification', function(e) {
    e.preventDefault();
    var id = $(this).data('id'); // This should get the ID correctly
    var url = $(this).data('url');

    // Find the row element using data-row-id
    var rowElement = $('[data-row-id="' + id + '"]');
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                method: 'POST',
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'JSON',
                success: function(data) {

                    if (data.status) {

                        toastr.success(data.message);
                        rowElement.remove();
                        if (data.load) {
                            setTimeout(function() {

                                window.location.href = "";
                            }, 1000);
                        }

                    } else {
                        toastr.warning(data.message);
                    }
                },
                error: function(data) {
                    var jsonValue = $.parseJSON(data.responseText);
                    const errors = jsonValue.errors
                    var i = 0;
                    $.each(errors, function(key, value) {
                        toastr.error(value);
                        i++;
                    });
                }
            });
        }
    });

});

$(document).on('click', '#logout', function(e) {
    e.preventDefault();
    var url = $(this).data('url');

    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out for this session!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Logout',
        cancelButtonText: 'Cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                method: 'POST',
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'JSON',
                success: function(data) {
                    toastr.success(data.message);

                    setTimeout(function() {
                        window.location.href = data.goto;
                    }, 2000);
                },
                error: function(data) {
                    var jsonValue = $.parseJSON(data.responseText);
                    const errors = jsonValue.errors
                    var i = 0;
                    $.each(errors, function(key, value) {
                        toastr.success(value);
                        i++;
                    });
                }
            });
        }
    });


});
