<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
  protected $fillable = [
    'name',
    'slug',
    'parent_id',
    'is_active',
    'sort_order',
    'seo_content',
    'meta_title',
    'meta_description'
  ];

  protected $casts = [
    'is_active' => 'boolean',
  ];

  /**
   * Get the parent area (main location)
   */
  public function parent(): BelongsTo
  {
    return $this->belongsTo(Area::class, 'parent_id');
  }

  /**
   * Get all sub-locations (children)
   */
  public function children(): HasMany
  {
    return $this->hasMany(Area::class, 'parent_id')->where('is_active', true)->orderBy('sort_order');
  }

  /**
   * Check if this is a main area (no parent)
   */
  public function isMainArea(): bool
  {
    return $this->parent_id === null;
  }

  /**
   * Check if this is a sub-location (has parent)
   */
  public function isSubLocation(): bool
  {
    return $this->parent_id !== null;
  }

  /**
   * Get full location name (e.g., "Dhanmondi, Dhaka")
   */
  public function getFullNameAttribute(): string
  {
    if ($this->isSubLocation() && $this->parent) {
      return $this->name . ', ' . $this->parent->name;
    }
    return $this->name;
  }

  /**
   * Scope to get only main areas
   */
  public function scopeMainAreas($query)
  {
    return $query->whereNull('parent_id');
  }

  /**
   * Scope to get only sub-locations
   */
  public function scopeSubLocations($query)
  {
    return $query->whereNotNull('parent_id');
  }
}
