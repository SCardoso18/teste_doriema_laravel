<?php

namespace App\Exports;

use App\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\withEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;

class DeliveryRequestsExport implements FromCollection, WithHeadings, withEvents, ShouldAutoSize
{
    public function collection()
    {
        $requests = DB::select(" SELECT client.name, client.email, client.telephone,
        request.id, request.status, request.note, request.payment_method, request.total_of_request,
        request.created_at, request.time, request.updated_at FROM client, request WHERE client.id = request.client_id
        AND request.status = 'EN' ORDER BY request.updated_at DESC ");

        $collection = collect($requests);

        return $collection;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = 'A1:W1'; // ALL HEADERS
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

    public function headings(): array
    {
        return [
            'Cliente',
            'Email',
            'Telefone',
            'Pedido Refer',
            'Status',
            'nota',
            'Forma de pagamento',
            'Total do pedido',
            'Criado em',
            'Hora',
            'Actualizado em'
        ];
    }

}
