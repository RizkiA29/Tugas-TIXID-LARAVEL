<?php
namespace App\Exports;

use App\Models\Promo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PromoExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Promo::all()->map(function ($promo) {
            return [
                'title' => $promo->promo_code,
                'type' => $promo->type,
                'total' => $promo->type == 'percent' ? $promo->discount . '%' : 'Rp. ' . number_format($promo->discount, 0, ',', '.'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Judul', 'Tipe', 'Total Potongan'];
    }
}
