<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Review;
use App\Movie;
use App\User;
use Auth;
class ReviewController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('role:admin');


  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id, $rid)
  {

      Auth::user()->authorizeRoles(['admin', 'critic']);

    $review  = Review::findOrFail($rid);
    $review->delete();

    return redirect()->route('admin.movies.show', $id);
  }


}
