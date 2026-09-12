<script>
    const orderId = {{ $id }};

    document.addEventListener('DOMContentLoaded', async function () {
        const token = sessionStorage.getItem('access_token');

        if (!token) {
            showSystemAlert("Sessió no vàlida o expirada. Si us plau, torna a iniciar sessió.");
            return;
        }

        // Adreça base de l'API central configurada al .env
        const apiBase = "{{ config('services.api_serra.url') }}";

        try {
            // 3. Llancem la petició GET asíncrona cap a la serradora
            const response = await fetch(`${apiBase}/api/orders/${orderId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            // 4. Clàusula de salvaguarda per a errors de l'API (El teu disseny de fre de mà)
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

            const orderData = await response.json();
            
            // 1. Injectem les dades generals de traçabilitat a la capçalera
            document.getElementById('general-code').textContent = orderData.code;
            document.getElementById('general-status').textContent = orderData.status;
            document.getElementById('general-date').textContent = orderData.date;

            // 2. Formatem i injectem els imports econòmics congelats amb l'estat localitzat
            const formattedBasis = orderData.base_imposable.toLocaleString('ca-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('general-taxable-basis').textContent = `${formattedBasis} €`;

            const formattedTax = orderData.iva.toLocaleString('ca-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('general-tax').textContent = `${formattedTax} €`;

            const formattedTotal = orderData.total_amount.toLocaleString('ca-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('general-total').textContent = `${formattedTotal} €`;

            // 5. Localitzem el contenidor general i preparem el fragment a la RAM
            const orderLinesContainer = document.getElementById('confirmed-order-lines');
            if (!orderLinesContainer) return;

            const mainFragment = document.createDocumentFragment();
            const rowTemplate = document.getElementById('confirmed-line-item');

            // 6. Recorrem els llistons de fusta congelats de la comanda
            orderData.order_lines.forEach(line => {
                const clone = rowTemplate.content.cloneNode(true);

                // Injectem el Nom i la Referència de forma segura
                clone.querySelector('.line_name').textContent = line.name;
                clone.querySelector('.line-reference').textContent = line.reference;

                // LÒGICA DE LES MIDES EN JAVASCRIPT (Substitueix els comentaris de Blade)
                if (line.height === -1) {
                    clone.querySelector('.line-dimensions').textContent = `Ø ${line.width} × ${line.length}`;
                } else {
                    clone.querySelector('.line-dimensions').textContent = `${line.width} × ${line.height} × ${line.length}`;
                }

                // Injectem la Quantitat facturada
                clone.querySelector('.line-quantity').textContent = line.quantity;

                // Formatem els preus a l'estil català (1.234,56 €)
                const formattedPrice = line.sale_unit_price.toLocaleString('ca-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                clone.querySelector('.line-unit-price').textContent = `${formattedPrice} ${line.unit}`;


                const formattedSubtotal = line.subtotal.toLocaleString('ca-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                clone.querySelector('.line-subtotal').textContent = `${formattedSubtotal} €`;

                // Afegim la fila processada al paraigües del fragment
                mainFragment.appendChild(clone);
            });

            // 7. Estampem tot el bloc a la pantalla amb un sol impacte al DOM
            orderLinesContainer.innerHTML = '';
            orderLinesContainer.appendChild(mainFragment);

        } catch (error) {
            showSystemAlert(error.message);
            return;
        }
    });
</script>
