<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductData extends Model
{
    protected $table = 'tblProductData';

    protected $fillable = [
        'strProductName',
        'strProductDesc',
        'strProductCode',
        'stock',
        'price',
        'dtmAdded',
        'dtmDiscontinued'
    ];

    public $timestamps = false;
}
