<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Exports\CinemaExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;


class CinemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        return view('admin.cinema.index', compact('cinemas'));
    }

    public function datatables()
    {
        $cinemas = Cinema::query();

        return DataTables::of($cinemas)
            ->addIndexColumn()
            ->addColumn('name', function ($cinema) {
                return $cinema->name;
            })
            ->addColumn('location', function ($cinema) {
                return $cinema->location;
            })
            ->addColumn('seating_capacity', function ($cinema) {
                return $cinema->seating_capacity;
            })
            ->addColumn('actions', function ($cinema) {
                $btnEdit = '<a href="' . route('admin.cinemas.edit', ['id' => $cinema->id]) . '" class="btn btn-primary me-2">Edit</a>';
                $btnDelete = '<form action="' . route('admin.cinemas.delete', ['id' => $cinema->id]) . '" method="POST" style="display:inline;">'
                   . '<input type="hidden" name="_token" value="' . csrf_field() . method_field('DELETE') . ''
                    . '<input type="hidden" name="_method" value="DELETE" >'
                    . '<button class="btn btn-danger" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Hapus</button>'
                    . '</form>';
                return '<div  class="d-flex justify-content-center align-items-center gap-2">' .
 $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['name', 'location', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cinema.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required|min:10',
        ],[
            'name.required' => 'Nama bioskop wajib diisi',
            'location.required' => 'Lokasi bioskop wajib diisi',
            'location.min' => 'Lokasi bioskop minimal 10 karakter',
        ]);
        $createData = Cinema::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if($createData){
            return redirect()->route('admin.cinemas.index')->with('success','Berhasil Membuat Data Baru!');
        }else{
            return redirect()->route('admin.cinemas.create')->with('error','Gagal! silakan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cinema $cinema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cinema = Cinema::find($id);
       //dd($cinema->toArray());
        return view('admin.cinema.edit', compact('cinema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required|min:10',
        ],[
            'name.required' => 'Nama bioskop wajib diisi',
            'location.required' => 'Lokasi bioskop wajib diisi',
            'location.min' => 'Lokasi bioskop minimal 10 karakter',
        ]);
       $updateData = Cinema::where('id', $id)->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if($updateData){
            return redirect()->route('admin.cinemas.index')->with('success','Berhasil Mengubah Data!');
        }else{
            return redirect()->back()->with('error','Gagal! silakan coba lagi');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedules = Schedule::where('cinema_id', $id)->count();
        if($schedules){
            return redirect()->route('admin.cinemas.index')->with('error','Gagal! Data bioskop masih memiliki jadwal tayang');
        }
        Cinema::where('id', $id)->delete();
        return redirect()->route('admin.cinemas.index')->with('success','Berhasil Menghapus Data!');
    }
public function trash()
{
    $cinemasTrash = Cinema::onlyTrashed()->get();
    return view('admin.cinema.trash', compact('cinemasTrash'));
}

public function restore($id)
{
    $cinema = Cinema::onlyTrashed()->findOrFail($id);
    $cinema->restore();
    return redirect()->route('admin.cinemas.trash')->with('success', 'Data berhasil dikembalikan!');
}

public function deletePermanent($id)
{
    $cinema = Cinema::onlyTrashed()->findOrFail($id);
    $cinema->forceDelete();
    return redirect()->route('admin.cinemas.trash')->with('success', 'Data berhasil dihapus permanen!');
}

    public function export()
    {
        return Excel::download(new CinemaExport, 'data_bioskop.xlsx');
    }
    
    public function cinemaList()
    {
        $cinemas = Cinema::all();
        return view('schedule.cinemas', compact('cinemas'));
    }

    public function cinemaSchedules($cinema_id)
    {
        $schedules = Schedule::where('cinema_id', $cinema_id)
            ->with('movie')
            ->whereHas('movie', function ($q) {
                $q->where('actived', 1);
            })
            ->get();
        return view('schedule.cinema-schedules', compact('schedules'));
    }
}
