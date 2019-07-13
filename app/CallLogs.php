<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallLogs extends Model
{
    protected $table    = 'call_logs';
    protected $fillable = ['id','source_caller', 'destination_caller', 'call_start_time', 'call_end_time','call_duration','call_status','other_details','recording_file'];
	
    protected $dates = [
		'call_date',
        'created_at',
        'updated_at'
    ];		
}
