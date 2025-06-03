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

class InstrumentIndicesImport implements ToModel, WithMapping, WithStartRow, WithBatchInserts,
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
            'code' => $row[1],
            'dev' => $row[0],
            'service' => $row[2] ?? "",
            'area' => $row[14] ?? "",
            'pid_drawing' => $row[3] ?? "",
            'device_description' => $row[4] ?? "",
            'manufacturer' => $row[5] ?? "",
            'model' => $row[6] ?? "",
            'range_unit' => $row[7] ?? "",
            'outsignal' => $row[8] ?? "",
            'supply' => $row[9] ?? "",
            'loop_drwg' => $row[10] ?? "",
            'spec_no' => $row[11] ?? "",
            'po_mr_no' => $row[12] ?? "",
            'remark' => $row[13] ?? "",
        ];
    }


    public function model(array $row)
    {
        $service = Service::where('name', $row['service'])->first()->id ?? null;
        $area = Area::where('name', $row['area'])->first()->id ?? null;
        if($service && $area){
            return new InstrumentIndex([
                'code' => $row['code'],
                'dev' => $row['dev'],
                'service_id' => $service,
                'area_id' => $area,
                'pid_drawing' => $row['pid_drawing'] ?? "",
                'device_description' => $row['device_description'] ?? "",
                'manufacturer' => $row['manufacturer'] ?? "",
                'model' => $row['model'] ?? "",
                'range_unit' => $row['range_unit'] ?? "",
                'outsignal' => $row['outsignal'] ?? "",
                'supply' => $row['supply'] ?? "",
                'loop_drwg' => $row['loop_drwg'] ?? "",
                'spec_no' => $row['spec_no'] ?? "",
                'po_mr_no' => $row['po_mr_no'] ?? "",
                'remark' => $row['remark'] ?? "",
                'is_finalize' => 1
            ]);
        }

        return null;
    }

//    public static function afterImport(AfterImport $event){
//        Log::info('AfterImport event fired');
//        $importInstance = $event->getConcernable();
//        Projects::whereNotIn('sap_code', $importInstance->uniqueIdentifiers)->delete();
//    }
    public function uniqueBy()
    {
        // TODO: Implement uniqueBy() method.
    }
}
