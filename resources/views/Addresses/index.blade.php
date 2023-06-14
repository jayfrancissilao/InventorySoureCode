@extends('layout.default')
@section('content')



    <div class="modal fade" id="modal-lg" data-backdrop="static"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="" id="form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Products</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label for="region">region</label>
                                <input type="text" name="region" id="region" class="form-control" placeholder="Product Name" required>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label for="province">province</label>
                                {{--<input type="textarea" >--}}
                                <textarea name="province" id="province" class="form-control" placeholder="Product Name" required></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label for="municipality">municipality</label>
                                <input type="text" name="municipality" id="municipality" class="form-control" placeholder="Price" required>
                                <label for="baranggay">baranggay</label>
                                <input type="text" name="baranggay" id="baranggay" class="form-control" placeholder="Stock" required>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">

                                <label for="zip_code">zip_code</label>
                                <input type="text" name="zip_code" id="zip_code" class="form-control" placeholder="Stock" required
                                <label for="street">street</label>
                                <input type="text" name="street" id="street" class="form-control" placeholder="Stock" required>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
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
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-weight: bold;">ADDRESSES</h3>

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
                        <th>region</th>
                        <th>province</th>
                        <th>municipality</th>
                        <th>barangay</th>
                        <th>zip_code</th>
                        <th>street</th>
                        <th>customer</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>

    <script>
        $(function (){
            'use strict';

            var baseurl = window.location.origin+'/addresses/';
            var url = '';

            var datatable = $('#data-table');
            var table = datatable.DataTable({
                destroy:true,
                processing:true,
                responsive: true,
                serchDelay:3500,
                deferRender: true,
                ajax:{
                    url:baseurl+'getAddresses',
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
                        targets: 8,
                        data: null,render: function(data,type,row){
                        return  '<a data-id="'+row.id+'" title="Edit" class="edit"><i class="fa fa-pencil-alt" aria-hidden="true"></i></a> | '+
                            '<a data-id="'+row.id+'" title="Delete" class="delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                    }
                ],
                columns: [
                    { data: 'id'},
                    { data: 'region'},
                    { data: 'province'},
                    { data: 'municipality'},
                    { data: 'baranggay'},
                    { data: 'zip_code'},
                    { data: 'street'},
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
                    $('#region').val(data.region);
                    $('#municipality').val(data.municipality);
                    $('#province').val(data.province);
                    $('#description').val(data.description);
                    $('#baranggay').val(data.baranggay);
                    $('#zip_code').val(data.zip_code);
                    $('#street').val(data.street);
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




        })
    </script>

@endsection