<?php

namespace App\Http\Controllers;

use App\Models\Images;
use Illuminate\Http\Request;
use App\Repositories\ImagesRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Collection;

class ImagesController extends Controller
{

    private $imagesRepository;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index','show']]);
        $this->middleware('verified', ['except' => ['index','show']]);
        $this->authorizeResource(Images::class, 'image');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //if query contains filter attributes
        if( $request->filled('age_from') || $request->filled('age_to') || $request->filled('gender') || $request->filled('description')) 
        {

            $request->filled('age_from') ? $age_from = $request->get('age_from') : $age_from = 0;
            $request->filled('age_to') ? $age_to = $request->get('age_to') : $age_to = 200;
            $request->filled('description') ?  $description = '%'. $request->get('description').'%' : $description = null;
            $request->filled('gender') ? $gender = $request->get('gender') : $gender = '*';
            $max = Carbon::now()->subYears($age_from)->toDateTimeString();
            $min = Carbon::now()->subYears($age_to)->toDateTimeString();
            
            $images = Images::whereHas("user", function($query) use ($max, $min, $gender){
                $query->whereBetween('birthdate', [$min,$max])->where('gender','=', $gender);
            });

            isset($description) ? $images->where('description','like','%'.$description.'%') : $images;

            $images = $images->where([
                ['published', '=', '1'],
                ['published_at', '<', Carbon::now()],
            ])
            ->orderBy('published_at', 'desc')->get();
        }

        else $images = Images::where([
            ['published', '=', '1'],
            ['published_at', '<', Carbon::now()],
        ])
        ->orderBy('published_at', 'desc')->get();
        return view('feed', ['images' => $images]);
    }

    /**
     * Display a listing of the user resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function my(Request $request)
    {
        $images = $request->user()->images->sortBy('published_at', null, true);

        if($request->filled('filter'))
        {
            switch ($request->get('filter')) {
                case '1':
                default: {
                    $images = $images->where('published', '=', '1')
                    ->where('published_at', '<', (Carbon::now()->toDateTimeString()))
                    ->sortBy('published_at', null, true);
                    }
                    break;
                case '2':{
                    $images = $images->where('published', '=', '0')
                    ->sortBy('published_at', null, true);
                    }
                    break;
                case '3':{
                        $images = $images->where('published', '=', '1')
                        ->where('published_at', '>', (Carbon::now()->toDateTimeString()))
                        ->sortBy('published_at', null, true);
                    }
                    break;
            }
        }
        return view('feed', ['images' => $images]);
    }

    /**
     * Show the form for creating a new resource.
     *     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('images.create');
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!empty($request->file('image'))) {
            $request->validate([
                'image' => 'image',
            ]);
            $image = new Images();
            $image->user_id = Auth::id();
            $image->filepath = $request->file('image')->store('images');
            $image->origin = $request->file('image')->getClientOriginalName();
            $image->published = 0;
            $image->save();
            return response()->json([
                'image' => route('images.show', ['image' => $image->id]),
                'id' => $image->id
            ]);
        }
        else if($request->filled('id'))
        {
            $image = Images::findOrFail($request->get('id'));
            if(!empty($image)) {
                $image->description = $request->get('description') ?? '';
                $image->published_at = Carbon::createFromFormat('Y-m-d H:i:s P',$request->get('published_at'))->tz('UTC') > Carbon::now() ?  
                Carbon::createFromFormat('Y-m-d H:i:s P',$request->get('published_at'))->tz('UTC') : Carbon::now();
                $image->published = (bool)$request->get('published') ?? false;
                $image->save();
                return back()->with('status', 'Image created!');
            }
        }
        else abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  Images $image
     * @return \Illuminate\Http\Response
     */
    public function show(Images $image)
    {
        //
        // dd($image);
            return response()->file(Storage::disk('public')->path($image->filepath));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Images $image)
    {
        return view('images.edit',['image' => $image]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Images $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Images $image)
    {
            if (!empty($request->file('image'))) 
            {
                $request->validate([
                    'image' => 'image',
                ]);
                $image->filepath = $request->file('image')->store('images');
                $image->origin = $request->file('image')->getClientOriginalName();
                $image->save();

                return response()->json([
                    'image' => route('images.show', ['image' => $img->id])
                ]);

            }

                $image->description = $request->get('description') ?? $image->description;

                if (! ($image->published && ($image->published_at < Carbon::now())))
                {
                    (Carbon::createFromFormat('Y-m-d H:i:s P',$request->get('published_at'))->tz('UTC') > Carbon::now()) ? 
                    $image->published_at = Carbon::createFromFormat('Y-m-d H:i:s P',$request->get('published_at'))->tz('UTC') :  $image->published_at = Carbon::now();

                    $image->published = (bool)$request->get('published') ?? $image->published;
                }
                $image->save();

                return back()->with('status', 'Image updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Images $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Images $image)
    {
        $image->delete();
        return back()->with('status', 'Image deleted!');
    }
}
