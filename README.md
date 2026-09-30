## 1 Descripció del Model IA Seleccionat

Per al desenvolupament assistit del client *frontend* d'aquesta pràctica, s'ha seleccionat l'ecosistema de **Google AI** (utilitzant els models de la família **Gemini** a través de google.com/ai).

### 1.1 Motius de la seva elecció:
* **Capacitat d'anàlisi de codi:** Destaca per la seva habilitat a l'hora de comprendre arquitectures Full Stack (Laravel-Frontend) i generar codi net, modular i adaptat als estàndards actuals de desenvolupament web.
* **Explicacions didàctiques:** Més enllà de proporcionar fragments de codi aïllats, ofereix desglossaments detallats del funcionament de les funcions, facilitant l'anàlisi crítica i l'aprenentatge durant el procés d'acoblament de l'API.
* **Context i coherència:** Manté un fil conductor sòlid durant la conversa, fet que permet refinar els formularis i les peticions de forma incremental sense perdre la consistència amb la base de dades existent.

### 1.2 Limitacions de la IA seleccionada en l'enviament de fitxers

Quan es treballa amb l'ecosistema de **Google AI** per a la transferència i anàlisi de codi font, s'han de tenir en compte les següents limitacions estructurals i tècniques de la plataforma, diferenciant clarament entre el comportament de la seva interfície web estàndard i entorns d'anàlisi dedicats:

