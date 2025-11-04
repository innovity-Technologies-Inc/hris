<?php

namespace App\Imports;

use App\Models\Bank;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\EmployeeBankAccount;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EmployeeBankAccountDetailsImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Skip header
        $collection->skip(1)->each(function ($row) {

            $row = $row->map(fn($cell) => trim((string) $cell));

            // Skip if no employee_id or emp_type
            if (empty($row[0]) && empty($row[1])) return;

            EmployeeBankAccount::create([
                'employee_id' => $this->getEmployeeId(Employee::class, $row[0]),
                'bank_id' => $this->getId(Bank::class, $row[1]),
                'branch_id' => $this->getId(Branch::class, $row[2]),
                'account_holder_name' => $row[3] ?? null,
                'account_number' => $row[4] ?? null,
                'status' => $row[5] ?? null,
                'remarks' => $row[6] ?? null,
            ]);
        });
    }

    //Match Full Value
    private function getEmployeeId($model, $employee_id)
    {
        if (empty($employee_id)) return null;
        $record = $model::where('applicant_id', $employee_id)->first();
        return $record ? $record->id : null;
    }

    //Match Partially matched Value

    private function getId($model, $name)
    {
        if (empty($name)) return null;
        $name = trim(strtolower($name));
        $record = $model::whereRaw('LOWER(name) LIKE ?', ["%{$name}%"])->first();
        return $record ? $record->id : null;
    }


}
