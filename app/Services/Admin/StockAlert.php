<?php

namespace App\Services\Admin;

use Carbon\CarbonInterface;

readonly class StockAlert
{
    public function __construct(
        public int $ropId,
        public string $kodeOnderdil,
        public string $namaOnderdil,
        public int $currentStock,
        public int $hasilRop,
        public string $status,
        public CarbonInterface $occurredAt,
    ) {}

    public function onderdilLabel(): string
    {
        return "{$this->kodeOnderdil} — {$this->namaOnderdil}";
    }

    public function timeLabel(): string
    {
        $minutes = (int) $this->occurredAt->diffInMinutes(now());

        if ($minutes < 1) {
            return 'Baru saja';
        }

        if ($minutes < 60) {
            return "{$minutes} menit lalu";
        }

        if ($this->occurredAt->isToday()) {
            $hours = max(1, (int) $this->occurredAt->diffInHours(now()));

            return "{$hours} jam lalu";
        }

        if ($this->occurredAt->isYesterday()) {
            return 'Kemarin';
        }

        return $this->occurredAt->translatedFormat('d M Y');
    }
}
