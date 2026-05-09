<?php 
$annuncio = $table[0];
$condizioniMap = [
    'Nuovo'  => 'Nuovo (Mai aperto)',
    'Ottime' => 'Ottime condizioni',
    'Buone'  => 'Buone condizioni',
    'Usato'  => 'Usato / Rovinato',
];
$condizioneAttuale = $condizioniMap[$annuncio['condizioni']] ?? 'Nuovo (Mai aperto)';
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
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(6px)
        -webkit-backdrop-filter: blur(6px)
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
    input[type="time"] {
        width: 100%;
        background: #f5f5f5;
        border: 1px solid #ccc;
        color: #1a1a1a;
        padding: 12px;
        border-radius: 8px;
        box-sizing: border-box;
    }

    .search-container { position: relative; }

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

    #custom_results::before {
        content: "";
        position: absolute;
        bottom: 100%;
        left: 20px;
        border: 10px solid transparent;
        border-bottom-color: white;
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
    #custom_results div:hover { background: #f0f0f0; border-radius: 8px; }

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

    .select-stato-wrapper { position: relative; cursor: pointer; }

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

    .select-options-stato div {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        color: #1a1a1a;
        cursor: pointer;
    }

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

        <input type="hidden" name="id_annuncio" value="<?php echo $annuncio['id_annuncio']; ?>">
        <input type="hidden" name="id_libro" id="id_libro_hidden" value="<?php echo $annuncio['id_libro']; ?>">
        <input type="hidden" name="condizioni" id="condizioni_hidden" value="<?php echo $annuncio['condizioni']; ?>">
        <input type="hidden" name="stato" id="stato_hidden" value="<?php echo $annuncio['stato']; ?>">

        <div style="margin-bottom: 1.5rem;" class="search-container">
            <label>Libro</label>
            <input type="text" id="search" oninput="get_libri()"
                   placeholder="Cerca un libro..." autocomplete="off"
                   value="<?php echo isset($annuncio['titolo']) ? htmlspecialchars($annuncio['titolo']) : ''; ?>">
            <div id="custom_results"></div>

            <script>
                function get_libri() {
                    let cerca = document.getElementById('search').value;
                    let lista = document.getElementById('lista_libri');
                    let hiddenInput = document.getElementById('id_libro_hidden');
                    let container = document.getElementById('custom_results');

                    if (cerca == "") {
                        hiddenInput.value = "";
                        container.style.display = 'none';
                        return;
                    }

                    fetch("libri.php?get_libri&testo=" + cerca)
                    .then(res => res.json())
                    .then(data => {
                        container.innerHTML = "";
                        if (data.length > 0) {
                            container.style.display = 'block';
                            data.forEach(riga => {
                                let option = document.createElement('option');
                                option.value = riga.titolo;
                                option.setAttribute('data-id', riga.id_libro);
                                lista.appendChild(option);

                                let item = document.createElement('div');
                                let titolo = riga.titolo || riga.Titolo;
                                let id = riga.id_libro || riga.ID_Libro;
                                item.innerText = titolo;
                                item.onclick = function() {
                                    document.getElementById('search').value = titolo;
                                    hiddenInput.value = id;
                                    // NOTA: non resettare a "" qui nell'update se l'utente sta solo scrivendo,
                                    // o rischi di svuotare l'ID precedente mentre digita.
                                    container.style.display = 'none';
                                };
                                container.appendChild(item);
                            });

                            let opzioneTrovata = Array.from(lista.options).find(opt => opt.value === cerca);
                            if (opzioneTrovata) {
                                hiddenInput.value = opzioneTrovata.getAttribute('data-id');
                            }
                        } else {
                            container.style.display = 'none';
                        }
                    });
                }
            </script>
        </div>

        <div class="form-grid">
            <div>
                <label for="prezzo_vendita">Prezzo (€)</label>
                <input type="number" step="0.01" name="prezzo_vendita" id="prezzo_vendita"
                       value="<?php echo $annuncio['prezzo_vendita']; ?>" required>
            </div>
            <div>
                <label for="luogo">Luogo</label>
                <input type="text" name="luogo" id="luogo"
                       value="<?php echo htmlspecialchars($annuncio['luogo']); ?>" required>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="ora">Ora</label>
            <input type="time" name="ora" id="ora"
                   value="<?php echo $annuncio['ora']; ?>" required>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label>Condizioni del libro</label>
            <div class="select-custom-wrapper" id="condizioniWrapper">
                <div class="select-trigger" onclick="toggleCondizioni()">
                    <span id="selectedCondizione"><?php echo $condizioneAttuale; ?></span>
                    <span style="font-size: 10px; color: #9c6b3c;">▼</span>
                </div>
                <div class="select-options-cond" id="condizioniOptions">
                    <div onclick="selectCondizione('Nuovo (Mai aperto)', 'Nuovo')">Nuovo (Mai aperto)</div>
                    <div onclick="selectCondizione('Ottime condizioni', 'Ottime')">Ottime condizioni</div>
                    <div onclick="selectCondizione('Buone condizioni', 'Buone')">Buone condizioni</div>
                    <div onclick="selectCondizione('Usato / Rovinato', 'Usato')">Usato / Rovinato</div>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label>Stato Annuncio</label>
            <div class="select-stato-wrapper" id="statoWrapper">
                <div class="select-trigger" onclick="toggleStato()">
                    <span id="selectedStato"><?php echo $annuncio['stato']; ?></span>
                    <span style="font-size: 10px; color: #9c6b3c;">▼</span>
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
    function toggleCondizioni() {
        const o = document.getElementById('condizioniOptions');
        o.style.display = o.style.display === 'block' ? 'none' : 'block';
    }

    function selectCondizione(label, valore) {
        document.getElementById('selectedCondizione').innerText = label;
        document.getElementById('condizioni_hidden').value = valore;
        document.getElementById('condizioniOptions').style.display = 'none';
    }

    function toggleStato() {
        const o = document.getElementById('statoOptions');
        o.style.display = o.style.display === 'block' ? 'none' : 'block';
    }

    function selectStato(valore) {
        document.getElementById('selectedStato').innerText = valore;
        document.getElementById('stato_hidden').value = valore;
        document.getElementById('statoOptions').style.display = 'none';
    }

    document.addEventListener('click', function(e) {
        if (!document.getElementById('search').contains(e.target)) {
            document.getElementById('custom_results').style.display = 'none';
        }
        if (!document.getElementById('condizioniWrapper').contains(e.target)) {
            document.getElementById('condizioniOptions').style.display = 'none';
        }
        if (!document.getElementById('statoWrapper').contains(e.target)) {
            document.getElementById('statoOptions').style.display = 'none';
        }
    });
</script>