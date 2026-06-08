<?php

return [
    'groups' => [
        [
            'id' => 'analisis-persediaan',
            'label' => 'Analisis Persediaan',
            'icon' => 'calculator',
            'children' => [
                ['label' => 'Analisis EOQ', 'route' => 'pimpinan.analisis-persediaan.eoq'],
                ['label' => 'Analisis ROP', 'route' => 'pimpinan.analisis-persediaan.rop'],
            ],
        ],
    ],
    'laporan' => [
        'id' => 'laporan',
        'label' => 'Laporan',
        'icon' => 'document',
        'children' => [
            ['label' => 'Persediaan Stok', 'route' => 'pimpinan.laporan.stok'],
            ['label' => 'Barang Masuk', 'route' => 'pimpinan.laporan.pembelian'],
            ['label' => 'Barang Keluar', 'route' => 'pimpinan.laporan.keluar'],
            ['label' => 'Analisis EOQ & ROP', 'route' => 'pimpinan.laporan.eoq-rop'],
        ],
    ],
    'items' => [
        [
            'label' => 'Dashboard',
            'route' => 'pimpinan.dashboard',
            'icon' => 'chart',
        ],
    ],
];
