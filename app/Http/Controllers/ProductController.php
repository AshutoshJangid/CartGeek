<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // 
    public function index()
    {
        return view('products.index');
    }

    /**
     * Get the data for listing in yajra.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProducts(Request $request, Product $product)
    {
        $data = $product->getData();
        return \DataTables::of($data)
            ->addColumn('Actions', function($data) {
                return '<button type="button" class="btn btn-success btn-sm" id="getEditProductData" data-id="'.$data->id.'">Edit</button>
                    <button type="button" data-id="'.$data->id.'" data-toggle="modal" data-target="#DeleteProductModal" class="btn btn-danger btn-sm" id="getDeleteId">Delete</button>';
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'price' => ['required','numeric'],
            'description' => 'required',
            'p_image[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // dd($request);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $images = [];
        if ($request->hasFile('p_image')) {
            foreach($request->file('p_image') as $p_image){
                
                $name = time().'.'.$p_image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $p_image->move($destinationPath, $name);
                $images[] = $name;
            }
            
        }   
        $product->storeData($request->all(),$images);

        return response()->json(['success'=>'Product added successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = new Product;
        $data = $product->findData($id);

        $html = '<div class="form-group">
            <label for="title">Product Name:</label>
            <input type="text" class="form-control" value="'.$data->product_name.'" name="name" id="name">
        </div>
        <div class="form-group">
            <label for="title">Product Price:</label>
            <input type="number" class="form-control" value="'.$data->product_price.'" name="price" id="price">
        </div>
        <div class="form-group">
            <label for="description">Product Description:</label>
            <textarea class="form-control" name="description" id="description">                        
            '.$data->product_description.'
            </textarea>
        </div>
        <div class="form-group">
            <label for="title">Product Image: <button type="button" class="addBtn" data-target="edit"> + </button></label>
            <div class="editFile"><input type="file" class="form-control" value="$data->product_" name="p_image[]" class="p_image"></div>
            <div class="editMoreFile"></div>
        </div>
    
    ';

        return response()->json(['html'=>$html]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { //dd($request);
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'price' => ['required','numeric'],
            'description' => 'required',
            'p_image[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        $product = new Product;
        $product->updateData($id, $request->all());

        return response()->json(['success'=>'Product updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = new Product;
        $product->deleteData($id);

        return response()->json(['success'=>'Product deleted successfully']);
    }
}
