<?php
// filepath: c:\laragon\www\tixid-1\app\Http\Controllers\PromoController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PromoExport;
use Yajra\DataTables\Facades\DataTables;



class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::all();
        return view('Staff.promo.index', compact('promos'));
    }

    public function datatables()
    {
        $promos = Promo::all();
        return DataTables::of($promos)
            ->addIndexColumn()
            ->addColumn('promo_code', function ($promo) {
                return strtoupper($promo->promo_code);
            })
            ->addColumn('type', function ($promo) {
                return ucfirst($promo->type);
            })
            ->addColumn('discount', function ($promo) {
                if ($promo->type == 'percent') {
                    return $promo->discount . '%';
                } else {
                    return 'Rp ' . number_format($promo->discount, 0, ',', '.');
                }
            })
            ->addColumn('active', function ($promo) {
                if ($promo->active) {
                    return '<span class="badge badge-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-danger">Nonaktif</span>';
                }
            })
            ->addColumn('actions', function ($promo) {
                $btnEdit = '<a href="' . route('staff.promos.edit', ['id' => $promo->id]) . '" class="btn btn-primary me-2">Edit</a>';

                $btnDelete = '<form action="' . route('staff.promos.delete', ['id' => $promo->id]) . '" method="POST" style="display:inline;">'
                   . csrf_field()
                   . method_field('DELETE')
                   . '<button class="btn btn-danger" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Hapus</button>'
                   . '</form>';

                return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['actions', 'promo_code', 'type', 'discount', 'active'])
            ->make(true);
    }

    public function create()
    {
        return view('Staff.promo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'required',
            'type' => 'required|in:percent,rupiah',
            'discount' => 'required|numeric|min:1',
        ]);
        $request['active'] = true; // Set active to true by default
        Promo::create($request->only('promo_code', 'type', 'discount', 'active'));
        return redirect()->route('staff.promos.index')->with('success', 'Promo berhasil ditambahkan');
    }

    public function edit($id)
    {
        $promo = Promo::findOrFail($id);
        return view('Staff.promo.edit', compact('promo'));
    }

    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);
        $request->validate([
            'promo_code' => 'required',
            'type' => 'required|in:percent,rupiah',
            'discount' => 'required|numeric|min:1',
        ]);
        $promo->update($request->only('promo_code', 'type', 'discount'));
        return redirect()->route('staff.promos.index')->with('success', 'Promo berhasil diupdate');
    }

    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();
        return redirect()->route('staff.promos.index')->with('success', 'Promo berhasil dihapus');
    }
public function trash()
{
    $promosTrash = Promo::onlyTrashed()->get();
    return view('Staff.promo.trash', compact('promosTrash'));
}

public function restore($id)
{
    $promo = Promo::onlyTrashed()->findOrFail($id);
    $promo->restore();
    return redirect()->route('staff.promos.trash')->with('success', 'Promo berhasil dikembalikan!');
}

public function deletePermanent($id)
{
    $promo = Promo::onlyTrashed()->findOrFail($id);
    $promo->forceDelete();
    return redirect()->route('staff.promos.trash')->with('success', 'Promo berhasil dihapus permanen!');
}
    public function export()
    {
        return Excel::download(new PromoExport, 'data_promo.xlsx');
    }
}
