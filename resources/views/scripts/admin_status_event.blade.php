ordersContainer.querySelectorAll('.order-status-select').forEach(select => {
    select.addEventListener('change', async function () {
        this.disabled = true;

        const orderId = this.getAttribute('data-order-id');
        const targetStatusId = this.options[this.selectedIndex].getAttribute('data-status-id');

        try {
            const patchRes = await fetch("{{ config('services.api_serra.url') }}/api/orders/" + orderId + "/status", {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ "status_id": parseInt(targetStatusId) })
            })

            const resData = await patchRes.json();
            if (patchRes.ok && resData.status === 'success') {
                const orderRow = document.getElementById(`row-${orderId}`);
                const orderRowContent = orderRow.getElementsByClassName('order-row-content')[0];
                const bannerRowContent = orderRow.getElementsByClassName('banner-row-content')[0];

                orderRowContent.classList.add('hidden');
                bannerRowContent.innerHTML = `
                    <div class="bg-green-50 border border-green-200 py-4 text-sm text-green-700 font-medium shadow-sm flex justify-center items-center w-full rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>L'estat de la comanda s'ha actualitzat correctament a la base de dades</span>
                        </div>
                    </div>
                `;

                setTimeout(() => {
                    bannerRowContent.innerHTML = "";
                    orderRowContent.classList.remove('hidden');
                }, 4000);
            } else {
                showSystemAlert(resData.message);
            }
        } catch (err) {
            showSystemAlert(err.message);
        } finally {
            select.disabled = false;
        }
    });
});
