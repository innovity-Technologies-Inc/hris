<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class ApiKey extends Model
{
    protected $table = 'api_keys';
    protected $fillable = ['google_maps_api_key'];

    /**
     * Accessor: Decrypt the key when retrieved.
     * Handles plain text gracefully for migration.
     */
    public function getGoogleMapsApiKeyAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        try {
            return Crypt::decrypt($value);
        } catch (DecryptException $e) {
            // Return plain text if decryption fails
            return $value;
        }
    }

    /**
     * Mutator: Encrypt the key before saving.
     */
    public function setGoogleMapsApiKeyAttribute($value)
    {
        $this->attributes['google_maps_api_key'] = !empty($value) ? Crypt::encrypt($value) : null;
    }
}
