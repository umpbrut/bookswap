<?php defined('APP') or die('Accesso Negato'); ?>

<!-- View per il form di creazione annuncio. Contiene stile, form e logica client-side. -->
<style>
    /* Animazione che fa scorrere il background orizzontalmente in loop continuo (80 secondi). */
    @keyframes scrollBackground {
        from { background-position: 0 0; }
        to   { background-position: -2000px 0; }
    }

    /* Background scorrevole e overlay scuro per ottenere profondità senza appesantire il form. */
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                    url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=3000');
        background-size: auto 100%;
        background-repeat: repeat-x;
        min-height: 100vh;
        margin: 0;
        animation: scrollBackground 80s linear infinite;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Il footer viene nascosto su questa pagina perché il form è centrato a schermo intero. */
    footer { display: none !important; }

    /* Card del form: sfondo bianco semi-trasparente con effetto sfocatura (vetro) e ombra profonda. */
    .annuncio-card {
        background: rgba(255, 255, 255, 0.93);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        color: #1a1a1a;
        padding: 2rem;
        border-radius: 15px;
        max-width: 600px;
        width: 100%;
        margin: 2rem auto;
        font-family: 'Segoe UI', sans-serif;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }

    /* Titolo della card: centrato, font serif per un aspetto editoriale/libresco. */
    .annuncio-card h2 {
        text-align: center;
        color: #1a1a1a;
        font-family: 'Georgia', serif;
        font-size: 2.5rem;
        margin-bottom: 2rem;
    }

    /* Label in maiuscoletto con lettera spaziata: stile tipografico coerente con il tema. */
    label {
        display: block;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #7a6040;
        margin-bottom: 0.5rem;
    }

    /* Stile base per tutti i campi input: sfondo grigio chiaro, bordo sottile, arrotondato. */
    input {
        width: 100%;
        background: #f5f5f5;
        border: 1px solid #ccc;
        color: #1a1a1a;
        padding: 12px;
        border-radius: 8px;
        box-sizing: border-box;
    }

    /* Input non modificabili: sfondo più scuro e cursore "vietato" per indicare la disabilitazione. */
    input[readonly] {
        background: #ececec;
        color: #888;
        cursor: not-allowed;
    }

    /* Contenitore relativo per il campo di ricerca: serve a posizionare il dropdown in modo assoluto sotto. */
    .search-container { position: relative; }

    /* Box dei risultati dell'autocomplete: posizionato sotto il campo, nascosto finché non ci sono risultati. */
    #custom_results {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        width: 100%;
        background: white;
        border-radius: 8px;
        z-index: 1000;
        display: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        border: 1px solid #ddd;
    }

    /* Freccia decorativa (pseudo-elemento) che punta verso l'alto, verso il campo di ricerca. */
    #custom_results::before {
        content: "";
        position: absolute;
        bottom: 100%;
        left: 20px;
        border: 10px solid transparent;
        border-bottom-color: white;
    }

    /* Stile delle singole voci del dropdown dei titoli. */
    #custom_results div {
        color: #333;
        padding: 12px 20px;
        cursor: pointer;
        font-size: 0.9rem;
        border-bottom: 1px solid #eee;
        text-transform: uppercase;
    }
    #custom_results div:last-child { border-bottom: none; }
    #custom_results div:hover      { background: #f0f0f0; border-radius: 8px; }

    /* Contenitore del dropdown per la ricerca per ISBN: stesso stile del dropdown titolo. */
    #isbn_results {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        width: 100%;
        background: white;
        border-radius: 8px;
        z-index: 1000;
        display: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        border: 1px solid #ddd;
    }
    #isbn_results::before {
        content: "";
        position: absolute;
        bottom: 100%;
        left: 20px;
        border: 10px solid transparent;
        border-bottom-color: white;
    }
    #isbn_results div {
        color: #333;
        padding: 12px 20px;
        cursor: pointer;
        font-size: 0.9rem;
        border-bottom: 1px solid #eee;
        text-transform: uppercase;
    }
    #isbn_results div:last-child { border-bottom: none; }
    #isbn_results div:hover      { background: #f0f0f0; border-radius: 8px; }

    /* Wrapper per il dropdown simulato delle condizioni: position:relative per il figlio assoluto. */
    .select-custom-wrapper {
        position: relative;
        cursor: pointer;
    }

    /* Trigger visibile del dropdown: imita uno <select> nativo con freccia custom. */
    .select-trigger {
        background: #f5f5f5;
        border: 1px solid #ccc;
        padding: 12px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #1a1a1a;
    }

    /* Menu a discesa nascosto che appare solo quando si clicca sul trigger. */
    .select-options-cond {
        position: absolute;
        width: 100%;
        background: #f5f5f5;
        border: 1px solid #ccc;
        border-radius: 8px;
        z-index: 100;
        display: none;
        margin-top: 5px;
    }
    .select-options-cond div {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        color: #1a1a1a;
        cursor: pointer;
    }
    .select-options-cond div:hover { background: #ebebeb; }

    /* Area di upload immagini: bordo tratteggiato, cliccabile, centrato. */
    .upload-container {
        border: 2px dashed #ccc;
        padding: 20px;
        text-align: center;
        border-radius: 10px;
        cursor: pointer;
        margin-top: 10px;
    }

    /* Se le foto sono obbligatorie e mancanti, il bordo diventa rosso come segnale di errore. */
    .upload-container.error { border-color: #e74c3c; }

    /* Anteprima immagini caricate: tre colonne uguali per mostrare le foto affiancate. */
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 10px;
    }

    /* Singola anteprima: altezza fissa con overflow nascosto per uniformità. */
    .preview-item {
        position: relative;
        height: 80px;
    }
    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 5px;
    }

    /* Bottone "X" per rimuovere una singola anteprima: cerchio rosso posizionato in alto a destra. */
    .remove-img {
        position: absolute;
        top: -5px;
        right: -5px;
        background: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        cursor: pointer;
        font-size: 12px;
    }

    /* Bottone principale di invio: dorato-brunito, tutto maiuscolo, larghezza intera. */
    .btn-submit {
        background: #b58d5b;
        color: white;
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        margin-top: 2rem;
    }
    .btn-submit:hover { background: #a07840; }

    /* Griglia a due colonne per affiancare coppie di campi (prezzo/luogo, data/ora). */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 1.5rem;
    }

    /* Messaggio di errore foto: testo rosso piccolo mostrato sotto l'area upload. */
    #foto-error {
        color: #e74c3c;
        font-size: 0.78rem;
        margin-top: 6px;
        display: none;
    }
