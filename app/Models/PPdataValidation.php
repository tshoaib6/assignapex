<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPdataValidation extends Model
{
    use HasFactory;
protected $table ='pp_data_validation';
    protected $fillable = [
        'cst_request_id',
        'decision',
        'notes',
        'docs',
        'status',
    ];

    /**
     * Get the CST request associated with the decision.
     */
    public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class, 'cst_request_id');
    }
    protected $casts = [
        'docs' => 'array',
    ];
}

