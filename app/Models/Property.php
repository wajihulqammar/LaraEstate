<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'price',
        'city',
        'address',
        'property_type',
        'bedrooms',
        'bathrooms',
        'area_size',
        'area_unit',
        'seller_id',
        'status',
        'featured_image',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'area_size' => 'decimal:2',
        'is_featured' => 'boolean',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhere('address', 'LIKE', "%{$search}%");
        });
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return '₨ ' . number_format($this->price, 0);
    }

    public function getMainImageAttribute()
    {
        return $this->featured_image ?: ($this->images->first()->image_path ?? 'placeholder.jpg');
    }

    public function getTruncatedDescriptionAttribute()
    {
        return \Str::limit($this->description, 150);
    }
}