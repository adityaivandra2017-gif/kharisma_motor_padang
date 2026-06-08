/**
 * Perbarui label waktu notifikasi (relatif) tanpa refresh halaman penuh.
 */
function formatNotificationTime(isoString) {
    const occurred = new Date(isoString);
    if (Number.isNaN(occurred.getTime())) {
        return '';
    }

    const now = new Date();
    const diffMs = now.getTime() - occurred.getTime();
    const minutes = Math.floor(diffMs / 60_000);

    if (minutes < 1) {
        return 'Baru saja';
    }

    if (minutes < 60) {
        return `${minutes} menit lalu`;
    }

    const hours = Math.floor(diffMs / 3_600_000);
    const isToday = occurred.toDateString() === now.toDateString();

    if (isToday) {
        return `${hours} jam lalu`;
    }

    const yesterday = new Date(now);
    yesterday.setDate(yesterday.getDate() - 1);

    if (occurred.toDateString() === yesterday.toDateString()) {
        return 'Kemarin';
    }

    return occurred.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
}

function updateNotificationTimes() {
    document.querySelectorAll('[data-notification-time]').forEach((element) => {
        const iso = element.getAttribute('data-notification-time');
        if (!iso) {
            return;
        }

        element.textContent = formatNotificationTime(iso);
    });
}

function initNotificationTimeUpdater() {
    const hasAdmin = document.getElementById('adminNotificationRoot');
    const hasPimpinan = document.getElementById('pimpinanNotificationRoot');

    if (!hasAdmin && !hasPimpinan) {
        return;
    }

    updateNotificationTimes();
    window.setInterval(updateNotificationTimes, 30_000);
}

document.addEventListener('DOMContentLoaded', initNotificationTimeUpdater);
