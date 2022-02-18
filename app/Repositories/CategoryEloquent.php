<?php

namespace App\Repositories;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Support\Facades\DB;
class CategoryEloquent extends BaseController
{
    private $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function index()
    {
        $category = Category::get();
        return $this->sendResponse('all categories', CategoryResource::collection($category));
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {
        $image = $data['image'];
        if ($image != null) {
            $filename = $data['image']->store('public/images');
            $imagename = $data['image']->hashName();
            $data['image'] = $imagename;
        }
        $category= Category::create([
            'image'=>$data['image'],
        ]);
        if (isset($data['category_translations']) ) {
            foreach ($data['category_translations'] as $key => $category_translation ) {

              CategoryTranslation::create([
                  //'category_id' => $data['category_translations'][$index]['category_id'],
                  'category_id' => $category->id,
                  'language' => $key,
                  'name' =>  $category_translation['name'],
              ]);
            }
         }
           return $this->sendResponse('add category successfully', new CategoryResource($category));
         DB::commit();
           } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
          }
        }


}
