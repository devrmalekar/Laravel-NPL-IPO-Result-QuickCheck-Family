<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDmatDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_dmat_detail';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_name', 'acc_num', 'dmat_id', 'client_id','pid'
    ];

    public function People(){
        return $this->belongsTo('People');
    }
}
