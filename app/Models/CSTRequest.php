<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CSTRequest extends Model
{
    protected $table = 'cst_requests';

    protected $fillable = [
        'user_id',
        'unique_request_id',
        'request_type',
        'test_type',
        'region',
        'city',
        'severity',
        'activity_type',
        'operator',
        'latitude',
        'longitude',
        'scenario_type',
        'scenario_set',
        'test_details',
        'route_link',
        'route_distance',
        'route_details',
        'assign_to',
        'status',
        'kml_path',
        'docs',
        'step',
        'pixel',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->unique_request_id)) {
                $lastRecord = self::orderBy('id', 'desc')->first();

                if ($lastRecord && preg_match('/CST-(\d+)/', $lastRecord->unique_request_id, $matches)) {
                    $nextNumber = intval($matches[1]) + 1;
                } else {
                    $nextNumber = 1;
                }

                $model->unique_request_id = 'CST-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function testerAssignments()
    {
        return $this->hasMany(TesterAssignment::class, 'cst_request_id');
    }

    protected $casts = [
        'kml_path' => 'array',
        'docs' => 'array',
    ];
}
