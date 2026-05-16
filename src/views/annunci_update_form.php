<?php
// Recupera il primo (e unico) record dell'annuncio passato dal controller tramite $table.
$annuncio = $table[0];

// Mappa le condizioni abbreviate verso le versioni estese usate nel form.
// Serve per compatibilità con annunci salvati con il vecchio formato breve.
$condizioniMap = [
    'Nuovo'  => 'Nuovo (Mai aperto)',
    'Ottime' => 'Ottime condizioni',
    'Buone'  => 'Buone condizioni',
    'Usato'  => 'Usato / Rovinato',
];
// Se il valore esiste nella mappa lo converte, altrimenti lo usa così com'è.
$condizioneAttuale = $condizioniMap[$annuncio['condizioni']] ?? $annuncio['condizioni'];
?>

<style>
    /* Animazione che fa scorrere il background da sinistra verso destra in loop continuo. */
    @keyframes scrollBackground {
        from { background-position: 0 0; }
        to   { background-position: -2000px 0; }
    }

    /* Sfondo con overlay scuro semi-trasparente sovrapposto all'immagine, per far risaltare il form. */
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

    /* Card principale del form: sfondo bianco leggermente trasparente con effetto vetro (backdrop-filter). */
    .annuncio-card {
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(6px);
        color: #1a1a1a;
        padding: 2rem;
        border-radius: 15px;
        max-width: 600px;
        width: 100%;
        margin: 2rem auto;
        font-family: 'Segoe UI', sans-serif;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }

    /* Titolo centrato del form, con font serif per un aspetto editoriale. */
    .annuncio-card h2 {
        text-align: center;
        color: #1a1a1a;
        font-family: 'Georgia', serif;
        font-size: 2.5rem;
        margin-bottom: 2rem;
    }

    /* Label in maiuscoletto, spaziature strette, tono dorato-brunito coerente con l'identità visiva. */
    label {
        display: block;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #7a6040;
        margin-bottom: 0.5rem;
    }

    /* Stile base condiviso per tutti gli input testuali, numerici, orari e date. */
    input[type="text"],
    input[type="number"],
    input[type="time"],
    input[type="date"] {
        width: 100%;
        background: #f5f5f5;
        border: 1px solid #ccc;
        color: #1a1a1a;
        padding: 12px;
        border-radius: 8px;
        box-sizing: border-box;
    }

    /* Input disabilitati alla modifica: sfondo grigio e cursore "vietato" per feedback visivo. */
    input[readonly] {
        background: #ececec;
        color: #888;
        cursor: not-allowed;
    }

    /* Contenitore relativo che permette al dropdown di posizionarsi esattamente sotto il campo. */
    .search-container {
        position: relative;
    }

    /* Dropdown dei risultati di ricerca: assoluto, z-index alto per sovrapporsi al contenuto. */
    #custom_results {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        width: 100%;
        background: white;
        border-radius: 8px;
        z-index: 1000;
        display: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        border: 1px solid #ddd;
        max-height: 220px;
        overflow-y: auto;
    }

    /* Singola riga del dropdown: padding generoso, maiuscoletto, separatore inferiore. */
    #custom_results div {
        color: #333;
        padding: 12px 20px;
        cursor: pointer;
        font-size: 0.9rem;
        border-bottom: 1px solid #eee;
        text-transform: uppercase;
    }
    #custom_results div:last-child { border-bottom: none; }
    #custom_results div:hover      { background: #f0f0f0; }

    /* Wrapper per i dropdown simulati (condizioni): position:relative serve al figlio assoluto. */
    .select-custom-wrapper {
        position: relative;
        cursor: pointer;
    }

    /* Trigger visibile del dropdown: imita uno <select> ma con stile personalizzato. */
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

    /* Lista opzioni per le condizioni: rimane nascosta fino al click sul trigger. */
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

    /* Griglia a due colonne per affiancare campi correlati (es. prezzo + luogo, data + ora). */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 1.5rem;
    }

    /* Bottone di invio principale: colore dorato-brunito, testo in maiuscoletto spaziato. */
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
</style>

