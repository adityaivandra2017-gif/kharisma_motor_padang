<?php

namespace App\Services\Pimpinan;

use Carbon\CarbonInterface;

readonly class PimpinanStockAlert
{
    public const STATUS_STOK_HABIS = 'Stok Habis';

    public const STATUS_PERLU_RESTOCK = 'Perlu Restock';

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
        return $this->namaOnderdil;
    }

    public function isStokHabis(): bool
    {
        return $this->status === self::STATUS_STOK_HABIS;
    }

    public function statusBadgeClasses(): string
    {
        return $this->isStokHabis()
            ? 'bg-red-100 text-red-800 ring-red-200'
            : 'bg-amber-100 text-amber-900 ring-amber-200';
    }

    public function statusDotClass(): string
    {
        return $this->isStokHabis()
            ? 'bg-red-500'
            : 'bg-amber-500';
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
