<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siteinfo extends Model
{
    protected $table = 'siteinfos';
    protected $fillable = [
        'site_profile',
        'phone_number',
        'address',
        'meta_email',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'email_signature',
        'facebook_url',
        'linkedin_url',
        'instagram_url'
        
            ];
    protected $hidden = [
        '_token',
    ];
}
