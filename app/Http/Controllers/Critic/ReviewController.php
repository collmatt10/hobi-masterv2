<?php

namespace App\Http\Controllers\Critic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Movie;
use App\Review;
use App\User;
use Auth;

class ReviewController extends Controller
{




  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('role:critic');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $movie = Movie::findOrFail($id);

        return view('critic.reviews.create')->with([
          'movie' => $movie
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $review = new Review();
        $review->title = $request->input('title');
        $review->body = $request->input('body');
        $review->movie_id = $id;
        $review->user_id = Auth::id(); //takes authorised loggied in users id


        $review->save();

        return redirect()->route('user.movies.show', $id);
      }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
