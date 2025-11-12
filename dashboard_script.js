document.addEventListener('DOMContentLoaded', function() {
    const actionButtons = document.querySelectorAll('.btn-action');
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.closest('.order-card').dataset.orderId;
            const newStatus = this.dataset.status;

            if (confirm(`Ubah status order #${orderId} menjadi '${newStatus}'?`)) {
                fetch('update_order_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id_pesanan=${orderId}&status_pesanan=${newStatus}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Jika statusnya 'dibayar', buka bill di tab baru
                        if (newStatus === 'dibayar') {
                            window.open(`generate_bill.php?id=${orderId}`, '_blank');
                        }
                        // Reload halaman utama untuk memperbarui tampilan
                        window.location.reload();
                    } else {
                        alert('Gagal memperbarui status.');
                    }
                });
            }
        });
    });
});