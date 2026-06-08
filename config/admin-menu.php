<?php

return [
    'groups' => [
        [
            'id' => 'master-data',
            'label' => 'Master Data',
            'icon' => 'cube',
            'children' => [
                ['label' => 'Data Onderdil', 'route' => 'admin.master-data.onderdil'],
                ['label' => 'Data Supplier', 'route' => 'admin.master-data.supplier'],
            ],
        ],
        [
            'id' => 'transaksi',
            'label' => 'Transaksi',
            'icon' => 'arrows',
            'children' => [
                ['label' => 'Barang Masuk', 'route' => 'admin.transaksi.masuk'],
                ['label' => 'Barang Keluar', 'route' => 'admin.transaksi.keluar'],
            ],
        ],
        [
            'id' => 'perhitungan',
            'label' => 'Perhitungan',
            'icon' => 'calculator',
            'children' => [
                ['label' => 'Economic Order Quantity (EOQ)', 'route' => 'admin.perhitungan.eoq'],
                ['label' => 'Reorder Point (ROP)', 'route' => 'admin.perhitungan.rop'],
            ],
        ],
    ],
    'laporan' => [
        'id' => 'laporan',
        'label' => 'Laporan',
        'icon' => 'document',
        'children' => [
            ['label' => 'Laporan Persediaan Stok', 'route' => 'admin.laporan.stok'],
            ['label' => 'Laporan Barang Masuk', 'route' => 'admin.laporan.masuk'],
            ['label' => 'Laporan Barang Keluar', 'route' => 'admin.laporan.keluar'],
            ['label' => 'Laporan Analisis EOQ & ROP', 'route' => 'admin.laporan.eoq-rop'],
        ],
    ],
    'items' => [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'chart',
        ],
    ],
];
