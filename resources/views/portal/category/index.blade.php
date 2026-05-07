@extends('layouts.admin', ['title' => 'Category Management', 'modal' => 'lg'])
@push('style')
<link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Category Management
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Category Management</li>
                    </ul>
                </div>

                {{-- <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('portal.category.create') }}" class="btn btn-sm fw-bold btn-primary">
                        Create
                    </a>
                </div> --}}
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <div id="mmenu">
                            @if (Auth::guard('admin')->user()->hasPermissionTo('category.create'))
                                <button type="button" class="btn btn-sm btn-primary" id="create_category">
                                    <i class="ri-add-line"></i>
                                    Create New Category
                                </button>
                            @endif
                            @if (Auth::guard('admin')->user()->hasPermissionTo('category.update'))
                                <button type="button" class="btn btn-sm btn-info" id="edit_category">
                                    <i class="ri-edit-box-line"></i>
                                    Update Category
                                </button>
                            @endif
                            @if (Auth::guard('admin')->user()->hasPermissionTo('category.delete'))
                                <button type="button" class="btn btn-sm btn-danger" id="delete_category">
                                    <i class="ri-delete-bin-line"></i>
                                    Remove Category
                                </button>
                            @endif
                            <input type="text" id="text" placeholder="Search">
                            <button type="button" class="btn btn-sm btn-success" id="search">
                                <i class="ri-search-line"></i>
                                Search Category
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div id="category-tree"></div>
                            </div>
                            <div class="col-md-6" style="background: #f9f9f9;padding: 10px;">
                                <div class="row" id="post-container">
                                    <div class="col-md-12 text-center">
                                        <p>Select Category First</p>
                                    </div>
                                </div>
                                <div id="load-more" class="text-center" style="display: none;">
                                    <button class="btn btn-sm btn-primary">Load More</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
        
    <style>
        #category-tree {
            padding: 10px;
            background-color: #f9f9f9;
        }

        #mmenu {
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        #mmenu input {
            margin-right: 5px;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <script>
        $(function () {
            let page = 1;
            let category_id = null;

            $('#load-more button').click(function () {

                var selectedNode = $("#category-tree").jstree("get_selected");
                category_id = selectedNode[0];
                if(category_id == null) {
                    return false;
                }

                loadMorePosts(category_id);
            });

            function loadMorePosts(category_id) {
                $.ajax({
                    url: '/portal/question/load-more?page=' + page,
                    type: 'GET',
                    data: {
                        category_id: category_id
                    },
                    beforeSend: function () {
                        $('#load-more').show();
                    },
                    success: function (data) {
                        if (data.trim() === '') {
                            $('#post-container').html("<div class='col-md-12 text-center'>Nothing to show.</div>");
                            $('#load-more').hide();
                        } else {
                            $('#post-container').append(data);
                            $('#load-more').show();
                            page++;
                        }   
                    }
                });
            }
            
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
                    _modalClassFormValidation1();
                })
                .fail(function (data) {
                    $('.modal-content').html('<span style="color:red; font-weight: bold;"> Something Went Wrong. Please Try again later.......</span>');
                    $('#modal-loader').hide();
                });
            });

            var _modalClassFormValidation1 = function () {
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

                                if (data.question_id) {
                                    $('#question_' + data.question_id).html(`
                                        Q. ${data.question}
                                        <div style="float: right;">
                                            <button id="content_management" data-url="/portal/question/${data.question_id}/edit" class="btn btn-sm btn-primary btn-icon">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    `);

                                    let optionsHtml = [
                                        { id: 1, text: data.option_one },
                                        { id: 2, text: data.option_two },
                                        { id: 3, text: data.option_three },
                                        { id: 4, text: data.option_four }
                                    ];

                                    optionsHtml.forEach(option => {
                                        if (option.text && option.text.trim() !== '') {
                                            $('#question_option_' + option.id + '_' + data.question_id).html(`
                                                <p>
                                                    ${data.correct_answer == option.id ? '<i class="fas fa-check fa-fw text-success"></i>' : `(${String.fromCharCode(96 + option.id)}).`} 
                                                    ${option.text}
                                                </p>
                                            `);
                                        }
                                    });
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
            
            $("#category-tree").jstree({
                "core": {
                    "data": {
                        "url": "/portal/get-categories",
                        "data": function (node) {
                            if (node.id === "#") {
                                return { "parent_id": null };
                            } else {
                                return { "parent_id": node.id };
                            }
                        },
                    },
                    "themes": {
                        "name": "default",
                        "responsive": true
                    }
                },
                "plugins": ["contextmenu", "search", "types", "dnd"],
                "search": {
                    "show_only_matches": true,
                    "search_leaves_only": false,
                    "ajax" : {
                        "url" : "/seller/get-categories/search",
                        "data" : function (str) {
                            return {
                                "operation" : "search",
                                "search_str" : str
                            };
                        }
                    }
                },
            })
            .on("move_node.jstree", function (e, data) {
                $.post("/seller/get-categories", {
                    "operation": "move_node",
                    "id": data.node.id,
                    "parent_id": data.parent,
                    "position": data.position
                });
            })
            .on("select_node.jstree", function (e, data) {
                category_id = data.node.id
                if(category_id == null) {
                    return false;
                }

                loadMorePosts(category_id);
            });;

            $("#search").click(function () {
                var searchString = $("#text").val();
                $("#category-tree").jstree("search", searchString);
            });

            // Add a folder node
            $("#create_category").click(function () {
                var selectedNode = $("#category-tree").jstree("get_selected");
                
                var url = '/portal/category/create?parent_id=' + selectedNode;
                window.location.href=url;
                return false;
            });
            
            $("#edit_category").click(function () {
                var selectedNode = $("#category-tree").jstree("get_selected");
                
                if(selectedNode != '') {
                    var url = '/portal/category/' + selectedNode + '/edit/';
                    window.open(url, '_blank');
                    return false;
                }

                toastr.error('Please Category First');
                return false;
            });
            
            $("#delete_category").click(function () {
                var selectedNode = $("#category-tree").jstree("get_selected");
                
                if(selectedNode != '') {
                    var url = '/portal/category/delete?category_id=' + selectedNode;
                    window.open(url, '_blank');
                    return false;
                }

                toastr.error('Please Category First');
                return false;
            });
        });
    </script>
@endpush
