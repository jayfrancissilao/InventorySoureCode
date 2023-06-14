@extends('layout.default')
@section('content')

    <style>
        .edit{
            cursor: pointer;
        }
        .delete{
            cursor: pointer;
        }
    </style>


    <div class="modal fade" id="modal-lg" data-backdrop="static"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="" id="form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Customer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">

                                <label>Firstname</label>
                                <input type="text" name="first_name" id="first_name" class="form-control"  required >
                                <br>
                                <label>Middlename</label>
                                <input type="text" name="middle_name" id="middle_name" class="form-control" required >
                                <br>
                                <label>Lastname</label>
                                <input type="text" name="last_name" id="last_name" class="form-control"  required >
                                <br>
                                <label>Phone number</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" required >
                                <br>
                            </div>
                            {{--<div class="col-sm-12 col-md- 6  col-lg-6">--}}
                               {{----}}
                                {{--<br>--}}
                              {{----}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary ">Save Changes</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Customers</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" id="open-modal" title="Open Modal">
                    <i class="fas fa-plus-square"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="data-table" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>first_name</th>
                        <th>middle_name</th>
                        <th>last_name</th>
                        <th>phone_number</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                </table>
            </div>
        </div>

        <script>
            $(function () {

                'use strict';

                var baseurl = window.location.origin+'/customers/';
                var url = '';
                var datatable = $('#data-table');

                var table = datatable.DataTable({
                    destroy:true,
                    processing:true,
                    responsive: true,
                    serchDelay:3500,
                    deferRender: true,
                    ajax:{
                        url:baseurl+'getCustomers',
                        method: 'GET',
                        dataType: 'JSON'
                    },
                    pagingType: 'full_numbers',
                    columnDefs: [
                        {
                            targets: 0,
                            render: function ( data, type, full, meta ) {
                                const row = meta.row;
                                return  row+1;
                            }
                        },
                        {
                            targets: 5,
                            data: null,render: function(data,type,row){
                            return  '<a data-id="'+row.id+'" title="Edit" class="edit"><i class="fa fa-pencil-alt" aria-hidden="true"></i></a> | '+
                                '<a data-id="'+row.id+'" title="Delete" class="delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                        }
                        }
                    ],
                    columns: [
                        { data: 'id'},
                        { data: 'first_name'},
                        { data: 'middle_name'},
                        { data: 'last_name'},
                        { data: 'phone_number'},
                        { data: 'id'}
                    ]
                });

                //add modal
                $('#open-modal').click(function () {
                    $('#modal-lg').modal('toggle');
                    url = 'add';
                });

                $('#modal').on('hidden.bs.modal', function (e) {
                    $('#form')[0].reset();
                    $('button[type="reset"]').fadeIn(100);
                    url = '';
                });

                //submit the data
                $('#form').submit(function (e) {
                    e.preventDefault();
                    const data = new FormData(this);
                    const action = baseurl+url;
                    $.ajax({
                        url: action,
                        method:'POST',
                        type:'POST',
                        data: data,
                        cache:false,
                        contentType: false,
                        processData: false
                    }).done(function (data,response, status) {
                        $('#modal-lg').modal('toggle');
                        $('#form')[0].reset();
                        table.ajax.reload(null, false);
                        toast('success', data.result, data.message);
                    }).fail(function (xhr, status, error) {
                        var messsage = '';
                        var response = JSON.parse(xhr.responseText);
                        var validation = JSON.parse(xhr.responseText).errors;
                        messsage = validation ? validation[Object.keys(validation)[0]]: response.message;
                        toast( 'error',status, messsage);
                    });
                });


                //edit
                datatable.on('click','.edit',function (e) {
                    e.preventDefault();
                    var dataId = $(this).attr('data-id');
                    var href = 'edit/'+dataId;
                    $.ajax({
                        url:href,
                        type: 'GET',
                        method: 'GET',
                        dataType:'JSON'
                    }).done(function (data, status, xhr) {
                        url = href;
                        $('#first_name').val(data.first_name);
                        $('#middle_name').val(data.middle_name);
                        $('#last_name').val(data.last_name);
                        $('#phone_number').val(data.phone_number);

                        $('#modal-lg').modal('toggle');
                    }).fail(function (xhr, status, error) {
                        const errors = JSON.parse(xhr.responseText);
                        toast('error', errors.result, errors.message);
                    });
                });


                //start of delete
                datatable.on('click','.delete',function (e) {
                    e.preventDefault();
                    var dataId = $(this).attr('data-id');
                    var href = baseurl+'delete/'+dataId;
                    Swal.fire({
                        title: 'Delete File?',
                        text: 'Are You Sure',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                url:href,
                                type: 'DELETE',
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                                },
                                dataType:'JSON'
                            }).done(function (data, status, xhr) {
                                toast('success', data.result, data.message);
                                table.ajax.reload(null, false);
                            }).fail(function (xhr, status, error) {
                                const errors = JSON.parse(xhr.responseText);
                                toast('error', errors.result, errors.message);
                            });
                        }
                    });
                }); //end of delete







                function toast(icon, result, message) {
                    Swal.fire({
                        icon:icon,
                        title:result,
                        text:message,
                        timer:5000
                    });
                }



            });

        </script>

@endsection