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
                        <div class="nav justify-content-end"><a class="btn btn-success mb-2 " name="modal_button" id="modal_button" data-toggle="modal" data-target="#modal">Add New</a></div>

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
                                    <form action="">
                                        @csrf
                                        
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

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary btnSave" name="btnSave" >Save</button>   
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
        $.each(products,function(key,value){
            product_data += '<tr>';
            product_data += '<td>'+i+'</td>';
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

    $(document).ready(function(){
        $.ajax({
            url:"{{ route('seller.data_show') }}",
            type: "get",
            dataType:"json",
            success:function(response){
                drawProductRow(response);
            },
        });
        document.getElementById('modal_button').addEventListener('click', function () {
            document.getElementById('exampleModalLongTitle').innerText= "New Product";
        });



        $('button[name="btnSave"]').click(function(){
                $.ajax({
                    url:"{{ route('seller.add_product') }}",
                    type: "POST",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        product_name:$('input[name="product_name"]').val(),
                        product_price:$('input[name="product_price"]').val(),
                        company_name:$('input[name="company_name"]').val()
                    },
                    dataType:"json",
                    success:function(response){
                        $('#modal').modal('hide');
                        console.log(response);
                        drawProductRow(response.products);
                    },
                });
            
        });
        

        $(document).on('click', '#btnDelete', function () {
            var id = $('button[name="btnDelete"]').val()
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

