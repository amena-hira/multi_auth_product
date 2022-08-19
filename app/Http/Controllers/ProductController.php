<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'product_name' => 'required|max:191',
            'product_price' => 'required',
            'company_name' => 'required|max:191',
            'image' => 'image|mimes:jpeg,png,jpg',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message'=> $validator->messages(),
                'products'=>Product::all()

            ]);
        }
        else{
            $product = new Product;
            $product->product_name = $request->input('product_name');
            $product->product_price = $request->input('product_price');
            $product->company_name = $request->input('company_name');
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $file->move('img',$fileName );
                $product->image = $fileName;
            }
            $product->save();

            return ['success'=>true, 'message'=> 'Inserted Successfully', 'products'=>Product::all()];
        }

        
          
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return Product::all();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $product = Product::find($id);
        return ['success'=>true, 'message'=> 'Inserted Successfully', 'product'=>$product];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(),[
            'product_name' => 'required|max:191',
            'product_price' => 'required',
            'company_name' => 'required|max:191',
            'image' => 'image|mimes:jpeg,png,jpg',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message'=> $validator->messages(),
                'products'=>Product::all()

            ]);
        }
        else{
            $product = Product::find($id);
            $product->product_name = $request->product_name;
            $product->product_price = $request->product_price;
            $product->company_name = $request->company_name;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $file->move('img',$fileName );
                $product->image = $fileName;
            }
            $product->save();
            return ['success'=>true, 'message'=> 'Updated Successfully', 'products'=>Product::all()];
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
        return ['success'=>true, 'message'=> 'Deleted Successfully','products'=>Product::all()];
    }
}
