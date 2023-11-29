<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'id',
        'invoice_number',
        'user',
        'comment',
        'date'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_number', 'invoice_number');
    }
}
