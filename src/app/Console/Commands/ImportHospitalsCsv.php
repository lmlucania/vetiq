<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Location\Enum\Prefecture;
use App\Domains\Schedule\Enum\DayOfWeek;
use App\Domains\Schedule\Enum\TimePeriod;
use App\Models\BusinessHour;
use App\Models\Hospital;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

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

        if (! file_exists($path)) {
            $this->error('CSVファイルが見つかりません。');
            return Command::FAILURE;
        }

        $csv = Reader::createFromPath($path, 'r');

        $rows = $csv->getRecords();

        $batch = [];

        foreach ($rows as $index => $row) {
            //            $batch[] = [
            //                'name' => $row[1],
            //                'post_code' => is_numeric($row[2]) ? $row[2] : '0000000', // 郵便番号が数字ではない場合もある
            //                'prefecture' => Prefecture::findFromAddress($row[3]) ?? 0,
            //                'address1' => $row[3],
            //                'phone' => $row[4],
            //                'is_published' => true,
            //                'created_at' => now(),
            //                'updated_at' => now(),
            //            ];
            $hospital = Hospital::create([
                'name'         => $row[1],
                'post_code'    => is_numeric($row[2]) ? $row[2] : '0000000', // 郵便番号が数字ではない場合もある
                'prefecture'   => Prefecture::findFromAddress($row[3]) ?? 0,
                'address1'     => $row[3],
                'phone'        => $row[4],
                'is_published' => true,
            ]);

            for ($i = 6; $i < count($row); $i += 9) {
                $csvToEnum = [
                    0 => DayOfWeek::MONDAY,    // 月
                    1 => DayOfWeek::TUESDAY,   // 火
                    2 => DayOfWeek::WEDNESDAY, // 水
                    3 => DayOfWeek::THURSDAY,  // 木
                    4 => DayOfWeek::FRIDAY,    // 金
                    5 => DayOfWeek::SATURDAY,  // 土
                    6 => DayOfWeek::SUNDAY,    // 日
                ];

                $timeRange = trim($row[$i]);

                if (empty($timeRange)) {
                    continue;
                }

                [$start, $end] = array_map('trim', explode('~', $timeRange));

                // 曜日ごとの営業フラグ (次の8列)
                $weekFlags = array_slice($row, $i + 1, 7);

                foreach ($weekFlags as $dayOfWeek => $flag) {
                    $timePeriod = $this->autoTimePeriod($start);

                    if ($flag !== '●' && $flag !== '☆') {
                        continue;
                    }

                    if (BusinessHour::where('hospital_id', $hospital->id)->where('day_of_week', $csvToEnum[$dayOfWeek])->where('time_period', $timePeriod)->exists()) {
                        $timePeriod = TimePeriod::NIGHT;
                    }

                    BusinessHour::create([
                        'hospital_id' => $hospital->id,
                        'day_of_week' => $csvToEnum[$dayOfWeek],
                        'start_time'  => $start,
                        'end_time'    => $end,
                        'time_period' => $timePeriod,
                    ]);
                }
            }

            if (($index + 1) % 1000 == 0) {
                $this->info(($index + 1) . '件目までインポート完了');
            }
        }

        $this->info($csv->count() . '件のインポート完了');
        return Command::SUCCESS;
    }

    private function insertHospital(array $data): bool
    {
        return DB::table('hospitals')->insert($data);
    }

    private function autoTimePeriod(string $start): TimePeriod
    {
        $hour = (int) Carbon::parse($start)->format('H');

        if ($hour < 12) {
            return TimePeriod::AM;
        }

        return TimePeriod::PM;
    }
}
