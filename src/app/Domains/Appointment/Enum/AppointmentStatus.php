<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Enum;

enum AppointmentStatus: int
{
    case Reserved          = 1; // 予約済み：患者が予約を完了した状態
    case CheckedIn         = 2; // 受付済み：受付を通って病院に到着した
    case Completed         = 3; // 診察完了：診察が完了し、会計などに進む状態
    case Cancelled         = 4; // キャンセル：患者からの申し出で予約を取り消した
    case NoShow            = 5; // 無断キャンセル：連絡なしで来院せず、予約時刻を過ぎた
    case CancelledByClinic = 6; // 病院側キャンセル：医師の都合などで取り消した

    public function label(): string
    {
        return match ($this) {
            self::Reserved          => '予約済み',
            self::CheckedIn         => '受付済み',
            self::Completed         => '診察完了',
            self::Cancelled         => 'キャンセル',
            self::NoShow            => '無断キャンセル',
            self::CancelledByClinic => '病院側キャンセル',
        };
    }
}
