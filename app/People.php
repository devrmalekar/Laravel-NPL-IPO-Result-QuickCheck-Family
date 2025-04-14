<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'people';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname', 'mname', 'lname','addr', 'father_name', 'gfather_name', 'citizenship_no', 'date_of_issue'
    ];

    public function BankDematDetail(){
        return $this->hasMany('BankDematDetail');
    }
}
