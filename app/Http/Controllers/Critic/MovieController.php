<?php

namespace App\Http\Controllers\Critic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Movie;
use App\User;
class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



     public function __construct()
     {
         $this->middleware('auth');
        //$this->middleware('role:user');
     }

    public function index()
    {
        $movies = Movie::all();
        return view('movies.index')->with([
          'movies' =>$movies

          $users = User::all();
           return view('users.movies.index')->with([
            'users' => $users
                 ]);
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = Movie::findOrFail($id);


        $reviews = $movie->reviews()->get();
        return view('movies.show')->with([
          'movie' =>$movie,
          'reviews' =>$reviews

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

}
