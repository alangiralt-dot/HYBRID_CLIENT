<!-- [3A] VARIABLE NO DEFINIDA: En el bulce @foreach proposat, la IA utilitza $orders que el controlador showOrders() de la classe OrderContoller no envia a la vista perquè no pot fer la petició ja que el Bearer Token resideix al navegador. -->
<!-- Dins del bucle @foreach($orders as $order) a orders.blade.php -->
<div class="col-span-2 text-sm text-gray-700">
    
    <!-- [3B] CONSULTAR ROL: La IA proposa encertadament desar l'estat d'administrador en la sessió que Laravel crea automàticament quan rep una petició del navegador. -->
    @if(session('is_admin') == 1)
        
        <!-- Menú desplegable per a l'administrador -->
        <!-- [3C] EXPOSICIÓ I ACOPLAMENT: L'esdeveniment inline 'onchange' obliga a exposar la funció asíncrona 'updateOrderStatus' a l'abast global del navegador (window), obrint problemes de seguretat des de la consola de Chrome i obliga a modificar codi en diferents llocs quan es modifica la signatura de la funció. -->
        <select onchange="updateOrderStatus('{{ $order->id }}', this)" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-red-500 focus:border-red-500 block w-full p-2.5 transition">
            
            <!-- [3D] BUCLE NIAT: Si l'administrador carrega 50 comandes a la pantalla i hi ha tres estats, el servidor web haurà de fer 150 iteracions  redactant l'HTML. -->
            @foreach($statuses as $status)
                
                <!-- [3E-IA] OPERADOR TERNARY MANUAL: En lloc d'utilitzar el codi natiu assignant un valor a <select> directament, utilitzar el id de la taula statuses com a valor de l'atribut value de <option> obliga a escriure un operador ternary a fi d'assignar-li l'atribut selected -->
                <option value="{{ $status['id'] }}" {{ $order->status_id == $status['id'] ? 'selected' : '' }}>
                    {{ $status['status'] }}
                </option>
            @endforeach
        </select>
    @else
        <!-- Text pla per a l'usuari normal -->
        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
            {{ $order->status }}
        </span>
    @endif
</div>
