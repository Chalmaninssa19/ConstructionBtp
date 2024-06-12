<?php

namespace App\Exports;

use App\Models\gestion_billet\VVentePack;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VVentePackExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return VVentePack::all();
    }

    public function headings(): array
    {
        return ["ID", "Nom", "Prix unitaire", "Quantite", "Montant"];
    }
}