#### 1.2.1 Canal de Càrrega de Fitxers Adjunts
* **Restricció de volum segons entorn:** La interfície web tradicional per a usuaris (https://google.com) limita la pujada a un màxim d'**1 fitxer per cada interacció (prompt)**. En canvi, els canals i entorns integrats d'auditoria de codi eliminen aquesta barrera, permetent la transferència multiparal·lela de fitxers.
* **Mida límit:** Admet fitxers individuals amb un pes màxim d'uns **100 MB**, processant el document de manera nativa des del servidor de la IA.
* **Formats de text i codi acceptats:** Permet la càrrega directa de fitxers amb extensions de programació estàndard (`.php`, `.js`, `.jsx`, `.html`, `.css`, `.json`) i documents de text o documentació (`.md`, `.txt`, `.pdf`). No admet arxius binaris ni executables per motius de seguretat.
* **Restricció de fitxers comprimits (`.zip`/`.rar`):** La interfície web no sempre descomprimeix correctament estructures complexes de directoris de manera nativa. Per tant, es requereix bolcar els fitxers de codi de forma individual.
* **Pèrdua d'estructura en formats de lectura (PDF):** La conversió de codi font a format PDF per a la seva anàlisi introdueix problemes de codificació de caràcters, trenca el sagnat (*indentació*) original i pot fragmentar línies de codi contínues. Per garantir una auditoria precisa, és imprescindible utilitzar formats de text pla.
* **Absència d'estructura de fitxers (Arbre de directoris):** La transferència aïllada del contingut dels fitxers omet completament la jerarquia i la ubicació real dels arxius dins de l'arquitectura del projecte. Per solucionar aquesta mancança, és necessari acompanyar el codi d'un mapa o arbre de directoris textual (generat amb comandes com `tree`) perquè la IA comprengui la distribució modular del framework.
* **Flux de treball seqüencial lent:** A causa de la restricció d'un sol fitxer per interacció a la interfície web, el procés de pujada esdevé totalment fragmentat. Si s'han modificat múltiples components (com rutes, controladors i formularis), l'usuari es veu obligat a realitzar cicles repetitius de selecció i tramesa per a cada arxiu, fet que trenca l'agilitat en l'auditoria conjunta de l'aplicació.

#### 1.2.2 Canal de Text Enganxat Directament
* **Capacitat d'entrada:** Permet introduir grans blocs de codi font com a text sense ocupar el canal de fitxers adjunts.
* **Límit de caràcters:** Està subjecte a la restricció d'un sol missatge d'entrada, la qual ronda els **32.000 tokens** (uns **100.000 a 130.000 caràcters**, que equivalen aproximadament a **100-130 KB** de text pur per prompt). Superar aquesta longitud bloqueja el processament de la consulta.
* **Límit de saturació per transferència massiva:** La tramesa de tot el codi font d'una aplicació de cop en un sol bloc de text pot saturar la memòria de treball immediata de la IA, provocant talls en les respostes o omissions de fitxers clau. Per tant, es requereix una transferència fragmentada i selectiva dels fitxers que contenen exclusivament la lògica de negoci.

#### 1.2.3 Gestió de Context (Avantatge General)
* **Finestra de context massiva:** Com a gran avantatge, compta amb una capacitat de memòria molt elevada (finestra de context). Això permet acumular múltiples interaccions al llarg de la sessió i analitzar fluxos de dades complets (Backend i Frontend simultàniament) sense pèrdua de coherència durant la sessió de treball.

### 1.3 Estratègia d'Optimització de Context per a la IA (AI-Context Pipeline)

Per garantir que la IA oferexi solucions 100% compatibles amb l'arquitectura real del projecte i evitar respostes genèriques o codi duplicat, vaig escriure el següent script que reuneix la part més significativa del codi font en un fitxer de text pla.

```php
<?php
declare(strict_types=1);

// 1. Specify the absolute path to your folder
$rootPath = 'C:\\xampp\\htdocs\\alan\\HYBRID_CLIENT\\';

if (!is_dir($rootPath) || !is_writable($rootPath)) {
    exit("Error: The directory $rootPath is not ready for writing.");
}

// 2. Specify the files within that folder
$dirIterator = new RecursiveDirectoryIterator($rootPath, RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new RecursiveIteratorIterator($dirIterator);

$filePaths = [];
foreach ($iterator as $file) {
    // If the route contains "/vendor/", we skip it automatically.
    // C:\xampp\htdocs\alan\API_SERRA\app\Http\Controllers (example route)
    if (str_contains($file->getPathname(), DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR) ||
        str_contains($file->getPathname(), DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'views')) {
        continue;
    }

    // We filter for only the relevant extensions (.php and .blade.php) and perhaps a few very specific files.
    // if (preg_match('/\.blade\.php$|\.php$|fustes_en_brut.json$/', $file->getFilename())) {
    if (preg_match('/\.blade\.php$|\.php$/', $file->getFilename())) {
        $filePaths[] = $file->getPathname();
    }
}

// 3. Specify where to save the result
$outputFile = 'C:\\xampp\\htdocs\\alan\\tasca_s5_02_docs\\ai_context.txt';

$outputDir = dirname($outputFile);
if (!is_dir($outputDir) || !is_writable($outputDir)) {
    exit("Error: The directory $outputDir is not ready for writing.");
}

$handle = fopen($outputFile, 'w');

fwrite($handle, "\n--- DIRECTORY TREE ---\n\n");
foreach ($filePaths as $filePath) {
  fwrite($handle, substr($filePath, strlen($rootPath)) . "\n");
}
fwrite($handle, "\n");

foreach ($filePaths as $filePath) {
    fwrite($handle, "--- FILE: " . substr($filePath, strlen($rootPath)) . " ---\n\n");
    fwrite($handle, file_get_contents($filePath) . "\n\n");
}

fclose($handle);
echo "Files merged into $outputFile" . PHP_EOL;
```
#### 1.3.1 Automatització del cicle de retroalimentació i eliminació de tasques tedioses:
L'script substitueix completament el procés manual, lent i repetitiu d'haver d'anar copiant i enganxant el codi fitxer per fitxer a cada modificació. Això millora l'experiència del programador i permet subministrar a la IA de manera instantània l'última versió de la part més significativa del codi font tantes vegades com calgui al llarg d'una mateixa sessió de treball.

#### 1.3.2 Filtratge intel·ligent i estalvi de tokens:
L'script inclou clàusules de salvaguarda automatitzades per ometre directoris natius pesants (com la carpeta `vendor/`) i fitxers temporals de cache o vistes compilades de l'entorn de producció local (`storage/framework/views/`). Això redueix dràsticament l'ús de tokens i evita saturar la finestra de context de la IA.

#### 1.3.3 Visualització global del projecte i prevenció de fitxers perduts:
Incloure l'arbre de directoris a dalt de tot dona a la IA un mapa complet i real de com estan col·locades totes les carpetes. D'aquesta manera, la IA entén a la primera on va cada fitxer i et dona codi adaptat al teu projecte, evitant que inventi rutes falses o demani desar arxius en llocs on no correspon.

---

## 2 Registre de les interaccions amb la IA

He registrat les interaccions amb la IA guardant la pàgina web del xat i editant-la per a eliminar tot allò que sobrava com ara scripts, menús, prompts, respostes, etc. Ara bé, he afegit links perquè puguis veure els documents que vaig enviar a la IA (fitxers i imatges) i algun script perquè puguis desplegar els prompts per a llegir tot el seu text, utilitzant les fletxes, les quals no estaran operatives fins que la pàgina s'hagi carregat completament.

### 2.1 Cicle de treball habitual

En aquest exemple, podràs veure les interaccions amb la IA més freqüents durant una sessió com ara:

* establir l'idioma de treball
* explicar el que hem de fer plegats
* donar context a la IA
* frenar els peus a la IA i deixar-li clar que soc jo qui marca el ritme de la sessió
* demanar explicacions sobre alguna cosa que ha proposat la IA o bé que desconec per pura ignorància
* demanar l'aprovació de la IA per a fer alguna cosa que li proposo
* informar de pauses llargues perquè, quan reprenguem la sessió, la IA faci espontàniament un petit resum del que estàvem fent abans de la pausa
* demanar que, utilitzant la numeració dels meus fitxers, la IA em digui quines línies de codi he de modificar o bé esborrar
* demanar que la IA revisi el que acabo de modificar
* debatre amb la IA sobre els commits que podem fer amb les modificacions fetes

**Obre ./docs/apartat_2_1/apartat_2_1.html amb Chrome fent-hi doble clic.**

### 2.2 Pensament metafòric

Quan la IA fa explicacions confuses plenes de contradiccions i de tecnicismes, que no saps si empra adequadament, sobre alguna cosa que desconeixes completament, és una bona idea demanar-li que t'ho expliqui utilitzant una metàfora, que pot arribar a ser molt complexa. En el següent exemple, podràs veure com la IA em va explicar CORS utilitzant-ne una.

**Obre ./docs/apartat_2_2/apartat_2_2.html amb Chrome fent-hi doble clic.**

---

## 3 Anàlisi del codi generat per la IA

En aquest apartat demostro que no crec a cegues el que diu o bé proposa la IA, sinó que llegeixo, entenc i opino.

### 3.1 Disbarats

En aquest exemple faig veure a la IA que ha dit una bajanada.

**Obre ./docs/apartat_3_1/apartat_3_1.html amb Chrome fent-hi doble clic.**

### 3.2 Coses inviables

En aquest exemple faig veure a la IA que m'ha proposat una cosa impossible de fer en aquell moment.

**Obre ./docs/apartat_3_2/apartat_3_2.html amb Chrome fent-hi doble clic.**

### 3.3 Millores

En aquest exemple milloro la proposta de la IA.

**Obre ./docs/apartat_3_3/apartat_3_3.html amb Chrome fent-hi doble clic.**

### 3.4 Comparació

Aquest és l'exemple més interessant perquè comparo el codi que em va proposar la IA per a fer una feature, que té unes vuitanta línies, amb el codi que realment vaig escriure per a fer-la. El primer fitxer (apartat_3_4.html) és un tros del xat i el segon (comparacio.html) és la comparació.

Per a navegar en comparacio.html has de tenir en compte els següents punts:
* Els comentaris escrits completament en majúscules formen part de la comparació i la resta formen part del codi.
* Els comentaris de la comparació estan formats per un títol en majúscules que sempre és visible i una descripció, la qual es fa visible en la part superior de la pantalla quan poses el cursor sobre el seu títol.
* Els títols en majúscules que són clicables perquè el cursor es transforma en un pointer (mà), en ser clicats fan un scroll automàtic cap a un altre títol en majúscules amb el qual formen una parella única, connectant així una línia del codi proposat per la IA i una del codi que realment vaig escriure. Fet el scroll, el títol que forma una parella amb el que has clicat està escrit en groc fins que facis un altre scroll automàtic.
* Cada títol només pot formar part d'una única parella, és a dir, cada títol és monògam perquè puguis preveure què passarà abans de clicar-lo.
* Les rutes relatives dels fitxers, que encapçalen el seu codi, estan escrites en groc i et diuen si el codi és de la IA (AI FILE) o meu (MY FILE).

**Obre ./docs/apartat_3_4/apartat_3_4.html amb Chrome fent-hi doble clic.**
**Obre ./docs/apartat_3_4/comparacio.html amb Chrome fent-hi doble clic.**

---

## 4 Descripció del procés de connexió entre el frontend i el backend
El sistema s'ha estructurat sota una arquitectura de comunicació híbrida i desacoblada dividida en dues capes clarament diferenciades: l'aplicació web client (frontend basat en rutes web, plantilles Blade, Tailwind CSS i JavaScript, executat a localhost:8001) i el servidor de l'API REST central (backend implementat en PHP/Laravel, encarregat de la lògica de negoci de fusteria i connectat a la base de dades MariaDB).
A continuació es detalla com s'ha implementat tècnicament aquest flux d'interacció i sincronització de dades segons el cicle de vida de les peticions:

### 4.1 Estratègia de Connexió i Arquitectura de Fluxos
La integració de dades entre ambdós servidors s'articula mitjançant dos mecanismes de connexió diferenciats segons la naturalesa de l'acció:

#### 4.1.1 Consum Servidor a Servidor (Backend-to-API síncron):
Per a accions estructurals com la càrrega inicial del catàleg o la previsualització comptable de la factura, el servidor web client actua com a passarel·la. Quan l'usuari navega per una categoria (paràmetre {slug} a web.php), el controlador CatalogueController resol l'ID i invoca el client HTTP natiu de Laravel (Http::get i Http::post). Per evitar l'exposició de rutes estàtiques, l'URL base s'injecta dinàmicament des del fitxer de configuració config/services.php, el qual llegeix la variable d'entorn API_BASE_URL definida al fitxer .env. Una vegada rebut el JSON del backend de l'API, el servidor web client compila les plantilles Blade, heretant del disseny general pare (layouts/app.blade.php), i envia el codi HTML final ja cuinat cap al navegador.

#### 4.1.2 Disparador Asíncron Local (Client-to-Server mitjançant JavaScript):
Per a accions d'alta interactivitat que requereixen mutacions immediates de la interfície sense recarregar la pantalla (com afegir o incrementar peces de fusta al carretó), l'execució es desplaça al motor de JavaScript del navegador, el qual realitza crides asíncronas (fetch) directament contra els endpoints locals de control del servidor web client.

### 4.2 Gestió d'Estats, Sessions Mixtes i Seguretat
El nucli del disseny d'aquest projecte recau en mantenir el servidor de l'API central completament lliure d'estat (stateless), delegant la persistència temporal en l'aplicació del client mitjançant un model dual:

#### 4.2.1 La Sessió de PHP local per a usuaris anònims:
Mentre el fuster navega de manera anònima i acumula llistons o bigues, les línies del carretó es guarden exclusivament dins d'un array a la sessió de PHP del servidor web client sota la clau current_order. Això evita obrir connexions o transaccions prematures a la base de dades MariaDB de l'API amb carrets que podrien acabar abandonats.

#### 4.2.2 Seguretat i Protecció de Peticions (CSRF):
En fer un enviament asíncron a través de JavaScript cap al servidor del client, l'aplicació exigeix un mecanisme de protecció de rutes. L'script llegeix la directiva {{ csrf_token() }} nativa de Laravel estampada a la vista Blade i la injecta directament a la capçalera HTTP sota la clau X-CSRF-TOKEN utilitzant el mètode de formulari application/x-www-form-urlencoded.


###  4.3️ Reptes Enfrontats i Solucions Implementades
Durant el desenvolupament d'aquesta arquitectura mixta es van haver de resoldre tres reptes tècnics de gran complexitat:

#### 4.3.1 Sincronització asíncrona i simulació econòmica centralitzada (Lògica de mides)

* Repte: El càlcul dels preus de la fusta depèn de múltiples unitats de mesura (m², m³, metres lineals o tires). Duplicar aquestes complexes fórmules matemàtiques al frontend hauria provocat problemes de consistència i decimals. A més, el servidor web del client no té accés directe a la base de dades on resideixen aquests preus.

* Solució: S'ha aprofitat el mètode showOrderDetails de l' OrderController del servidor web com a ntermediari. Quan l'usuari visita la seva comanda actual, el controlador extreu l'array current_order de la sessió de PHP (on hi ha, per exemple, l'ID 36 amb quantitat 3). Neteja i neteja les dades mitjançant un bucle foreach per crear una col·lecció simplificada de parelles d'identificadors i quantitats ($transformedItems). Acte seguit, llança una petició POST via Http::post cap a l'endpoint /api/orders/previews de l'API. El servidor de l'API calcula la base imposable, l'IVA (21%) i els totals exactes, retornant un objecte JSON estructurat ($apiData). El controlador del client el captura a la línia 146 i l'injecta netament a la vista Blade per pintar els imports econòmics exactes i unificats a la pantalla de l'usuari.

#### 4.3.2 Confirmació visual immediata sense rebuig de l'experiència d'usuari

* Repte: L'usuari necessita una confirmació immediata de que el producte s'ha afegit al carretó de la sessió sense que la pàgina web hagi de fer una recàrrega sencera del navegador, evitant trencar la fluïdesa de la navegació.

* Solució: S'ha codificat un mecanisme de transició dinàmica en el DOM dins de la funció JavaScript testAddProduct. En el moment en què la petició asíncrona fetch rep de tornada un codi d'èxit 200 OK per part del controlador del servidor web, el script clona i emmagatzema l'HTML i les classes CSS originals de la fila del producte. Immediatament, modifica les propietats .className e .innerHTML del contenidor per commutar la línia per una franja verda de confirmació amb un element SVG de validació i el missatge "El producte s'ha afegit correctament a la comanda actual". Per finalitzar el cicle, s'inicialitza un temporitzador en segon pla mitjançant setTimeout() que, al cap d'un interval exacte de 4 segons, restableix el disseny original deixant la graella llesta per a noves operacions.

## 4.3.3 Control de fluxos i renderització condicional de bucles buits

* Repte: L'aplicació ha de respondre de manera completament diferent en cas que la sessió de PHP no contingui productes, evitant errors d'execució en intentar mapejar o enviar arrays buits cap al servidor de l'API externa.

* Solució: Es va implementar una estructura condicional robusta en dues capes. Al controlador, si l'array de la sessió és buit, el flux d'execució es desvia directament evitant per complet la petició HTTP cap a l'API externa. En paral·lel, a la capa de presentació es fa servir la directiva dual @forelse de Blade; si la col·lecció arriba buida, el motor de plantilles omet automàticament les línies de muntatge de la taula i executa de forma nativa la clàusula @empty, injectant el contenidor #empty-cart-message. Això permet que l'script de JavaScript s'activi per imprimir un missatge aleatori de fons i forçar l'ocultació del botó final de compra si l'usuari es manté en estat anònim.

---

## 5 Reflexions sobre el procés d'aprenentatge i el desenvolupament assistit per IA
El desenvolupament d'aquest projecte, integrant de manera simultània un frontend basat en vistes Blade/JavaScript i un backend asíncron d'API REST en PHP/Laravel, ha suposat un aprenentatge profund en l'àmbit de les arquitectures de programació distribuïdes. L'ús de la intel·ligència artificial generativa com a eina de suport ha estat un factor clau en el procés, del qual se'n deriven les següents reflexions i conclusions:

### 5.1 Avantatges del Desenvolupament Assistit per IA

#### 5.1.1 Agilització en la Maquetació i Disseny Visual:
L'ús de la IA ha permès accelerar exponencialment la creació de les plantilles de la interfície. La generació de codi HTML net combinat amb classes dinàmiques de Tailwind CSS va estalviar un temps considerable en el disseny de la graella del catàleg i els components interactius (com el menú lateral ocult).

#### 5.1.2 Comprensió de Directives d'Estructura Avançades:
La IA va facilitar la correcta implementació i comprensió del motor de plantilles de Laravel, especialment en estructures híbrides complexes com l'ús de la directiva dual @forelse / @empty i l'orquestració de seccions amb @extends, @section i @yield per acoblar els fitxers pare i fill.

#### 5.1.3 Resolució de Problemes de Seguretat de fons:
L'assistència de la IA va ser clau per comprendre la necessitat imperiosa de protegir les comunicacions asíncronas mitjançant l'ús natiu del csrf_token, automatitzant la injecció de les capçaleres X-CSRF-TOKEN a les peticions HTTP fetch realitzades des del navegador cap al servidor del client.

### 5.2 Reptes, Aprenentatges i Limitacions de la IA

#### 5.2.1 El perill de les solucions genèriques:
Durant el procés, es va fer evident que la IA tendeix a proposar arquitectures genèriques estandarditzades (com delegar tot el flux a una Single Page Application clàssica). Va requerir una ntervenció humana exigent i l'anàlisi estricte amb el depurador Xdebug per redirigir la IA i fer-li entendre que el projecte es basava en una arquitectura mixta molt particular, on coexisteixen la sessió de PHP local (per a usuaris anònims) i les consultes de servidor a servidor (mitjançant la Facade HTTP).

#### 5.2.2 Gestió del Context i la Lògica de Negoci:
La IA pot generar fragments de codi aïllats molt eficients, però té dificultats per comprendre les regles de negoci globals (com el càlcul de preus basat en variables de lots i tipus d'unitats de fusta com m² o m³). L'aprenentatge més valuós ha estat adonar-se que la IA necessita un control i un guiatge humà constant; sense una supervisió rigorosa línia a línia del codi del controlador (OrderController), el sistema hauria caigut en inconsistències de decimals o errors de persistència de dades.

### 5.3 Conclusió General
L'experiència en aquest lliurament demostra que la IA generativa és un copilot extraordinari per augmentar la productivitat i resoldre bloquejos sintàctics o d'estil. No obstant això, l'arquitectura de programació i el rigor tècnic depenen exclusivament del desenvolupador. Entendre el viatge complet de les dades de forma cronològica, analitzar el comportament del servidor mitjançant punts de ruptura i forçar la separació neta de responsabilitats entre servidors són competències humanes que la IA no pot substituir.