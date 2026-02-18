<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class InteractionLog extends Model
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
    protected $collection = 'interaction_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'message',
        'response',
        'platform',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     * Only using created_at for logs.
     *
     * @var bool
     */
    const UPDATED_AT = null;

    /**
     * Get the client that owns this interaction log.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
