<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = "people";
    protected $guarded = ["id","created_at","updated_at"];
    protected $appends = ["full_name"];

    public function getFullNameAttribute(){
        return ucwords("$this->firstName $this->lastName");
    }

}
