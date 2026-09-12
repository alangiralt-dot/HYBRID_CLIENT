<script>
    const API_SERRA_BASE_URL = "{{ config('services.api_serra.url') }}";

    document.addEventListener('DOMContentLoaded', function () {
        fetchAndRenderOrders();
    });

    async function fetchAndRenderOrders() {
        const ordersContainer = document.getElementById('orders-list');
        if (!ordersContainer) return;

        const token = sessionStorage.getItem('access_token');
        if (!token) {
            showSystemAlert("Sessió no vàlida o expirada. Si us plau, torna a iniciar sessió per consultar les teves comandes.");
            return;
        }
        
        const apiUrl = `${API_SERRA_BASE_URL}/api/orders`;
        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) {
            try {
                const errorData = await response.json();
                showSystemAlert(errorData.message || response.status);
                return;
            } catch (error) {
                showSystemAlert(error.message);
                return;
            }
        }

        const confirmedOrders = await response.json();
        if (confirmedOrders.length === 0) {
            ordersContainer.innerHTML = `
                <div class="text-center py-8 text-gray-500 font-normal text-[13px]">
                    Encara no has confirmat cap comanda.
                </div>
            `;
            return;
        }

        try {
            const mainFragment = document.createDocumentFragment();
            
            const rowTemplate = document.getElementById('orders-list-item');

            confirmedOrders.forEach(order => {
                const clone = rowTemplate.content.cloneNode(true);

                const orderLink = clone.querySelector('.order-link');
                orderLink.href = `{{ url('/comandes') }}/${order.id}`;

                clone.querySelector('.order-code').textContent = order.code;
                clone.querySelector('.order-status').textContent = order.status;
                clone.querySelector('.order-date').textContent = order.date;
                clone.querySelector('.order-availability').textContent = order.order_availability;

                const totalFormatted = order.total_amount.toLocaleString('ca-ES', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                clone.querySelector('.order-total').textContent = `${totalFormatted} €`;

                mainFragment.appendChild(clone);
            });

            ordersContainer.innerHTML = '';
            ordersContainer.appendChild(mainFragment);

        } catch (error) {
            showSystemAlert(error.message);
            return;
        }
    }
</script>