<div class="annuncio-card">
    <h2>Modifica Annuncio</h2>

    <!-- Form inviato in POST verso il controller annunci, azione "edit". -->
    <form action="index.php?page=annunci&action=edit" method="post">

        <!-- Campi nascosti che trasportano l'ID annuncio, l'ID libro e le condizioni selezionate. -->
        <input type="hidden" name="id_annuncio" value="<?= $annuncio['id_annuncio'] ?>">
        <input type="hidden" name="id_libro"    id="id_libro_hidden" value="<?= $annuncio['id_libro'] ?>">
        <input type="hidden" name="condizioni"  id="condizioni_hidden" value="<?= htmlspecialchars($condizioneAttuale) ?>">

        <!-- NOTA: lo stato NON è modificabile da questo form (modifica n.1).
             Viene gestito esclusivamente tramite i pulsanti "Consegnato" e "Ripristina"
             nella sezione Ordini → Venduti, per garantire coerenza nel flusso di acquisto. -->

        <!-- RICERCA TITOLO: campo di testo con autocomplete live.
             Il campo è read-only dal titolo digitato manualmente (modifica n.3):
             il titolo si imposta solo selezionando dal dropdown, mai a mano,
             così è sempre collegato a un ISBN valido nel database. -->
        <div style="margin-bottom:1.5rem;" class="search-container">
            <label>Libro</label>
            <input type="text" id="search" oninput="get_libri()" placeholder="Cerca un libro..."
                   autocomplete="off" value="<?= htmlspecialchars($annuncio['titolo'] ?? '') ?>">
            <div id="custom_results"></div>
        </div>

        <!-- ISBN: campo di sola lettura, popolato automaticamente quando si seleziona un libro. -->
        <div style="margin-bottom:1.5rem;">
            <label>ISBN</label>
            <input type="text" id="isbn_input" readonly
                   placeholder="Seleziona un libro per vedere l'ISBN"
                   value="<?= htmlspecialchars($annuncio['ISBN'] ?? '') ?>">
        </div>

        <!-- PREZZO e LUOGO: affiancati con la griglia a due colonne. -->
        <div class="form-grid">
            <div>
                <label>Prezzo (€)</label>
                <input type="number" step="0.01" name="prezzo_vendita" min="0.01" max="200"
                       value="<?= $annuncio['prezzo_vendita'] ?>" required>
            </div>
            <div>
                <label>Luogo</label>
                <input type="text" name="luogo" value="<?= htmlspecialchars($annuncio['luogo']) ?>" required>
            </div>
        </div>

        <!-- DATA e ORA dell'incontro: affiancate nella stessa riga. -->
        <div class="form-grid">
            <div>
                <label>Data incontro</label>
                <input type="date" name="data" value="<?= htmlspecialchars($annuncio['data'] ?? '') ?>" required>
            </div>
            <div>
                <label>Ora</label>
                <input type="time" name="ora" value="<?= htmlspecialchars($annuncio['ora']) ?>" required>
            </div>
        </div>

        <!-- CONDIZIONI: dropdown personalizzato con le 4 fasce di usura del libro. -->
        <div style="margin-bottom:1.5rem;">
            <label>Condizioni del libro</label>
            <div class="select-custom-wrapper" id="condizioniWrapper">
                <div class="select-trigger" onclick="toggleCondizioni()">
                    <span id="selectedCondizione"><?= htmlspecialchars($condizioneAttuale) ?></span>
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

        <!-- STATO RIMOSSO (modifica n.1): il blocco "Stato Annuncio" con il dropdown
             Disponibile / Non Disponibile è stato eliminato da questo form. -->

        <button type="submit" class="btn-submit">Salva Modifiche</button>
    </form>
</div>

<script>
    // ── Ricerca libro per TITOLO con aggiornamento automatico dell'ISBN ──
    // Viene chiamata a ogni tasto premuto nel campo #search.
    // Interroga libri.php via fetch e popola il dropdown #custom_results.
    function get_libri() {
        let cerca     = document.getElementById('search').value;
        let hiddenId  = document.getElementById('id_libro_hidden');
        let isbnInput = document.getElementById('isbn_input');
        let container = document.getElementById('custom_results');

        // Se il campo è vuoto, resetta l'ISBN e chiude il dropdown.
        if (cerca === '') {
            hiddenId.value  = '';
            isbnInput.value = '';
            container.style.display = 'none';
            return;
        }

        // Chiamata AJAX verso l'endpoint JSON di ricerca per titolo.
        fetch('libri.php?get_libri&testo=' + encodeURIComponent(cerca))
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if (data.length > 0) {
                    container.style.display = 'block';
                    // Per ogni risultato crea una riga cliccabile nel dropdown.
                    data.forEach(riga => {
                        let titolo = riga.titolo || riga.Titolo;
                        let id     = riga.id_libro || riga.ID_Libro;
                        let isbn   = riga.ISBN || riga.isbn || '';

                        let item = document.createElement('div');
                        item.innerText = titolo;
                        // Al click: compila il campo visibile, l'ID nascosto e l'ISBN readonly.
                        item.onclick = function () {
                            document.getElementById('search').value = titolo;
                            hiddenId.value  = id;
                            isbnInput.value = isbn;
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
    // Apre/chiude la lista delle opzioni quando si clicca sul trigger.
    function toggleCondizioni() {
        let o = document.getElementById('condizioniOptions');
        o.style.display = o.style.display === 'block' ? 'none' : 'block';
    }
    // Aggiorna il testo visibile e il valore dell'input nascosto con la scelta effettuata.
    function selectCondizione(valore) {
        document.getElementById('selectedCondizione').innerText        = valore;
        document.getElementById('condizioni_hidden').value             = valore;
        document.getElementById('condizioniOptions').style.display     = 'none';
    }

    // Chiude qualsiasi dropdown aperto quando l'utente clicca altrove nella pagina.
    document.addEventListener('click', function (e) {
        if (!document.getElementById('search').contains(e.target))
            document.getElementById('custom_results').style.display = 'none';
        if (!document.getElementById('condizioniWrapper').contains(e.target))
            document.getElementById('condizioniOptions').style.display = 'none';
    });
</script>
