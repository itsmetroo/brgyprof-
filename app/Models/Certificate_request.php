<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate_request extends Model
{
    use HasFactory;
    protected $table = 'certificate_requests';
    //Primary Key
    public $primaryKey = 'request_id';
    protected $fillable = [
        'resident_id',
        'name',
        'description',
        'age',
        'gender',
        'cert_id',
        'request_type'
    ];

    // Relationship with resident info
    public function resident()
    {
        return $this->belongsTo(resident_info::class, 'resident_id', 'resident_id');
    }

    // Relationship with certificate list
    public function certificateType()
    {
        return $this->belongsTo(Certificate_list::class, 'cert_id', 'certificate_list_id');
    }
}
