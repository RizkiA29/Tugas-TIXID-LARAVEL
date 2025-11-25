<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;
use Yajra\DataTables\Facades\DataTables;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movie.index', compact('movies'));
    }

    public function chart()
    {
        $filmActive = Movie::where('actived', 1)->count();
        $filmNonActive = Movie::where('actived', 0)->count();
        $data = [ $filmActive, $filmNonActive];
        return response()->json([
            'data' => $data
        ]);
    }

    public function datatables()
    {
        $movies = Movie::query();

        return DataTables::of($movies)
            ->addIndexColumn()
            ->addColumn('poster_img', function ($movie) {
                $url = asset('storage/' . $movie->poster);
                return '<img src="' . $url . '" width="70">';
            })
            ->addColumn('actived_badge', function ($movie) {
                if ($movie->actived) {
                    return '<span class="badge badge-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-danger">Nonaktif</span>';
                }
            })
            ->addColumn('actions', function ($movie) {
               $btnDetail = '<button class="btn btn-secondary me-2" onclick= \'showModal(' . json_encode($movie) . ')\'>Detail</button>';
               $btnEdit = '<a href="' . route('admin.movies.edit', ['id' => $movie->id]) . '" class="btn btn-primary me-2">Edit</a>';

               $btnDelete = '<form action="' . route('admin.movies.delete', ['id' => $movie->id]) . '" method="POST" style="display:inline;">'
                   . '<input type="hidden" name="_token" value="' . csrf_field() . method_field('DELETE') . ''
                   . '<input type="hidden" name="_method" value="DELETE">'
                   . '<button class="btn btn-danger" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Hapus</button>'
                   . '</form>';

               $btnNonAktif = '';
               if ($movie->actived == 1) {
                   $btnNonAktif = '<form action="' . route('admin.movies.nonactive', ['id' => $movie->id]) . '" method="POST" style="display:inline;margin-left:8px;">'
                       . '<input type="hidden" name="_token" value="' . csrf_field() . method_field('PATCH') . ''
                       . '<input type="hidden" name="_method" value="PATCH">'
                       . '<button class="btn btn-warning ms-2" onclick="return confirm(\'Nonaktifkan film ini?\')">Non-Aktifkan</button>'
                       . '</form>';
               }

               return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnDetail . $btnEdit . $btnDelete . $btnNonAktif . '</div>';
            })
            ->rawColumns(['poster_img','actived_badge','actions'])
            ->make(true);
    }

