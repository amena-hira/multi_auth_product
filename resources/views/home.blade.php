@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-info">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Customer, You are logged in!') }}

                    <div class="mt-2">
                        <h2 class="text-center">Product Table</h2>
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
                <div class="card-footer text-muted">
                    all rights reserved to Amena Akhter Hira
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
            product_data += '<td><button class="btn btn-primary" name="btnBuy" id="btnBuy" value="'+value.id+'">Buy</button></td>';
            product_data += '</tr>';
            i++;
        });

        $('table tbody').html(product_data);
    }
    $(document).ready(function(){
        $.ajax({
            url:"{{ route('customer.data_show') }}",
            type: "get",
            success:function(response){
                drawProductRow(response);
            },
        });
    });
</script>
@endsection
