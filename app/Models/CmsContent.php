<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsContent extends Model
{
    use HasFactory;
    protected $table = 'cms_content';
    protected $fillable = ['org_id','cnt_id','lang_id','content','is_active','created_by'];
    
}

