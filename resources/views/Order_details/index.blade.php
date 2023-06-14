@extends('layout.default')
@section('content')

    <div class="modal fade" id="modal-lg" data-backdrop="static"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="" id="form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Payment Method</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-8 col-lg-6">
                                {{--<label for="category">Add Category</label>--}}
                                <input type="text" name="total" id="total" class="form-control" placeholder="Category" required>
                            </div>
                            <div class="col-sm-12 col-md-8 col-lg-6">
                                <label for="customer-id">customer_id</label>
                                <select name="customer_id" id="customer-id" class="form-control" required>
                                    <option value="" class="d-none">Select Customer</option>
                                    @foreach($customers as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between  ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-primary ">Save Changes</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-weight: bold;">Manage Order Details</h3>

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
                        <th>No</th>
                        <th>Total</th>
                        <th>Customer</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>

    <script>
        $(function () {

            'use strict';

            var baseurl = window.location.origin+'/order_details/';
            var url = '';

            var datatable = $('#data-table');
            var table = datatable.DataTable({
                destroy:true,
                processing:true,
                responsive: true,
                serchDelay:3500,
                deferRender: true,
                ajax:{
                    url:baseurl+'getOrder_details',
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
                        targets: 3,
                        data: null,render: function(data,type,row){
                        return  '<a data-id="'+row.id+'" title="Edit" class="edit"><i class="fa fa-pencil-alt" aria-hidden="true"></i></a> | '+
                            '<a data-id="'+row.id+'" title="Delete" class="delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                    }
                ],
                columns: [
                    { data: 'id'},
                    { data: 'total'},
                    { data: 'customers.first_name'},
                    { data: 'id'}
                ]
            });

            $('#open-modal').click(function () {
                $('#modal-lg').modal('toggle');
                url = 'add';
            });

            $('#modal').on('hidden.bs.modal', function (e) {
                $('#form')[0].reset();
                $('button[type="reset"]').fadeIn(100);
                url = '';
            });

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
                    $('#total').val(data.total);
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