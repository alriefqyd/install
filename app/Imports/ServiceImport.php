<?php

namespace App\Imports;


use App\Models\Area;
use App\Models\InstrumentIndex;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Events\AfterImport;

class ServiceImport implements ToModel, WithMapping, WithStartRow, WithBatchInserts,
    WithUpserts, WithChunkReading, WithEvents
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use RemembersRowNumber;
    use Importable;
    use RegistersEventListeners;

    private $uniqueIdentifiers = [];
    public function batchSize(): int{
        return 500;
    }

    public function chunkSize(): int{
        return 500;
    }

    public function startRow(): int{
        return 2;
    }

    public function map($row): array{
        return [
            'code' => $row[0],
            'name' => $row[1],
        ];
    }


    public function model(array $row)
    {
        if($row['name']){
            return new Service([
                'code' => $row['code'],
                'name' => $row['name'],
            ]);
        } return null;
    }
    public function uniqueBy()
    {
        // TODO: Implement uniqueBy() method.
    }
}
