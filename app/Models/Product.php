<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $guarded = array();

    public function getData()
    {
        return static::orderBy('created_at','desc')->get();
    }

    public function storeData($input,$images)
    {//dd($input);
    	return static::create(['product_name'=>$input['name'],'product_price'=>$input['price'],'product_description'=>$input['description'],'image_path'=>json_encode($images)]);
    }

    public function findData($id)
    {
        return static::find($id);
    }

    public function updateData($id, $input)
    {
        return static::find($id)->update(['product_name'=>$input['name'],'product_price'=>$input['price'],'product_description'=>$input['description']]);
    }

    public function deleteData($id)
    {
        return static::find($id)->delete();
    }
}
