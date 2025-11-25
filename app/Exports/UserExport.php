<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::orderBy('role')->get()->map(function ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'joined_at' => $user->created_at->format('d-m-Y'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama', 'Email', 'Role', 'Tanggal Bergabung'];
    }
}
