<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class GoogleMapApi extends Model
{
    use Userstamps, Auditable;
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
