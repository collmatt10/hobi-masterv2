<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Storage;
use App\Movie;
use App\User;
use Auth;

class MovieController extends Controller
{

  public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
      {
          $name = !is_null($filename) ? $filename : Str::random(25);

          $file = $uploadedFile->storeAs($folder, $name.'.'.$uploadedFile->getClientOriginalExtension(), $disk);

          return $file;
      }




  public function __construct()
  {
      $this->middleware('auth');
        $this->middleware('role:admin');
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $movies = Movie::orderBy('created_at','desc')->paginate(4);

       return view('admin.movies.index')->with([
         'movies' => $movies
       ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.movies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request)
 {
 //validation rules
         $rules = [
             'title' => 'required|string|unique:movies,title|min:2|max:191',
             'director'  => 'required|string|min:5|max:1000',
             'company'  => 'required|string|min:5|max:1000',
             'boxoffice'  => 'required|string|min:5|max:1000',
             'runtime'  => 'required|string|min:4|max:1000',
             'body'  => 'required|string|min:5|max:10000',
             'image' => 'required|file|image|',
         ];
         //custom validation error messages
         $messages = [
             'title.unique' => 'Movie title should be unique', //syntax: field_name.rule
         ];
         //First Validate the form data
         $request->validate($rules,$messages);
         //Create a movie = new

         $image = $request->file('image');
         $extension = $image->getClientOriginalExtension();
         $filename = date('Y-m-d-His'). '.' .$extension;
         $path = $image->storeAs('public/images', $filename);

         $movie = new Movie;
         $movie->title = $request->title;
         $movie->director = $request->director;
         $movie->company = $request->company;
         $movie->boxoffice = $request->boxoffice;
         $movie->runtime = $request->runtime;
         $movie->body = $request->body;
         $movie->image = $filename;
         $movie->user_id = Auth::id();
         $movie->save(); // save it to the database.
         //Redirect to a specified route with flash message.

         //if($request->hasFile('image')){
           //get image file
          //$image = $request->image;
            //get just extension
           //$ext = $image->getClientOriginalExtension();
           //make unique
           //$filename = uniqid().'.'.$ext;
           //$image->storeAs('public/images',$filename);
           //Storage::put('file.jpg', $contents);
           //Storage::delete("public/images/{$movie->image}");
           //$movie->image = $filename;
         //}
       // Persist user record to database


         $request->session()->flash('success', 'Movie added successfully!');
         return redirect()->route('admin.movies.index');
             //->with('status','Created a new Movie!'); old laravel flash status
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
       return view('admin.movies.show')->with([
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
    public function edit($id)
    {
      $movie = Movie::findOrFail($id);

      return view('admin.movies.edit')->with([
        'movie' => $movie
      ]);
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

       $movie = Movie::findOrFail($id);
       $request->validate([
         'title'=>'required|max:191'. $movie->id,
         'director'=>'required|max:191',
         'company'=>'required|max:191',
         'boxoffice'=>'required|max:191',
         'runtime'=>'required|max:5',
         'body'=>'required|max:500',
         'image' => 'required|file|image|',
       ]);

       if ($request->hasFile('image')) {
         $image = $request->file('image');
         $extension = $image->getClientOriginalExtension();
         $filename = date('Y-m-d-His'). '.' .$extension;
         $path = $image->storeAs('public/images', $filename);

        Storage::delete("public/images{$movie->image}");
        $movie->image = $filename;
       }


       $movie->title = $request->input('title');
       $movie->director = $request->input('director');
       $movie->company = $request->input('company');
       $movie->boxoffice = $request->input('boxoffice');
       $movie->runtime = $request->input('runtime');
       $movie->body = $request->input('body');


       $movie->save();

       $request->session()->flash('info', 'Movie edited successfully!');

       return redirect()->route('admin.movies.index');
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
       $movie = Movie::findOrFail($id);

       $movie->delete();

       $request->session()->flash('danger', 'Movie deleted!');

       return redirect()->route('admin.movies.index');

      return view('admin.movies.show')->with([
        'movie' => $movie
      ]);
    }
}
