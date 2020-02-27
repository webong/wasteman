<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
