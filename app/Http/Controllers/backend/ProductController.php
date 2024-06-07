<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\traits\media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 4 methods (index , edit , create , destory)
    use media;
    public function index()
    {
        // get all products
        $products = DB::table('products')->select('id', 'name_en','name_ar','price','status','quantity','code','created_at')->get();
        // pass these data to products view
        return view('backend.products.index',compact('products'));
    } 
    public function create()
    {
        $brands = DB::table('brands')->get();
        $subcategories = DB::table('subcategories')->select('id','name_en')->where('status',1)->get();
        return view('backend.products.create',compact('brands','subcategories'));
    }

    public function edit($id)
    {
        $brands = DB::table('brands')->get();
        $subcategories = DB::table('subcategories')->select('id','name_en')->where('status',1)->get();
        $product = DB::table('products')->where('id',$id)->first(); // return data as object 
        return view('backend.products.edit',compact('product','brands','subcategories'));
    }

    public function store(StoreProductRequest $request)
    {
        $photoName = $this->uploadPhoto($request->image,'products');
        $data = $request->except('_token','image','page');
        $data['image'] = $photoName;
        DB::table('products')->insert($data);
        return $this->redirectAccordingToRequest($request);
    }

    public function update(UpdateProductRequest $request , $id)
    {
        // if photo exists => upload it
        $data = $request->except('_token','_method','page','image');
        if($request->has('image')){
            // delete photo
            $oldPhotoName = DB::table('products')->select('image')->where('id',$id)->first()->image;
            $photoPath = public_path('/dist/img/products/').$oldPhotoName;
            $this->deletePhoto($photoPath);
            // upload image
            $photoName = $this->uploadPhoto($request->image,'products');
            $data['image'] = $photoName;
        }
        // update product in db
        DB::table('products')->where('id',$id)->update($data);
        // redirect.
        return $this->redirectAccordingToRequest($request);
    }

    public function destroy($id)
    {
        // delete photo
        $oldPhotoName = DB::table('products')->select('image')->where('id',$id)->first()->image;
        $photoPath = public_path('/dist/img/products/').$oldPhotoName;
        $this->deletePhoto($photoPath);
        // delete product
        DB::table('products')->where('id',$id)->delete();
        return redirect()->back()->with('success','Successfull Opertaion');
    }

    private function redirectAccordingToRequest($request)
    {
        if($request->page == 'back'){
            return redirect()->back()->with('success','Successfull Opertaion');
        }else{
            return redirect()->route('products.index')->with('success','Successfull Opertaion');
        }
    }

}
