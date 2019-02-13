<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExampleModel extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'value'
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}