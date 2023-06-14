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
                        <h4 class="modal-title">Add Products</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label for="product-name">Product Name</label>
                                <input type="text" name="product_name" id="product-name" class="form-control" placeholder="Product Name" required>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label for="description">Description</label>
                                <input type="textarea" name="description" id="description" class="form-control" placeholder="Description" required>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label for="price">Price</label>
                                <input type="number" name="price" id="price" class="form-control" placeholder="Price" required>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label for="stock">Stock</label>
                                <input type="number" name="stock" id="stock" class="form-control" placeholder="Stock" required>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label for="category-id">category_id</label>
                                <select name="category_id" id="category-id" class="form-control" required>
                                    <option value="" class="d-none">Select Category</option>
                                    @foreach($categories as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label for="file">File</label>
                                <input type="file" name="file" id="file" class="form-control" placeholder="File" accept="image/*">
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
            <h3 class="card-title" style="font-weight: bold;">PRODUCTS</h3>

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
                        <th>Name</th>
                        <th>description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                </table>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            Footer
        </div>
        <!-- /.card-footer-->
    </div>

    <script>
        $(function () {
            'use strict';

            var baseurl = window.location.origin+'/products/';
            var url = '';

            var datatable = $('#data-table');
            var table = datatable.DataTable({
                destroy:true,
                processing:true,
                responsive: true,
                serchDelay:3500,
                deferRender: true,
                ajax:{
                    url:baseurl+'getProducts',
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
                        targets: 4,
                        render: function ( data, type, full, meta ) {
                            return '<img src="/img/'+data+'" alt="image" style="height: 3rem; width: 3rem; border-radius: 50%;">';
                        }
                    },

                    {
                        targets: 7,
                        data: null,render: function(data,type,row){
                        return  '<a data-id="'+row.id+'" title="Edit" class="edit"><i class="fa fa-pencil-alt" aria-hidden="true"></i></a> | '+
                            '<a data-id="'+row.id+'" title="Delete" class="delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                    }
                ],
                columns: [
                    { data: 'id'},
                    { data: 'product_name'},
                    { data: 'description'},
                    { data: 'price'},
                    { data: 'product_image'},
                    { data: 'stock'},
                    { data: 'categories.category'},
                    { data: 'id'}
                ]
            });

            $('#open-modal').click(function () {
                $('#modal-lg').modal('toggle');
                url = 'add';
            });

            $('#modal-lg').on('hidden.bs.modal', function (e) {
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
                    $('#price').val(data.price);
                    $('#product-name').val(data.product_name);
                    $('#stock').val(data.stock);
                    $('#description').val(data.description);
                    $('#category-id').val(data.category_id);
                    $('#modal-lg').modal('toggle');
                }).fail(function (xhr, status, error) {
                    const errors = JSON.parse(xhr.responseText);
                    toast('error', errors.result, errors.message);
                });
            });

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
            });

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