public function home()
{
    $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->limit(3)
        ->get();
    return view('home', compact('movies'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function homeMovies(Request $request)
    {
        $nameMovie = $request->search_movie;
        if ($nameMovie != "") {
            $movies = Movie::where('actived', 1)
                ->where('title', 'LIKE', '%' . $nameMovie . '%')
                ->orderBy('created_at', 'DESC')
                ->get();
            return view('movies', compact('movies'));

        } else {

        $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->get();
        return view('movies', compact('movies'));
    }
    }
    public function movieSchedule($movie_id, Request $request)
    {
        $sort_price = $request->sort_price;
        if ($sort_price) {
            $movie = Movie::where('id', $movie_id)->with([
                'schedules' => function ($q) use ($sort_price) {
                    $q->orderBy('price', $sort_price);
                },
                'schedules.cinema'
            ])->first();
        } else {
            $movie = Movie::where('id', $movie_id)->with('schedules', 'schedules.cinema')->first();
        }

        $sortAlphabet = $request->sort_alphabet;
        if ($sortAlphabet == 'ASC') {
            $movie->schedules = $movie->schedules->sortBy(function ($schedule) {
                return $schedule->cinema->name;
            })->values();
        } elseif ($sortAlphabet == 'DESC') {
            $movie->schedules = $movie->schedules->sortByDesc(function ($schedule) {
                return $schedule->cinema->name;
            })->values();
        }

        return view('schedule.detail', compact('movie'));
    }

    public function create()
    {
        return view('admin.movie.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            'poster' => 'required|mimes:jpeg,jpg,png,svg',
            'description' => 'required',
        ], [
            'title.required' => 'Judul film wajib diisi.',
            'duration.required' => 'Durasi film wajib diisi.',
            'genre.required' => 'Genre film wajib diisi.',
            'director.required' => 'Sutradara film wajib diisi.',
            'age_rating.required' => 'Rating usia wajib diisi.',
            'poster.required' => 'Poster film wajib diunggah.',
            'poster.mimes' => 'Poster harus berupa file gambar (jpeg, png, jpg, svg).',
            'description.required' => 'Deskripsi film wajib diisi.',
        ]);

        $poster = $request->file('poster');
        $namaFile = Str::random(10) . "-poster." . $poster->getClientOriginalExtension();
        $path = $poster->storeAs('posters', $namaFile, 'public');
        $namaFile = str::random(10) . "-poster." . $poster->getClientOriginalExtension();
        $path = $poster->storeAs('posters', $namaFile, 'public');

        $createData = Movie::create([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'poster' => $path,
            'description' => $request->description,
            'actived' => 1
        ]);
        if ($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Data film berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan data film. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie, $id)
    {
        $movie = Movie::find($id);
        return view('admin.movie.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie, $id)
    {
       $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            'poster' => 'mimes:jpeg,jpg,png,svg',
            'description' => 'required',
        ], [
            'title.required' => 'Judul film wajib diisi.',
            'duration.required' => 'Durasi film wajib diisi.',
            'genre.required' => 'Genre film wajib diisi.',
            'director.required' => 'Sutradara film wajib diisi.',
            'age_rating.required' => 'Rating usia wajib diisi.',
            'poster.mimes' => 'Poster harus berupa file gambar (jpeg, png, jpg, svg).',
            'description.required' => 'Deskripsi film wajib diisi.',
            'description.min' => 'Deskripsi film minimal 10 karakter.',
        ]);
        $movie = Movie::find($id);
        if ($request->hasFile('poster')) {
           $filePath =storage_path('app/public/' . $movie->poster);
           if (file_exists($filePath)) {
            unlink($filePath);
           }
           $file = $request->file('poster');
              $fileName = Str::random(10) . "-poster." . $file->getClientOriginalExtension();
              $path = $file->storeAs('posters', $fileName, 'public');
        }
        $updateData = $movie->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'poster' => $request->hasFile('poster') ? $path : $movie->poster,
            'description' => $request->description,
            'actived' => 1
        ]);
        if ($updateData) {
            return redirect()->route('admin.movies.index')->with('success', 'Data film berhasil diubah.');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah data film. Silakan coba lagi.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
     $schedules = Schedule::where('movie_id', $id)->count();
    if ($schedules) {
        return redirect()->route('admin.movies.index')->with('error', 'Gagal! Data bioskop masih memiliki jadwal tayang');
    }

    $movie = \App\Models\Movie::findOrFail($id);

    // Hapus file poster dari storage jika ada
    if ($movie->poster && \Storage::disk('public')->exists($movie->poster)) {
        \Storage::disk('public')->delete($movie->poster);
    }

    // Hapus data movie
    $movie->delete();

    return redirect()->route('admin.movies.index')->with('success', 'Data film & poster berhasil dihapus!');
}

    public function nonactive($id)
    {
        $movie = \App\Models\Movie::findOrFail($id);
        $movie->actived = 0;
        $movie->save();

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil dinonaktifkan!');
    }

    public function trash()
    {
        $moviesTrash = Movie::onlyTrashed()->get();
        return view('admin.movie.trash', compact('moviesTrash'));
    }

    public function restore($id)
    {
        $movie = Movie::onlyTrashed()->findOrFail($id);
        $movie->restore();
        return redirect()->route('admin.movies.trash')->with('success', 'Data berhasil dikembalikan!');
    }

    public function deletePermanent($id)
    {
        $movie = Movie::onlyTrashed()->findOrFail($id);
        $movie->forceDelete();
        return redirect()->route('admin.movies.trash')->with('success', 'Data berhasil dihapus permanen!');
    }

    public function export()
    {
        $fileName = 'data-film.xlsx';
        return Excel::download(new MovieExport, $fileName);
    }
}
