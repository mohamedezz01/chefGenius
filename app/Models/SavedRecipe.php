<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class SavedRecipe extends Model
{
    use HasFactory;

    protected $guarded = []; 
    protected $casts = [
        'ingredients' => 'array', 
        'steps' => 'array',  
    ];
    protected $fillable = [
        'name',
        'description',
        'estimated_prep_time', // Match database column name
        'difficulty',
        'steps',
        'user_id',
        'source'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}