</style>

<div class="annuncio-card">
    <h2>Pubblica Annuncio</h2>

    <!-- Form con enctype multipart/form-data per supportare l'upload di file (immagini). -->
    <!-- L'id "publishForm" viene usato dallo script per la validazione lato client. -->
    <form action="index.php?page=annunci&action=store" method="post" enctype="multipart/form-data" id="publishForm">

        <!-- id_libro: campo nascosto inviato al server; viene popolato via JS quando si seleziona un libro. -->
        <input type="hidden" name="id_libro"   id="id_libro_hidden">
        <!-- condizioni: campo nascosto che memorizza la scelta del dropdown simulato. -->
        <input type="hidden" name="condizioni" id="condizioni_hidden" value="Nuovo (Mai aperto)">

        <!-- ── RICERCA TITOLO (modifica n.3) ──
             Il campo "search" mostra il titolo selezionato, ma è readonly per l'utente:
             non è possibile digitare direttamente un titolo libero.
             Questo garantisce che il libro sia sempre collegato a un ISBN valido nel DB.
             Il campo diventa scrivibile solo per la ricerca live (gestita da JS via oninput). -->
        <div style="margin-bottom:1.5rem;" class="search-container">
            <label>Titolo del Libro</label>
            <!-- "readonly" viene rimosso da JS solo durante la ricerca per permettere la digitazione.
                 In realtà qui il campo è editabile per la ricerca; la protezione è che
                 id_libro_hidden rimane vuoto finché non si seleziona dal dropdown. -->
            <input type="text" id="search" oninput="get_libri()" placeholder="Cerca per titolo..." autocomplete="off">
            <div id="custom_results"></div>
        </div>

        <!-- ── RICERCA ISBN (modifica n.4) ──
             Oltre alla ricerca per titolo, l'utente può cercare anche per ISBN.
             Digitando nel campo ISBN il sistema suggerisce i libri corrispondenti
             e, alla selezione, compila automaticamente il campo titolo e l'ID nascosto.
             Il campo non è readonly perché serve per la ricerca; come per il titolo,
             il valore "vero" che va al server è id_libro_hidden. -->
        <div style="margin-bottom:1.5rem;" class="search-container">
            <label>ISBN</label>
            <input type="text" id="isbn_input" oninput="get_libri_isbn()" placeholder="Cerca per ISBN..." autocomplete="off">
            <div id="isbn_results"></div>
        </div>

        <!-- PREZZO e LUOGO: affiancati nella stessa riga tramite griglia CSS. -->
        <div class="form-grid">
            <div>
                <label>Prezzo (€)</label>
                <input type="number" step="0.01" name="prezzo_vendita" id="prezzoInput"
                       placeholder="es. 12.50" min="0.01" max="200.00" required>
            </div>
            <div>
                <label>Luogo di Scambio</label>
                <input type="text" name="luogo" placeholder="Es. Biblioteca" required>
            </div>
        </div>

        <!-- DATA e ORA dell'incontro: pre-compilati con la data/ora corrente come valore di default. -->
        <div class="form-grid">
            <div>
                <label>Data</label>
                <input type="date" name="data" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div>
                <label>Ora</label>
                <input type="time" name="ora" value="<?= date('H:i') ?>" required>
            </div>
        </div>

        <!-- CONDIZIONI: dropdown CSS custom che sostituisce il <select> nativo per compatibilità stilistica. -->
        <div style="margin-bottom:1.5rem;">
            <label>Condizioni del libro</label>
            <div class="select-custom-wrapper" id="condizioniWrapper">
                <!-- Il trigger mostra l'opzione corrente e apre la lista al click. -->
                <div class="select-trigger" onclick="toggleCondizioni()">
                    <span id="selectedCondizione">Nuovo (Mai aperto)</span>
                    <span style="font-size:10px;color:#9c6b3c;">▼</span>
                </div>
                <div class="select-options-cond" id="condizioniOptions">
                    <div onclick="selectCondizione('Nuovo (Mai aperto)')">Nuovo (Mai aperto)</div>
                    <div onclick="selectCondizione('Ottime condizioni')">Ottime condizioni</div>
                    <div onclick="selectCondizione('Buone condizioni')">Buone condizioni</div>
                    <div onclick="selectCondizione('Usato / Rovinato')">Usato / Rovinato</div>
                </div>
            </div>
        </div>

        <!-- UPLOAD IMMAGINI (modifica n.5): le 3 foto sono ora obbligatorie.
             La validazione avviene lato client nel submit del form.
             L'area è cliccabile e apre il selettore file del sistema operativo. -->
        <label>Immagini (Obbligatorie: esattamente 3)</label>
        <div class="upload-container" id="uploadContainer" onclick="document.getElementById('fileInput').click()">
            <span style="color:#7a6040;font-size:0.9rem;">
                Aggiungi esattamente 3 immagini<br>
                <small>Clicca qui per selezionarle</small>
            </span>
            <!-- Il file input è nascosto; viene attivato dal click sul div sopra. -->
            <!-- "multiple" permette la selezione multipla; onchange avvia la preview. -->
            <input type="file" id="fileInput" name="foto[]" multiple accept="image/*"
                   style="display:none" onchange="previewImages(this.files)">
        </div>
        <!-- Griglia delle anteprime: viene popolata dinamicamente da previewImages(). -->
        <div id="previewGrid" class="preview-grid"></div>
        <!-- Messaggio di errore mostrato se si tenta di inviare con meno o più di 3 foto. -->
        <div id="foto-error">Devi caricare esattamente 3 foto prima di pubblicare.</div>

        <button type="submit" class="btn-submit">Pubblica Annuncio</button>
    </form>
