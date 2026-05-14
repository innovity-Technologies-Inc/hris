<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $table = 'api_keys';
    protected $fillable = ['google_maps_api_key'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'google_maps_api_key' => 'encrypted',
        ];
    }

    /**
     * Get the google_maps_api_key, handling potential decryption errors for unencrypted data.
     */
    protected function getGoogleMapsApiKeyAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        try {
            return decrypt($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // If decryption fails, it's likely already unencrypted or encrypted with a different key
            return $value;
        }
    }
}
