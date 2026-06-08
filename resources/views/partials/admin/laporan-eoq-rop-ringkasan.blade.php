@props(['ringkasan'])

<div class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
    <div class="laporan-stat-card">
        <p class="laporan-stat-card__label">Total Onderdil Dianalisis</p>
        <p class="laporan-stat-card__value">{{ number_format($ringkasan['total_onderdil'], 0, ',', '.') }}</p>
    </div>
    <div class="laporan-stat-card laporan-stat-card--aman">
        <p class="laporan-stat-card__label">Total Kondisi Aman</p>
        <p class="laporan-stat-card__value">{{ number_format($ringkasan['total_aman'], 0, ',', '.') }}</p>
    </div>
    <div class="laporan-stat-card laporan-stat-card--habis">
        <p class="laporan-stat-card__label">Total Perlu Restock</p>
        <p class="laporan-stat-card__value">{{ number_format($ringkasan['total_perlu_restock'], 0, ',', '.') }}</p>
    </div>
</div>
