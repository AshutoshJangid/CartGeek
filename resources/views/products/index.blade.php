@extends('products.layout')

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        {{ __('Product Listing') }}
                        <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"  data-toggle="modal" data-target="#CreateProductModal">
                            Create Product
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered datatable">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th width="150" class="text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Product Modal -->
<div class="modal" id="CreateProductModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Product Create</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                    <strong>Success!</strong>Product was added successfully.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form  id="SubmitCreateProductForm" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <div class="form-group">
                        <label for="title">Product Name:</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group">
                        <label for="title">Product Price:</label>
                        <input type="number" class="form-control" name="price" id="price">
                    </div>
                    <div class="form-group">
                        <label for="description">Product Description:</label>
                        <textarea class="form-control" name="description" id="description">                        
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label for="title">Product Image: <button type="button" class="addBtn" data-target="add"> + </button></label>
                        <div class="addFile"><input type="file" class="form-control" name="p_image[]" class="p_image"></div>
                        <div class="addMoreFile"></div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="SubmitCreateProductForm">Create</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
                </form>
                
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal" id="EditProductModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Product Edit</h4>
                <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                    <strong>Success!</strong>Product was added successfully.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form  method="PUT" id="SubmitEditProductForm" enctype='multipart/form-data'>
                    <input type="hidden" name="_method" value="PUT">
                <div id="ModelEditProductForm">
                    
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" >Update</button>
                    <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Close</button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div class="modal" id="DeleteProductModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Product Delete</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h4>Are you sure want to delete this Product?</h4>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="SubmitDeleteProductForm">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // init datatable.
        var dataTable = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            pageLength: 5,
            // scrollX: true,
            "order": [[ 0, "desc" ]],
            ajax: '{{ route('get-products') }}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'product_name', name: 'product_name'},
                {data: 'product_price', price: 'product_price'},
                {data: 'product_description', name: 'product_description'},
                {data: 'Actions', name: 'Actions',orderable:false,serachable:false,sClass:'text-center'},
            ]
        });
        

        // Create product Ajax request.
        $('#SubmitCreateProductForm').submit(function(e) {
            e.preventDefault();
            var data = new FormData(this);
                for(var pair of data.entries()) {
                console.log(pair[0]+ ', '+ pair[1]); 

                }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('products.store')}}",
                method: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(result) {
                    if(result.errors) {
                        $('.alert-danger').html('');
                        $.each(result.errors, function(key, value) {
                            $('.alert-danger').show();
                            $('.alert-danger').append('<strong><li>'+value+'</li></strong>');
                        });
                    } else {
                        $('.alert-danger').hide();
                        $('.alert-success').show();
                        $('.datatable').DataTable().ajax.reload();
                        setInterval(function(){ 
                            $('.alert-success').hide();
                            $('#CreateProductModal').modal('hide');
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });

        $('.modelClose').on('click', function(){
            $('#EditProductModal').hide();
        });
        var id;
        $('body').on('click', '#getEditProductData', function(e) {
            // e.preventDefault();
            $('.alert-danger').html('');
            $('.alert-danger').hide();
            id = $(this).data('id');
            $.ajax({
                url: "products/"+id+"/edit",
                method: 'GET',
                // data: {
                //     id: id,
                // },
                success: function(result) {
                    console.log(result);
                    $('#ModelEditProductForm').html(result.html);
                    $('#EditProductModal').show();
                }
            });
        });
        $('#SubmitEditProductForm').submit(function(e) {
            e.preventDefault();
            var datakkkk = new FormData(this);
                for(var pair of datakkkk.entries()) {
                console.log(pair[0]+ ', '+ pair[1]); 

                }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "products/"+id,
                method: 'POST',
                data: datakkkk,
                cache: false,
                contentType: false,
                processData: false,
                success: function(result) {
                    if(result.errors) {
                        $('.alert-danger').html('');
                        $.each(result.errors, function(key, value) {
                            $('.alert-danger').show();
                            $('.alert-danger').append('<strong><li>'+value+'</li></strong>');
                        });
                    } else {
                        $('.alert-danger').hide();
                        $('.alert-success').show();
                        $('.datatable').DataTable().ajax.reload();
                        setInterval(function(){ 
                            $('.alert-success').hide();
                            $('#CreateProductModal').modal('hide');
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });

        // Get single product in EditModel
        

        // Update product Ajax request.
        // $('#SubmitEditProductForm').click(function(e) {
        //     e.preventDefault();
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     $.ajax({
        //         url: "products/"+id,
        //         method: 'PUT',
        //         data: {
        //             title: $('#editTitle').val(),
        //             description: $('#editDescription').val(),
        //         },
        //         success: function(result) {
        //             if(result.errors) {
        //                 $('.alert-danger').html('');
        //                 $.each(result.errors, function(key, value) {
        //                     $('.alert-danger').show();
        //                     $('.alert-danger').append('<strong><li>'+value+'</li></strong>');
        //                 });
        //             } else {
        //                 $('.alert-danger').hide();
        //                 $('.alert-success').show();
        //                 $('.datatable').DataTable().ajax.reload();
        //                 setInterval(function(){ 
        //                     $('.alert-success').hide();
        //                     $('#EditProductModal').hide();
        //                 }, 2000);
        //             }
        //         }
        //     });
        // });

        // Delete product Ajax request.
        var deleteID;
        $('body').on('click', '#getDeleteId', function(){
            deleteID = $(this).data('id');
        })
        $('#SubmitDeleteProductForm').click(function(e) {
            e.preventDefault();
            var id = deleteID;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "products/"+id,
                method: 'DELETE',
                success: function(result) {
                    setInterval(function(){ 
                        $('.datatable').DataTable().ajax.reload();
                        $('#DeleteProductModal').hide();
                    }, 1000);
                }
            });
        });
    });
    
    $('body').on('click', '.addBtn', function(){
        var target = $(this).data('target');
        $('.'+target+'MoreFile').append($('.'+target+'File').html());
    });
</script>
@endsection