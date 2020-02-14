<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
  public function users(){
    return $this->belongsTo('App\User')->using('App\Critic');
  }

  //public function book(){
  //  return $this->belongsTo('App\Book');
//  }
}
