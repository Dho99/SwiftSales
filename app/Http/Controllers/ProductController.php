<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
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

    public function index(Request $request)
    {
        $products = Product::all()->take(6);

        if($request->ajax()){
            return response()->json(['data' => $products], 200);
        }else{
            return view('Admin.Product.list', [
                'title' => 'Daftar Produk',
                'products' => $products,
                'categories' => Category::all(),
            ]);

        }

    }

    public function ajaxRequestProduct(Request $request, $skip){
        $products = Product::all()->skip($skip)->take(6);
        $productArray = [];
        foreach($products as $product){
            $productArray[] = [
                'id' => $product->id,
                'code' => $product->code,
                'batch' => $product->batch,
                'name' => $product->name,
                'description' => strip_tags($product->description),
                'supplier' => $product->supplier->name,
                'category' => $product->category->name,
                'images' => json_decode($product->images),
                'stock' => $product->stock,
                'buyPrice' => $product->buyPrice,
                'sellPrice' => $product->sellPrice,
                'expiredDate' => Carbon::parse($product->expiredDate)->format('d F Y'),
            ];
        }
        if($request->ajax()){
            return response()->json(['message' => 'Data berhasil dimuat ulang', 'data' => $productArray], 200);
        }else{
            abort(400);
        }
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

    public function stockInView(){
        return view('Admin.Product.stock-in', [
            'title' => 'Stock-in Produk',
            'recomendations' => Product::where('stock', '<=', 10)->get(),
        ]);
    }

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

    public function ajaxGetProduct(Request $request, $code)
    {
        if($request->ajax()){
            $product = Product::where('code', $code)->first();
            if(empty($product)){
                return response('Not Found', 404);
            }
            $stockInHistory = StockHistory::where('productId', $product->id)->with('user','product')->get();
            return response()->json(['data' => [$product, $stockInHistory]]);
        }else{
            abort(400);
        }
    }

    public function storeNewStock(Request $request)
    {
        if($request->ajax()){
            $data = $request->validate([
                'newStock' => 'required',
                'productCode' => 'required'
            ]);

            $newStock = $data['newStock'];
            $oldStock = $request->oldStock;

            $calculateStock = $oldStock += $newStock;

            try{
                $product = Product::where('code', $data['productCode']);

                $product->update([
                    'stock' => $calculateStock
                ]);

                $history = StockHistory::create([
                    'productId' => $product->get()->pluck('id')->first(),
                    'before' => $request->oldStock,
                    'after' => $calculateStock,
                    'userId' => auth()->user()->id
                ]);
                return response()->json(['message' => 'Data Stock produk berhasil ditambahkan'], 201);
            }catch(\Exception $e){
                return response($e->getMessage(), 500);
            }

        }else{
            abort(400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, Request $request)
    {
        if($request->ajax()){
            $data = $product;
            $product['images'] = json_decode($product['images']);
            return response()->json(['data' => $data], 200);
        }else{
            return view('Admin.Product.edit-update', [
                'title' => 'Edit data Produk',
                'product' => $product,
                'suppliers' => Supplier::all(),
                'categories' => Category::all(),
                'beforeThisUrl' => $this->UrlHandler->beforeCreateUrl(),
                'storeFormUrl' => $this->UrlHandler->storeFormUrl(),
                'imageArray' => json_decode($product->images),
            ]);
        }
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

        foreach(json_decode($imagePaths) as $image){
            $imageReplacedStoragePath = str_replace('/storage', '/public', $image);
            Storage::delete($imageReplacedStoragePath);
        }


        $product->delete();
        return response()->json(['message' => 'Data Produk berhasil dihapus'], 200);
    }
}
