<?php

namespace App\Services\Import;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;

class ImportHandler implements ImportHandlerInterface
{
    public function import(array $clearMode): void
    {
        foreach ($clearMode as $r) {
            $category = Category::updateOrCreate(
                ['name' => $r['category_name']],
                ['name' => $r['category_name']]
            );

            $product = Product::updateOrCreate(
                [
                    'name' => $r['name'],
                    'description' => $r['description'],
                    'price' => $r['price']
                ],
                [
                    'name' => $r['name'],
                    'description' => $r['description'],
                    'price' => $r['price'],
                    'category_id' => $category->id
                ]
            );

            $json_string = str_replace("'", '"', $r['images']);
            $images = json_decode($json_string);

            foreach ($images as $image) {
                $createdImage = Image::updateOrCreate(
                    ['name' => $image, 'product_id' => $product->id],
                    ['name' => $image, 'product_id' => $product->id]
                );
            }
        }
    }
}