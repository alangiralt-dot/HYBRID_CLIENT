<script>
    // 1. Injecció dinàmica de la ID des de PHP cap a JavaScript
    const orderId = {{ $id }};

    document.addEventListener('DOMContentLoaded', function () {
        const token = sessionStorage.getItem('access_token');

        // 2. Filtre de seguretat immediat (clàusula de salvaguarda)
        if (!token) {
            showSystemAlert("Sessió no vàlida o expirada. Si us plau, torna a iniciar sessió.");
            return;
        }

        console.log("Comencem a carregar de forma asíncrona la comanda número:", orderId);
        
        // Aquí s'executarà el nostre fetch(GET) cap a la serradora...
    });
</script>
