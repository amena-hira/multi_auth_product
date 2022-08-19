@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Seller, You are logged in!') }}

                    <div class="mt-5">
                        <h2 class="text-center">Product Table</h2>
                        <div class="nav justify-content-end"><a class="btn btn-success mb-2 " name="modal_button" id="modal_button" >Add New</a></div>

                        {{-- Modal --}}
                        
                        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <form action="" id="product_form" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group">
                                            <label for="product_name">Product Name</label>
                                            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name">
                                        </div>
                                        <div class="form-group">
                                            <label for="product_price">Product Price</label>
                                            <input type="number" step="any" class="form-control" id="product_price" name="product_price" placeholder="Enter product price">
                                        </div>
                                        <div class="form-group">
                                            <label for="company_name">Company Name</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter Company Name">
                                        </div>
                                        
                                        <div class="form-group" >
                                            <div id="imageField" class="mb-2"></div>
                                            <label for="image">Product Image</label>
                                            
                                            <input type="file" class="form-control-file" name="image" id="image">
                                        </div>
                                        

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary btnSave" name="btnSave" id="btnSave">Save</button>   
                                        <button type="button" class="btn btn-primary btnUpdate" name="btnUpdate" id="btnUpdate" >Update</button> 
                                    </div>
                                </form>
                                
                            </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        
                        <table class="table" >
                            <thead>
                              <tr>
                                <th scope="col">SL</th>
                                <th scope="col">Product Image</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Product Price</th>
                                <th scope="col">Company Name</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody id="product_id">
                                
                              
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')

<script>

    function drawProductRow (products) {
        var product_data = '';
        var i=1;
        let base = '{{ asset("img") }}';
        $.each(products,function(key,value){
            product_data += '<tr>';
            product_data += '<td>'+i+'</td>';
            product_data += '<td><img class="rounded img-fluid" width="100" height="130" src="'+base+'/'+value.image+'"> </td>';
            product_data += '<td>'+value.product_name+'</td>';
            product_data += '<td>'+value.product_price+'</td>';
            product_data += '<td>'+value.company_name+'</td>';
            product_data += '<td><button class="btn btn-success" name="btnEdit" id="btnEdit" value="'+value.id+'">Edit</button>';
            product_data += '<button class="ml-2 btn btn-danger" name="btnDelete" id="btnDelete" value="'+value.id+'">Delete</button></td>';
            product_data += '</tr>';
            i++;
        });

        $('table tbody').html(product_data);
    }
    function reset(){
        $('#modal').find('input').each(function(){
            $(this).val(null)
        })
    }

    $(document).ready(function(){
        $.ajax({
            url:"{{ route('seller.data_show') }}",
            type: "get",
            success:function(response){
                drawProductRow(response);
            },
        });
        
        $(document).on('click', '#modal_button', function () {
            $('#modal').find('.modal-title').text('New Product');
            $('#btnUpdate').hide();
            $('#btnSave').show();
            $('#modal').modal('show');
                
            
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $(document).on('click', '#btnSave', function (){
            let formData = new FormData($('#product_form')[0]);
            // $('#imageField').hide();
            $.ajax({
                url:"{{ route('seller.add_product') }}",
                type: "POST",
                data:formData,
                dataType:"json",
                contentType: false,
                processData: false,
                success:function(response){
                    $('#modal').modal('hide');
                    reset();
                    console.log(response);
                    drawProductRow(response.products);
                },
            });
        
        });

        $(document).on('click', '#btnEdit', function () {
            $('#modal').find('.modal-title').text('Edit Product');
            $('#btnUpdate').show();
            $('#btnSave').hide();
            // $('#imageField').show();
            var id = $(this).val();
            console.log(id);
            reset();
            $.ajax({
                url: "/seller/edit_product/"+id,
                type: "get",
                success:function(response){
                    let base = '{{ asset("img") }}';
                    $.each(response,function(key,value){
                        $('#id').val(value.id);
                        $('#product_name').val(value.product_name);
                        $('#product_price').val(value.product_price);
                        $('#company_name').val(value.company_name);
                    })
                    $('#imageField').append('<p style="margin:0">Uploaded Image </p>');
                    $('#imageField').append('<img class="rounded img-fluid" width="100" height="100" src="'+base+'/'+response.product.image+'">');
                    $('#modal').modal('show');
                },
            });
        });

        $(document).on('click', '#btnUpdate', function () {
            var id = $('input[name="id"]').val()
            console.log(id);
            $.ajax({
                url:"/seller/update_product/"+id,
                type: "PUT",
                data:{
                    "_token": "{{ csrf_token() }}",
                    product_name:$('input[name="product_name"]').val(),
                    product_price:$('input[name="product_price"]').val(),
                    company_name:$('input[name="company_name"]').val()
                },
                dataType:"json",
                success:function(response){
                    $('#modal').modal('hide');
                    reset();
                    console.log(response);
                    drawProductRow(response.products);
                },
            });
            
                
            
        });
        $(document).on('click', '#btnDelete', function () {
            var id = $(this).val()
            $.ajax({
                url: "/seller/delete_product/"+id,
                type: "DELETE",
                data:{
                    _token:'{{ csrf_token() }}',
                },
                success:function(response){
                    console.log(response);
                    drawProductRow(response.products);
                },
            });
                
            
        });
        
    

            
        

            
    });
    

</script>
@endsection

