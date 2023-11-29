<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    public $timestamps = false;
    protected $primaryKey = 'invoice_number';

    protected $fillable = [
        'invoice_number',
        'load_number',
        'invoice_status',
        'invoice_date',
        'invoice_due_date',
        'billing_contact',
        'freight_charges',
        'fuel_surcharge'
    ];

    public function loadRelation()
    {
        return $this->belongsTo(Load::class, 'load_number', 'load_number');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'invoice_number', 'invoice_number');
    }
}
