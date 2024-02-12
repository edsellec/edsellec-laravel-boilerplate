<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Default values for attributes to be set if the input values are incorrect.
     * If there is no default set, it will be set to NULL.
     * This only gets used if you're using the setAttribute* functions in this class.
     *
     * @var array
     */
    protected array $defaults = [];

    /**
     * Get the model id.
     *
     * @return int
     */
    public function getModelId(): int
    {
        return (int) $this->id;
    }

    /**
     * Checks if the model is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->getModelId());
    }
}