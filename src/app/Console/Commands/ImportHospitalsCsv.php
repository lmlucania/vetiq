<?php

namespace App\Console\Commands;

use App\Domains\Location\Enum\Prefecture;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use App\Models\Hospital;

class ImportHospitalsCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-hospitals-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CSVから動物病院をインポート';

    private const BATCH_SIZE = 2000;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = storage_path('app/import/hospitals.csv');

        if (!file_exists($path)) {
            $this->error('CSVファイルが見つかりません。');
            return Command::FAILURE;
        }

        $csv = Reader::createFromPath($path, 'r');

        $records = $csv->getRecords();

        $batch = [];

        foreach ($records as $index => $record)
        {
            $batch[] = [
                'name' => $record[1],
                'post_code' => is_numeric($record[2]) ? $record[2] : '0000000', // 郵便番号が数字ではない場合もある
                'prefecture' => Prefecture::findFromAddress($record[3]) ?? 0,
                'address1' => $record[3],
                'phone' => $record[4],
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) === self::BATCH_SIZE) {
                $this->insertHospital($batch);
                $batch = [];
                $this->info(($index + 1) . '件目までインポート完了');
            }
        }

        // 残りがあればインポート
        if (!empty($batch)) {
            $this->insertHospital($batch);
            $this->info('残りのインポート完了');
        }

        $this->info($csv->count() . '件のインポート完了');
        return Command::SUCCESS;
    }

    private function insertHospital(array $data): bool
    {
        return DB::table('hospitals')->insert($data);
    }
}