</div>

<script>
    // ── Array globale che mantiene in memoria i file immagine selezionati dall'utente ──
    // Viene aggiornato ogni volta che si aggiunge o rimuove una foto.
    let imagesArray = [];

    // ── Ricerca per TITOLO (modifica n.3) ──
    // Chiamata a ogni tasto nel campo #search.
    // Interroga libri.php via AJAX e mostra un dropdown di titoli corrispondenti.
    // L'utente non può modificare il titolo a mano: deve selezionare dal dropdown.
    function get_libri() {
        let cerca     = document.getElementById('search').value;
        let container = document.getElementById('custom_results');
        let hiddenInput = document.getElementById('id_libro_hidden');
        let isbnInput   = document.getElementById('isbn_input');

        // Sotto i 2 caratteri non ha senso cercare: troppi falsi positivi.
        if (cerca.length < 2) {
            container.style.display = 'none';
            hiddenInput.value = "";
            return;
        }

        // Fetch all'endpoint libri.php con il testo di ricerca come parametro.
        fetch("libri.php?get_libri&testo=" + encodeURIComponent(cerca))
            .then(res => res.json())
            .then(data => {
                container.innerHTML = "";
                if (data.length > 0) {
                    container.style.display = 'block';
                    // Crea una <div> cliccabile per ogni libro trovato.
                    data.forEach(riga => {
                        let item   = document.createElement('div');
                        let titolo = riga.Titolo || riga.titolo;
                        let isbn   = riga.ISBN   || riga.isbn;
                        let id     = riga.ID_Libro || riga.id_libro;
                        item.innerText = titolo;
                        // Al click: compila il titolo visibile, l'ISBN e l'id_libro nascosto.
                        item.onclick = function () {
                            document.getElementById('search').value = titolo;
                            isbnInput.value   = isbn || "";
                            hiddenInput.value = id;
                            container.style.display = 'none';
                        };
                        container.appendChild(item);
                    });
                } else {
                    container.style.display = 'none';
                }
            });
    }

    // ── Ricerca per ISBN (modifica n.4) ──
    // Funziona come get_libri() ma parte dal codice ISBN digitato nel campo #isbn_input.
    // Cerca libri il cui ISBN contiene il testo inserito e mostra il dropdown #isbn_results.
    // Alla selezione, compila automaticamente il campo titolo e l'ID nascosto.
    function get_libri_isbn() {
        let isbnDigitato = document.getElementById('isbn_input').value;
        let container    = document.getElementById('isbn_results');
        let hiddenInput  = document.getElementById('id_libro_hidden');
        let titoloInput  = document.getElementById('search');

        // Parte la ricerca solo con almeno 3 caratteri per evitare risultati troppo generici.
        if (isbnDigitato.length < 3) {
            container.style.display = 'none';
            hiddenInput.value = "";
            return;
        }

        // Riusa lo stesso endpoint libri.php ma filtra per ISBN anziché per titolo.
        // NOTA: l'endpoint deve supportare il parametro "isbn" — vedi libri.php (modifica n.4).
        fetch("libri.php?get_libri_isbn&testo=" + encodeURIComponent(isbnDigitato))
            .then(res => res.json())
            .then(data => {
                container.innerHTML = "";
                if (data.length > 0) {
                    container.style.display = 'block';
                    data.forEach(riga => {
                        let item   = document.createElement('div');
                        let titolo = riga.Titolo || riga.titolo;
                        let isbn   = riga.ISBN   || riga.isbn;
                        let id     = riga.ID_Libro || riga.id_libro;
                        // Mostra sia l'ISBN che il titolo nel dropdown per chiarezza.
                        item.innerText = isbn + " — " + titolo;
                        item.onclick = function () {
                            document.getElementById('isbn_input').value = isbn || "";
                            titoloInput.value = titolo;
                            hiddenInput.value = id;
                            container.style.display = 'none';
                        };
                        container.appendChild(item);
                    });
                } else {
                    container.style.display = 'none';
                }
            });
    }

    // ── Dropdown Condizioni ──
    // Apre/chiude la lista delle condizioni al click sul trigger.
    function toggleCondizioni() {
        const o = document.getElementById('condizioniOptions');
        o.style.display = o.style.display === 'block' ? 'none' : 'block';
    }
    // Aggiorna il testo visibile e il valore dell'input nascosto con la scelta effettuata.
    function selectCondizione(val) {
        document.getElementById('selectedCondizione').innerText  = val;
        document.getElementById('condizioni_hidden').value       = val;
        document.getElementById('condizioniOptions').style.display = 'none';
    }

    // ── Anteprima immagini (modifica n.5) ──
    // Aggiunge i file selezionati all'array globale e ne mostra l'anteprima.
    // Blocca se si supera il limite di 3 immagini totali.
    function previewImages(files) {
        const grid = document.getElementById('previewGrid');
        if (imagesArray.length + files.length > 3) {
            alert("Puoi caricare al massimo 3 immagini!");
            return;
        }
        Array.from(files).forEach(file => {
            imagesArray.push(file);
            const reader = new FileReader();
            // FileReader legge il file in modo asincrono e lo converte in base64 per la preview.
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'preview-item';
                // Inserisce l'immagine e il bottone di rimozione nella griglia.
                div.innerHTML = `<img src="${e.target.result}">
                    <button type="button" class="remove-img" onclick="removeImg(${imagesArray.length - 1})">✕</button>`;
                grid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        // Aggiorna il colore del bordo upload: verde se ok (3), rosso se incompleto.
        updateUploadBorder();
    }

    // Rimuove un'immagine dall'array e ridisegna tutte le anteprime da zero.
    function removeImg(index) {
        imagesArray.splice(index, 1);
        document.getElementById('previewGrid').innerHTML = '';
        // Salva una copia e svuota l'array prima di riaggiungere per ricalcolare gli indici.
        const currentFiles = [...imagesArray];
        imagesArray = [];
        previewImages(currentFiles);
        updateUploadBorder();
    }

    // Aggiorna il bordo dell'area upload (rosso = incompleto, normale = ok) e nasconde/mostra l'errore.
    function updateUploadBorder() {
        const container = document.getElementById('uploadContainer');
        const errMsg    = document.getElementById('foto-error');
        if (imagesArray.length === 3) {
            container.classList.remove('error');
            errMsg.style.display = 'none';
        }
        // Non mostriamo l'errore in tempo reale, solo al submit.
    }

    // ── Validazione al submit (modifica n.5) ──
    // Prima di inviare il form verifica che siano state caricate esattamente 3 foto.
    // Se non è così, blocca l'invio e mostra il messaggio di errore.
    document.getElementById('publishForm').addEventListener('submit', function (e) {
        const errMsg    = document.getElementById('foto-error');
        const container = document.getElementById('uploadContainer');

        if (imagesArray.length !== 3) {
            // Previene l'invio del form.
            e.preventDefault();
            // Segnala visivamente l'errore sul campo upload.
            container.classList.add('error');
            errMsg.style.display = 'block';
            // Scorre la pagina verso il messaggio di errore per renderlo visibile.
            errMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        // Le 3 foto ci sono: dobbiamo sincronizzarle con l'input file prima dell'invio.
        // FileList nativo non è modificabile, quindi usiamo un DataTransfer per ricrearlo.
        const dt = new DataTransfer();
        imagesArray.forEach(f => dt.items.add(f));
        document.getElementById('fileInput').files = dt.files;
    });

    // Chiude i dropdown aperti quando si clicca altrove nella pagina.
    document.addEventListener('click', function (e) {
        if (!document.getElementById('search').contains(e.target))
            document.getElementById('custom_results').style.display = 'none';
        if (!document.getElementById('isbn_input').contains(e.target))
            document.getElementById('isbn_results').style.display = 'none';
        if (!document.getElementById('condizioniWrapper').contains(e.target))
            document.getElementById('condizioniOptions').style.display = 'none';
    });
</script>
