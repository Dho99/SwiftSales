<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\UrlHandler;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $UrlHandler;
    protected $userData;

    public function __construct(UrlHandler $UrlHandler, UserController $userController){
        $this->UrlHandler = $UrlHandler;
        $this->userData = $userController;
    }

    public function index()
    {
        return view('Admin.Product.list', [
            'title' => 'Daftar Produk',
            'products' => Product::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // dd($this->UrlHandler);
        return view('Admin.Product.create', [
            'title' => 'Tambah Produk',
            'suppliers' => Supplier::all(),
            'categories' => Category::all(),
            'beforeThisUrl' => $this->UrlHandler->beforeCreateUrl(),
            'storeFormUrl' => $this->UrlHandler->storeFormUrl()
        ]);
    }

    // public function uploadDescriptionImage(Request $request){
    //     if($request->hasFile('file')){
    //         $file = $request->file('file');
    //         $filenametostore = time() . '.' . $file->getClientOriginalExtension();
    //         $path = $file->move(storage_path('app/public/uploads/descriptionImage'), $filenametostore);
    //         echo $path;
    //         exit();
    //     }
    // }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:products',
            'name' => 'required|unique:products,name',
            'description' => 'required',
            'supplierId' => 'required',
            'categoryId' => 'required',
            'images' => 'required|json',
            'buyPrice' => 'required',
            'sellPrice' => 'required|gt:buyPrice',
            'expiredDate' => 'required|after:today'
        ]);
        // $data = $request->all();
        $data['userId'] = $this->userData->authUser()->id;
        $data['batch'] = 0;
        $data['stock'] = 0;
        // $data['imagesArr'] = [];

        // foreach(json_decode($data['jsonImages']) as $image){
        //     $data['imagesArr'][] = '/storage/uploads/productImage/'.$image;
        // }

        // $data['images'] = json_encode($data['imagesArr']);

        try{
            Product::create($data);
            return response()->json(['message' => 'Data Produk berhasil di Tambahkan'], 200);
        }catch(\Exception $e){
            abort(500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, Request $request)
    {
        return view('Admin.Product.edit-update', [
            'title' => 'Edit data Produk',
            'product' => $product,
            'suppliers' => Supplier::all(),
            'categories' => Category::all(),
            'beforeThisUrl' => $this->UrlHandler->beforeCreateUrl(),
            'storeFormUrl' => $this->UrlHandler->storeFormUrl(),
            'imageArray' => json_decode($product->images),
        ]);
        // if($request->ajax()){
        //     return response()->json(['data' => $product], 200);
        // }else{
        //     abort(400);
        // }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'description' => 'required',
            'supplierId' => 'required',
            'categoryId' => 'required',
            'images' => 'required|json',
            'buyPrice' => 'required',
            'sellPrice' => 'required|gt:buyPrice',
            'expiredDate' => 'required|after:today'
        ];

        if($request->code != $product->code){
            $rules['code'] = 'required|unique:product,code';
        }

        if($request->name != $product->name){
            $rules['name'] = 'required|unique:product,name';
        }

        $validated = $request->validate($rules);



        try{
            $product->update($validated);
            return response()->json(['message' => 'Data berhasil diperbarui'], 200);
        }catch(\Exception $e){
            abort($e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $imagePaths = $product->images;
        // $images = [];
        foreach(json_decode($imagePaths) as $image){
            $imageReplacedStoragePath = str_replace('/storage', '/public', $image);
            Storage::delete($imageReplacedStoragePath);
        }

        // foreach($images as $img){
        // }

        $product->delete();
        return response()->json(['message' => 'Data Produk berhasil dihapus'], 200);
    }
}
