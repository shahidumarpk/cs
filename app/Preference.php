<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    protected $fillable = [
        'option','description','value', 'created_by', 'modified_by'
    ];
        
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    
    public function createdby()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function modifiedby()
    {
        return $this->belongsTo('App\User', 'modified_by');
    }
}
