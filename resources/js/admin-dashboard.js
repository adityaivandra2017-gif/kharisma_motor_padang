import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

function initStockChart() {
    const canvas = document.getElementById('dashboardStockChart');
    if (!canvas) {
        return;
    }

    let chartData;
    try {
        chartData = JSON.parse(canvas.dataset.chart ?? '{}');
    } catch {
        return;
    }

    const values = chartData.values ?? [0, 0, 0];
    const labels = chartData.labels ?? ['Aman', 'Menipis', 'Habis'];
    const maxValue = Math.max(...values, 0);

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Jumlah Onderdil',
                    data: values,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(239, 68, 68, 0.85)',
                    ],
                    borderColor: [
                        'rgb(5, 150, 105)',
                        'rgb(217, 119, 6)',
                        'rgb(220, 38, 38)',
                    ],
                    borderWidth: 1,
                    borderRadius: 12,
                    borderSkipped: false,
                    maxBarThickness: 56,
                    hoverBorderWidth: 1.5,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 850,
                easing: 'easeOutCubic',
            },
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.92)',
                    titleColor: '#f8fafc',
                    bodyColor: '#f8fafc',
                    borderColor: 'rgba(251, 113, 133, 0.4)',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        label(context) {
                            return `Jumlah: ${context.parsed.y} onderdil`;
                        },
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#475569',
                        font: { size: 12, weight: '600' },
                    },
                    border: { color: 'rgba(148, 163, 184, 0.15)' },
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: maxValue > 0 ? maxValue + 1 : 1,
                    ticks: {
                        precision: 0,
                        stepSize: 1,
                        color: '#64748b',
                        font: { size: 11 },
                    },
                    grid: {
                        color: 'rgba(148, 163, 184, 0.22)',
                        drawBorder: false,
                    },
                    border: { display: false },
                },
            },
        },
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initStockChart);
} else {
    initStockChart();
}
