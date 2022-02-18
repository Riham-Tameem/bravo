<?php

namespace App\Repositories;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\FavoriteResource;
use App\Http\Resources\ProductResource;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTranslation;
use App\Models\Rate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductEloquent extends BaseController
{

    private $model;

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function index(array $data)
    {

        $product = Product::where('category_id',$data['category_id'])->get();
        return $this->sendResponse('all product', ProductResource::collection($product));
    }

    public function store(array $data)
    {
       // dd($data);
           DB::beginTransaction();

       try {
            $image = $data['image'];
            if ($image != null) {
                $filename = $data['image']->store('public/images');
                $imagename = $data['image']->hashName();
                $data['image'] = $imagename;
            }
            $product= Product::create([
                'main_price'=>$data['main_price'],
                'current_price'=>$data['current_price'],
                'image'=>$data['image'],
                'category_id'=>$data['category_id'],
            ]);
            if (isset($data['product_translations']) ) {
                foreach ($data['product_translations'] as $key => $product_translation ) {
                    ProductTranslation::create([
                        'product_id' => $product->id,
                        'language' => $key,
                        'name' =>  $product_translation['name'],
                       'description' =>  $product_translation['description'],
                    ]);
                }
            }
            if (isset($data['product_images']) ) {
                foreach ($data['product_images'] as $image) {
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $filename = $image->store('public/images');
                    $imagename = $image->hashName();
                    $productImage->image = ($imagename);
                    $productImage->save();
                }
            }
           DB::commit();
           return $this->sendResponse('add product successfully', new ProductResource($product));
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }
    }
    public function show(array $data){
        $product=Product::where('id',$data['product_id'])->first();
        if($product){
            return $this->sendResponse('show product successfully', new ProductResource($product));
        }else{
            return $this->sendError('Enter Valid Product ');

        }
   }

    public function favourite(array $data)
    {

        $product = Product::find($data['product_id']);
        $user = Auth::user()->id;
        if (!$product) {
            return $this->sendError(404 ,'There is no Product has this id');
        }
        $like= Favorite::where('product_id',$product->id)
            ->where('user_id',$user)->first();
        if(isset($like)){
            $like->delete();
            return $this->sendResponse('Remove Product from Favorite ',[]);
        }
        $favourite = Favorite::create([
            'product_id' => $product->id,
            'user_id' => $user,
            'is_favorite' => true,
        ]);

        return $this->sendResponse('Product has been added to your Favorites',  new FavoriteResource($favourite));

    }
   public function rate(array $data){

       $user=auth()->user()->id;
       $is_rate=Rate::where('user_id',$user)->where('product_id',$data['product_id'])->first();
        if(!$is_rate ){
            $rate = Rate::create([
                'product_id' => $data['product_id'],
                'user_id' => $user,
                'rate' => $data['rate'],
            ]);
            return $this->sendResponse('Rating done', []);
        }
       return $this->sendResponse('you have been rating before', []);


   }

}
