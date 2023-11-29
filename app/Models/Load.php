<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Load extends Model
{
    protected $table = 'loads';
    public $timestamps = false;
    protected $primaryKey = 'load_number';

    protected $fillable = [
        'load_number',
        'load_status',
        'load_mode',
        'customer_quote_total',
        'customer_invoice_total',
        'total_weight'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'load_number', 'load_number');
    }
}
