<?php

namespace App\Http\Controllers\Apis;

use App\Models\Brand;
use App\Models\Product;
use App\Http\traits\media;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\traits\ApiTrait;

class ProductController extends Controller
{
    use media,ApiTrait;
   public function index()
   {
       $products = Product::all(); // select * from products 
       return $this->Data(compact('products')); // return data
   }

   public function create()
   {
        $brands = Brand::all();
        $subcategories = Subcategory::select('id','name_en')->get();
        return $this->Data(compact('brands','subcategories'));    // return data
   }
 
   public function edit($id)
   {
       // validation on ID
        // $product = Product::where('id',$id)->first(); 
        $product = Product::findOrFail($id); // 151551515 // gallal // dlsfkjlkas
        $brands = Brand::all();
        $subcategories = Subcategory::select('id','name_en')->get();
        return $this->Data(compact('product','brands','subcategories')); // return data

   }

   public function store(StoreProductRequest $request)
    {
        $photoName = $this->uploadPhoto($request->image,'products');
        $data = $request->except('image');
        $data['image'] = $photoName;
        Product::create($data);
        return $this->SuccessMessage("product created successfully",201); // return success message
    }

    public function update(UpdateProductRequest $request , $id)
    {
        // if photo exists => upload it
        $data = $request->except('image','_method');
        if($request->has('image')){
            // delete photo
            $oldPhotoName = Product::find($id)->image;
            $photoPath = public_path('/dist/img/products/').$oldPhotoName;
            $this->deletePhoto($photoPath);
            // upload image
            $photoName = $this->uploadPhoto($request->image,'products');
            $data['image'] = $photoName;
        }
        // update product in db
        Product::where('id',$id)->update($data);
        // redirect.
        return $this->SuccessMessage('product updated successfully'); // return success message
    }

    public function destroy($id) // galal
    {
        // delete photo
        $product = Product::find($id);
        if($product){
            $oldPhotoName = $product->image;
            $photoPath = public_path('/dist/img/products/').$oldPhotoName;
            $this->deletePhoto($photoPath);
            // delete product
            Product::where('id',$id)->delete();
            return $this->SuccessMessage('product Deleted successfully'); // return success message
        }else{
            return $this->ErrorMessage(['id'=>'The Id Is Invalid'],'The given data was invalid.',422); // return Error message
        }
        
    }
}
