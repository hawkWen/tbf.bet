<?php

namespace App\Exports;

use App\Models\CustomerDeposit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerDepositExport implements FromQuery,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'Id',
            'name',
            'email',
            'createdAt',
            'updatedAt',
        ];
    }
    public function query()
    {
        return CustomerDeposit::query();
        /*you can use condition in query to get required result
         return Bulk::query()->whereRaw('id > 5');*/
    }
    public function map($customer_deposit): array
    {
        return [
            $customer_deposit->id,
            $customer_deposit->name,
            $customer_deposit->username,
            Date::dateTimeToExcel($customer_deposit->created_at),
            Date::dateTimeToExcel($customer_deposit->updated_at),
        ];
    }
}
