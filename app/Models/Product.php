<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory,Searchable;

    protected $fillable = [
        'name', 'description', 'price', 'image', 'category', 'subcategory', 'brand', 'vendor',
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
         'columns' => [
             'name' => 10,
             'description' => 5,
             'price' => 5,
         ],
         
        ];

    public function searchableAs()
    {
        return 'products_index';
        
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        return $array;
    }

    public function getRouteKeyName()
    {
        return 'name';
    }



}
