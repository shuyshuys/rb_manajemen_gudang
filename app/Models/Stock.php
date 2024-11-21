<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Stock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'qty_balance',
        'qty_opname',
        'qty_difference',
        'location',
        'month',
        'year',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'item_id' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        // Set created_by saat pertama kali dibuat
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        // Set updated_by saat diperbarui
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getQtyDifferenceAttribute()
    {
    return $this->qty_balance - $this->qty_opname;
    }
}
