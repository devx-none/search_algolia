<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //array products
        $products = [
            [
                'name' => 'Product 1',
                'description' => 'Description 1',
                'price' => '100',
                'image' => 'image1.jpg',
                'category' => 'Category 1',
                'subcategory' => 'Subcategory 1',
                'brand' => 'Brand 1',
                'vendor' => 'Vendor 1',
            ],
            [
                'name' => 'Product 2',
                'description' => 'Description 2',
                'price' => '200',
                'image' => 'image2.jpg',
                'category' => 'Category 2',
                'subcategory' => 'Subcategory 2',
                'brand' => 'Brand 2',
                'vendor' => 'Vendor 2',
            ],
            [
                'name' => 'Product 3',
                'description' => 'Description 3',
                'price' => '300',
                'image' => 'image3.jpg',
                'category' => 'Category 3',
                'subcategory' => 'Subcategory 3',
                'brand' => 'Brand 3',
                'vendor' => 'Vendor 3',
            ],
            [
                'name' => 'Product 4',
                'description' => 'Description 4',
                'price' => '400',
                'image' => 'image4.jpg',
                'category' => 'Category 4',
                'subcategory' => 'Subcategory 4',
                'brand' => 'Brand 4',
                'vendor' => 'Vendor 4',
            ],
            [
                'name' => 'Product 5',
                'description' => 'Description 5',
                'price' => '500',
                'image' => 'image5.jpg',
                'category' => 'Category 5',
                'subcategory' => 'Subcategory 5',
                'brand' => 'Brand 5',
                'vendor' => 'Vendor 5',
            ]
        ];

        //insert products
        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }

       
        
        
        
        




    }

}
