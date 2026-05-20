<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class MailSetting extends Model
{
    protected $table = 'mail_settings';
    protected $fillable = [
        'app_name',
        'mail_host',
        'encryption_type',
        'sender_email',
        'password',
        'port'
    ];

    /**
     * Accessor: Decrypt the mail_host when retrieved.
     */
    public function getMailHostAttribute($value)
    {
        return $this->decryptValue($value);
    }

    /**
     * Mutator: Encrypt the mail_host before saving.
     */
    public function setMailHostAttribute($value)
    {
        $this->attributes['mail_host'] = !empty($value) ? Crypt::encrypt($value) : null;
    }

    /**
     * Accessor: Decrypt the sender_email when retrieved.
     */
    public function getSenderEmailAttribute($value)
    {
        return $this->decryptValue($value);
    }

    /**
     * Mutator: Encrypt the sender_email before saving.
     */
    public function setSenderEmailAttribute($value)
    {
        $this->attributes['sender_email'] = !empty($value) ? Crypt::encrypt($value) : null;
    }

    /**
     * Accessor: Decrypt the password when retrieved.
     */
    public function getPasswordAttribute($value)
    {
        return $this->decryptValue($value);
    }

    /**
     * Mutator: Encrypt the password before saving.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = !empty($value) ? Crypt::encrypt($value) : null;
    }

    /**
     * Helper to safely decrypt values or return plain text if decryption fails.
     */
    private function decryptValue($value)
    {
        if (empty($value)) {
            return $value;
        }

        try {
            return Crypt::decrypt($value);
        } catch (DecryptException $e) {
            return $value;
        }
    }
}

