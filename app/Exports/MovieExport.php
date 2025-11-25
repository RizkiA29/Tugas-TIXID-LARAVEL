<?php

namespace App\Exports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;


class MovieExport implements FromCollection, Withheadings, Withmapping
{
    private $key = 0;
    public function collection()
    {
        return Movie::orderBy ('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            "No",
            "Judul",
            "Durasi",
            "Genre",
            "Sustradara",
            "Usia Minimal",
            "Poster",
            "Sipnopsis",
            "Status Aktif",
        ];
    }
    public function map($movie): array
    {
     return  [
        ++$this->key,
         $movie->title,
         Carbon::parse($movie->duration)->format("H") . "jam" . carbon::parse($movie->duration)->format("i") . "menit",
            $movie->genre,
            $movie->director,
            $movie->age_rating . "+",
            asset ("storage") . "/" . $movie->poster,
            $movie->description,
            $movie->actived == 1 ? 'Aktif' : 'Non-Aktif',
        ];
    }
}
