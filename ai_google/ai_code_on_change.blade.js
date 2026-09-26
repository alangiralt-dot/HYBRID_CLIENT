function updateOrderStatus(orderId, selectElement) {
    
    const newStatusId = selectElement.value;
    const token = sessionStorage.getItem('bearer_token'); // Recuperem el teu token d'administrador
    
    // Netejem possibles alertes del nostre component modular anterior
    const errorBanner = document.getElementById('error-banner');
    const successBanner = document.getElementById('success-banner');
    if(errorBanner) errorBanner.classList.add('hidden');
    if(successBanner) successBanner.classList.add('hidden');

    // Desactivem temporalment el select durant la crida (estil catàleg)
    selectElement.disabled = true;

    fetch(`http://localhost:8000/api/orders/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status_id: parseInt(newStatusId)
        })
    })
    // PROMESES ENCADENADES: La IA controla el flux mitjançant promeses encadenades. Actualment, l'ús de .then() no es considera una bona pràctica perquè genera codi més difícil de llegir, mantenir i depurar que el codi escrit utilitzant async/await i blocs try...catch
    .then(response => response.json())
    .then(data => {
        // CODI REDUNDANT: La IA no ha escrit «selectElement.disabled = false;» en un bloc finally.
        selectElement.disabled = false; // Tornem a activar el select
        
        if (data.status === 'success') {
            // INCOMPLIMENT D'UN REQUERIMENT: La IA es limita a utilitzar el sistema d'avisos generals del sistema, en lloc d'utilitzar la fila modificada per a mostrar un missatge d'èxit durant uns segons.
            // FUNCIÓ NO DEFINIDA: La IA invoca una funció showSystemSuccess() com si ja estigués definida en el codi font, segurament volent invocar showSuccessMessage() que sí està definida. Ara bé, coneix perfectament el id d'un <p> del banner.
            if(typeof showSystemSuccess === 'function') {
                showSystemSuccess('Estat de la comanda actualitzat correctament.');
            } else {
                const successMsg = document.getElementById('success-message');
                if(successMsg) {
                    successMsg.textContent = 'Estat de la comanda actualitzat correctament.';
                    successBanner.classList.remove('hidden');
                }
            }
        } else {
            // Si l'API respon amb algun error de validació
            showSystemAlert(data.message || 'No s\'ha pogut actualitzar l\'estat.');
        }
    })
    .catch(error => {
        // CODI REDUNDANT: La IA no ha escrit «selectElement.disabled = false;» en un bloc finally.
        selectElement.disabled = false;
        showSystemAlert('S\'ha produït un error de connexió amb el servidor de l\'API.');
    });
}
