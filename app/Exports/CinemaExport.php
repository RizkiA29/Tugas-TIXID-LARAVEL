<?php
namespace App\Exports;

use App\Models\Cinema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CinemaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Cinema::all()->map(function ($cinema, $index) {
            return [
                'no' => $index + 1,
                'nama' => $cinema->name,
                'lokasi' => $cinema->location,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'Lokasi'];
    }
}
