<?php 
$annuncio = $table[0];

// Mappa condizioni brevi → estese (per compatibilità con dati già esistenti nel DB)
$condizioniMap = [
    'Nuovo'  => 'Nuovo (Mai aperto)',
    'Ottime' => 'Ottime condizioni',
    'Buone'  => 'Buone condizioni',
    'Usato'  => 'Usato / Rovinato',
];
// Se il valore è già esteso lo lascia, altrimenti lo converte
$condizioneAttuale = $condizioniMap[$annuncio['condizioni']] ?? $annuncio['condizioni'];
?>

<style>
    @keyframes scrollBackground {
        from { background-position: 0 0; }
        to   { background-position: -2000px 0; }
    }
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
    .annuncio-card {
        background: rgba(255,255,255,0.94);
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
    .annuncio-card h2 {
        text-align: center;
        color: #1a1a1a;
        font-family: 'Georgia', serif;
        font-size: 2.5rem;
        margin-bottom: 2rem;
    }
    label {
        display: block;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #7a6040;
        margin-bottom: 0.5rem;
    }
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
    input[readonly] {
        background: #ececec;
        color: #888;
        cursor: not-allowed;
    }
    .search-container { position: relative; }
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
    #custom_results div {
        color: #333;
        padding: 12px 20px;
        cursor: pointer;
        font-size: 0.9rem;
        border-bottom: 1px solid #eee;
        text-transform: uppercase;
    }
    #custom_results div:last-child { border-bottom: none; }
    #custom_results div:hover { background: #f0f0f0; }
    .select-custom-wrapper { position: relative; cursor: pointer; }
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
    .select-options-cond,
    .select-options-stato {
        position: absolute;
        width: 100%;
        background: #f5f5f5;
        border: 1px solid #ccc;
        border-radius: 8px;
        z-index: 100;
        display: none;
        margin-top: 5px;
    }
    .select-options-cond div,
    .select-options-stato div {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        color: #1a1a1a;
        cursor: pointer;
    }
    .select-options-cond div:hover,
    .select-options-stato div:hover { background: #ebebeb; }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 1.5rem;
    }
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

    <form action="index.php?page=annunci&action=edit" method="post">

        <input type="hidden" name="id_annuncio" value="<?= $annuncio['id_annuncio'] ?>">
        <input type="hidden" name="id_libro"    id="id_libro_hidden" value="<?= $annuncio['id_libro'] ?>">
        <input type="hidden" name="condizioni"  id="condizioni_hidden" value="<?= htmlspecialchars($condizioneAttuale) ?>">
        <input type="hidden" name="stato"       id="stato_hidden" value="<?= htmlspecialchars($annuncio['stato']) ?>">

        <!-- RICERCA TITOLO -->
        <div style="margin-bottom:1.5rem;" class="search-container">
            <label>Libro</label>
            <input type="text" id="search" oninput="get_libri()"
                   placeholder="Cerca un libro..." autocomplete="off"
                   value="<?= htmlspecialchars($annuncio['titolo'] ?? '') ?>">
            <div id="custom_results"></div>
        </div>

        <!-- ISBN (readonly, si popola automaticamente) -->
        <div style="margin-bottom:1.5rem;">
            <label>ISBN</label>
            <input type="text" id="isbn_input" readonly
                   placeholder="Seleziona un libro per vedere l'ISBN"
                   value="<?= htmlspecialchars($annuncio['ISBN'] ?? '') ?>">
        </div>

        <!-- PREZZO + LUOGO -->
        <div class="form-grid">
            <div>
                <label>Prezzo (€)</label>
                <input type="number" step="0.01" name="prezzo_vendita"
                       min="0.01" max="200"
                       value="<?= $annuncio['prezzo_vendita'] ?>" required>
            </div>
            <div>
                <label>Luogo</label>
                <input type="text" name="luogo"
                       value="<?= htmlspecialchars($annuncio['luogo']) ?>" required>
            </div>
        </div>

        <!-- DATA + ORA -->
        <div class="form-grid">
            <div>
                <label>Data incontro</label>
                <input type="date" name="data"
                       value="<?= htmlspecialchars($annuncio['data'] ?? '') ?>" required>
            </div>
            <div>
                <label>Ora</label>
                <input type="time" name="ora"
                       value="<?= htmlspecialchars($annuncio['ora']) ?>" required>
            </div>
        </div>

        <!-- CONDIZIONI -->
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

        <!-- STATO -->
        <div style="margin-bottom:1.5rem;">
            <label>Stato Annuncio</label>
            <div class="select-custom-wrapper" id="statoWrapper">
                <div class="select-trigger" onclick="toggleStato()">
                    <span id="selectedStato"><?= htmlspecialchars($annuncio['stato']) ?></span>
                    <span style="font-size:10px;color:#9c6b3c;">▼</span>
                </div>
                <div class="select-options-stato" id="statoOptions">
                    <div onclick="selectStato('Disponibile')">Disponibile</div>
                    <div onclick="selectStato('Non disponibile')">Non Disponibile</div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-submit">Salva Modifiche</button>
    </form>
</div>

<script>
    // ── Ricerca libro con ISBN automatico ──
    function get_libri() {
        let cerca = document.getElementById('search').value;
        let hiddenId   = document.getElementById('id_libro_hidden');
        let isbnInput  = document.getElementById('isbn_input');
        let container  = document.getElementById('custom_results');

        if (cerca === '') {
            hiddenId.value = '';
            isbnInput.value = '';
            container.style.display = 'none';
            return;
        }

        fetch('libri.php?get_libri&testo=' + encodeURIComponent(cerca))
        .then(res => res.json())
        .then(data => {
            container.innerHTML = '';
            if (data.length > 0) {
                container.style.display = 'block';
                data.forEach(riga => {
                    let titolo = riga.titolo || riga.Titolo;
                    let id     = riga.id_libro || riga.ID_Libro;
                    let isbn   = riga.ISBN || riga.isbn || '';

                    let item = document.createElement('div');
                    item.innerText = titolo;
                    item.onclick = function() {
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

    // ── Condizioni ──
    function toggleCondizioni() {
        let o = document.getElementById('condizioniOptions');
        o.style.display = o.style.display === 'block' ? 'none' : 'block';
    }
    function selectCondizione(valore) {
        document.getElementById('selectedCondizione').innerText = valore;
        document.getElementById('condizioni_hidden').value = valore;
        document.getElementById('condizioniOptions').style.display = 'none';
    }

    // ── Stato ──
    function toggleStato() {
        let o = document.getElementById('statoOptions');
        o.style.display = o.style.display === 'block' ? 'none' : 'block';
    }
    function selectStato(valore) {
        document.getElementById('selectedStato').innerText = valore;
        document.getElementById('stato_hidden').value = valore;
        document.getElementById('statoOptions').style.display = 'none';
    }

    // Chiudi dropdown cliccando fuori
    document.addEventListener('click', function(e) {
        if (!document.getElementById('search').contains(e.target))
            document.getElementById('custom_results').style.display = 'none';
        if (!document.getElementById('condizioniWrapper').contains(e.target))
            document.getElementById('condizioniOptions').style.display = 'none';
        if (!document.getElementById('statoWrapper').contains(e.target))
            document.getElementById('statoOptions').style.display = 'none';
    });
</script>