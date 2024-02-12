<?php

namespace App\Models;

use App\Constants\DatabaseTableConstant;
use App\Data\Example\ItemData;
use Illuminate\Support\Str;

/**
 * Class Item
 *
 * @package App\Models
 * @property string $id
 * @property string $name
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * 
 * Model relationships
 *
 * @mixin Eloquent
 */
class Item extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = DatabaseTableConstant::ITEMS;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The additional attributes to include in the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Specify the type of the primary key
    protected $keyType = 'uuid';

    // Disable auto-incrementing for the primary key
    public $incrementing = false;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * Convert model to a data object.
     *
     * @return ItemData
     */
    public function toData()
    {
        return ItemData::from([
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->deleted_at,
        ]);
    }
}
