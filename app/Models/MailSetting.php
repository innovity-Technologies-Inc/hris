<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
