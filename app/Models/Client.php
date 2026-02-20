<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Client extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'business_category',
        'brand_logo',      // Changed from profile_image
        'services',        // New: array of services
        'business_info',   // New: contact, branches, hours, payment methods
        'ai_config',       // New: AI configuration
        'offers',          // New: promotional offers
        'fb_page_id',      // New: Facebook Page ID
        'insta_account_id', // New: Instagram Account ID
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'services' => 'array',
        'business_info' => 'array',
        'ai_config' => 'array',
        'offers' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the interaction logs for this client.
     */
    public function interactionLogs()
    {
        return $this->hasMany(InteractionLog::class, 'client_id');
    }
}
