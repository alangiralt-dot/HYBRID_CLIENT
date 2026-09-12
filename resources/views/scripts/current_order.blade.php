<script>
    const orderLines = @json($transformed_items);
    
    document.addEventListener('DOMContentLoaded', function () {
        const emptyCartMessage = document.getElementById('empty-cart-message');
        if (emptyCartMessage) {
            const alternativeMessages = [
                "Aquest carretó buit fa un xic de pena.",
                "De mica en mica s'omple la pica.",
                "Això sembla un taller un divendres a la tarda!",
                "Amb el carretó buit no es pot fer feina.",
                "Aquest carretó no pesa gaire, oi?"
            ];
            const randomIndex = Math.floor(Math.random() * alternativeMessages.length);
            emptyCartMessage.textContent = alternativeMessages[randomIndex];
        }
        
        const btnConfirmOrder = document.getElementById('btn-confirm-order');
        const token = sessionStorage.getItem('access_token');
        if (token && btnConfirmOrder) {
            btnConfirmOrder.classList.remove('hidden');
        } else {
            return
        }

        btnConfirmOrder.addEventListener('click', async function () {
            // 1. Evitem que es cliqui dues vegades seguides desactivant el botó
            btnConfirmOrder.disabled = true;
            btnConfirmOrder.textContent = "PROCESSANT...";

            try {
                // 2. Llançem la petició POST asíncrona directa a l'API central
                const apiBase = "{{ config('services.api_serra.url') }}";
                const response = await fetch(`${apiBase}/api/orders`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        "order_lines": orderLines
                    })
                });

                const data = await response.json();

                // 3. Punt 12 i 13 del teu full de ruta: si l'API respon amb èxit, fem el PRG del client
                if (response.ok && data.status === 'success') {
                    // Forçem la redirecció GET neta cap al llistat del servidor client
                    window.location.href = "{{ route('orders.showOrders') }}";
                } else {
                    // alert(data.message || "Error en processar la comanda amb la serradora central.");
                    showSystemAlert(data.message);
                    btnConfirmOrder.disabled = false;
                    btnConfirmOrder.textContent = "CONFIRMAR COMANDA";
                }

            } catch (error) {
                showSystemAlert(error.message);
                btnConfirmOrder.disabled = false;
                btnConfirmOrder.textContent = "CONFIRMAR COMANDA";
            }
        });
    });

    function updateInvoiceSession(productId, step, currentValue) {
        const currentVal = parseInt(currentValue) || 0;
        
        // Protecció local: Si l'usuari intenta restar i ja som al mínim (el pack), bloquegem la petició asíncrona
        if (step < 0 && currentVal <= Math.abs(step)) {
            return; 
        }

        // 1. Injectem el token CSRF de validació de Laravel
        const csrfToken = "{{ csrf_token() }}";

        // 2. Preparem la petició POST cap a la teva ruta oficial d'afegir
        const xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('orders.updateQuantity') }}", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

        // 3. Un cop la sessió s'ha modificat amb èxit pel controlador, recarreguem la URL
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                window.location.href = "{{ url('/comandes/current') }}";
            }
        };

        // 4. ENVIEM EL STEP DIRECTAMENT: Laravel farà el `+= $step` exacte a la sessió
        xhr.send(`product_id=${productId}&quantity=${step}`);
    }
    function removeInvoiceItem(productId) {
        const csrfToken = "{{ csrf_token() }}";

        const xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('orders.remove') }}", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Quan la sessió s'ha buidat, cridem immediatament la URL comandes/current
                window.location.href = "{{ url('/comandes/current') }}";
            }
        };

        xhr.send(`product_id=${productId}`);
    }
</script>
