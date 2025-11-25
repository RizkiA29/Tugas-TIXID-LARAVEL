<?php
namespace App\Exports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ScheduleExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Schedule::with(['cinema', 'movie'])->get()->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'bioskop' => $item->cinema->name ?? '',
                'film' => $item->movie->title ?? '',
                'jam_tayang' => is_array($item->hours) ? implode(', ', $item->hours) : $item->hours,
                'harga' => $item->price ? 'Rp. ' . number_format($item->price, 0, ',', '.') : '',
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama Bioskop', 'Judul Film', 'Jam Tayang', 'Harga'];
    }
}
