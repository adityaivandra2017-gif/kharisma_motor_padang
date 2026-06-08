import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

function initPimpinanDoughnut() {
    const canvas = document.getElementById('pimpinanStockDoughnut');
    if (!canvas) return;

    let chartData;
    try {
        chartData = JSON.parse(canvas.dataset.chart ?? '{}');
    } catch {
        return;
    }

    const labels = chartData.labels ?? ['Aman', 'Menipis', 'Habis'];
    const values = chartData.values ?? [0, 0, 0];
    const percentages = chartData.percentages ?? [0, 0, 0];
    const total = values.reduce((sum, value) => sum + value, 0);

    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.9)',
                        'rgba(245, 158, 11, 0.9)',
                        'rgba(239, 68, 68, 0.9)',
                    ],
                    borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                    borderWidth: 3,
                    hoverOffset: 8,
                    cutout: '66%',
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 950,
                easing: 'easeOutCubic',
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.92)',
                    titleColor: '#f8fafc',
                    bodyColor: '#f8fafc',
                    borderColor: 'rgba(251, 113, 133, 0.35)',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        label(context) {
                            const idx = context.dataIndex;
                            return `${labels[idx]}: ${values[idx]} item (${percentages[idx]}%)`;
                        },
                        footer() {
                            return `Total: ${total} item`;
                        },
                    },
                },
            },
        },
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPimpinanDoughnut);
} else {
    initPimpinanDoughnut();
}
