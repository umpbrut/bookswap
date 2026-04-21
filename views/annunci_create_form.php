<?php defined('APP') or die('Accesso Negato'); ?>

<style>
    /* Tema Scuro Generale */
    .annuncio-card {
        background: #121212;
        color: #e0e0e0;
        padding: 2rem;
        border-radius: 15px;
        max-width: 600px;
        margin: auto;
        font-family: 'Segoe UI', sans-serif;
    }

    .annuncio-card h2 {
        text-align: center;
        color: #fff;
        font-family: 'Georgia', serif;
        font-size: 2.5rem;
        margin-bottom: 2rem;
    }

    label {
        display: block;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #a89880;
        margin-bottom: 0.5rem;
    }

    input {
        width: 100%;
        background: #1a1a1a;
        border: 1px solid #333;
        color: #fff;
        padding: 12px;
        border-radius: 8px;
        box-sizing: border-box;
    }

    /* SISTEMAZIONE TEMA RICERCA (Box Bianco con Freccetta) */
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
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
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

    /* Selettore Condizioni */
    .select-custom-wrapper { position: relative; cursor: pointer; }
    .select-trigger {
        background: #1a1a1a;
        border: 1px solid #333;
        padding: 12px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .select-options-cond {
        position: absolute;
        width: 100%;
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 8px;
        z-index: 100;
        display: none;
        margin-top: 5px;
    }
    .select-options-cond div { padding: 10px; border-bottom: 1px solid #333; }
    .select-options-cond div:hover { background: #252525; }

    /* Upload Immagini */
    .upload-container {
        border: 2px dashed #333;
        padding: 20px;
        text-align: center;
        border-radius: 10px;
        cursor: pointer;
        margin-top: 10px;
    }
    .preview-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 10px; }
    .preview-item { position: relative; height: 80px; }
    .preview-item img { width: 100%; height: 100%; object-fit: cover; border-radius: 5px; }
    .remove-img { position: absolute; top: -5px; right: -5px; background: red; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; font-size: 12px; }

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

    .form-grid {
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 20px; 
        margin-bottom: 1.5rem;
    }
</style>

<div class="annuncio-card">
    <h2>Pubblica Annuncio</h2>

    <form action="index.php?page=annunci&action=store" method="post" enctype="multipart/form-data" id="publishForm">
        <input type="hidden" name="id_libro" id="id_libro_hidden">
        <input type="hidden" name="condizioni" id="condizioni_hidden" value="Nuovo (Mai aperto)">
        
        <div style="margin-bottom: 1.5rem;" class="search-container">
            <label>Titolo del Libro</label>
            <input type="text" id="search" oninput="get_libri()" placeholder="Titolo " autocomplete="off">
            <div id="custom_results"></div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label>ISBN</label>
            <input type="text" name="isbn" id="isbn_input" placeholder="Codice ISBN">
        </div>

        <div class="form-grid">
            <div>
                <label>Prezzo (€)</label>
                <input type="number" step="0.01" name="prezzo" id="prezzoInput" value="0.00" min="0" required>
            </div>
            <div>
                <label>Luogo di Scambio</label>
                <input type="text" name="luogo" placeholder="Es. Biblioteca" required>
            </div>
        </div>

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

        <div style="margin-bottom: 1.5rem;">
            <label>Condizioni del libro</label>
            <div class="select-custom-wrapper" id="condizioniWrapper">
                <div class="select-trigger" onclick="toggleCondizioni()">
                    <span id="selectedCondizione">Nuovo (Mai aperto)</span>
                    <span style="font-size: 10px; color: #9c6b3c;">▼</span>
                </div>
                <div class="select-options-cond" id="condizioniOptions">
                    <div onclick="selectCondizione('Nuovo (Mai aperto)')">Nuovo (Mai aperto)</div>
                    <div onclick="selectCondizione('Ottime condizioni')">Ottime condizioni</div>
                    <div onclick="selectCondizione('Buone condizioni')">Buone condizioni</div>
                    <div onclick="selectCondizione('Usato / Rovinato')">Usato / Rovinato</div>
                </div>
            </div>
        </div>

        <label>Immagini (Max 3)</label>
        <div class="upload-container" onclick="document.getElementById('fileInput').click()">
            <span style="color: #a89880; font-size: 0.9rem;">Aggiungi fino a 3 immagini<br><small>Clicca qui</small></span>
            <input type="file" id="fileInput" name="foto[]" multiple accept="image/*" style="display:none" onchange="previewImages(this.files)">
        </div>
        <div id="previewGrid" class="preview-grid"></div>

        <button type="submit" class="btn-submit">Pubblica Annuncio</button>
    </form>
</div>

<script>
    function get_libri() {
        let cerca = document.getElementById('search').value;
        let container = document.getElementById('custom_results');
        let hiddenInput = document.getElementById('id_libro_hidden');
        let isbnInput = document.getElementById('isbn_input');

        if (cerca.length < 2) { 
            container.style.display = 'none'; 
            hiddenInput.value = "";
            return; 
        }

        fetch("libri.php?get_libri&testo=" + encodeURIComponent(cerca))
        .then(res => res.json())
        .then(data => {
            container.innerHTML = "";
            if (data.length > 0) {
                container.style.display = 'block';
                data.forEach(riga => {
                    let item = document.createElement('div');
                    let titolo = riga.Titolo || riga.titolo;
                    let isbn = riga.ISBN || riga.isbn;
                    let id = riga.ID_Libro || riga.id_libro;
                    item.innerText = titolo;
                    item.onclick = function() {
                        document.getElementById('search').value = titolo;
                        hiddenInput.value = id;
                        if(isbnInput) isbnInput.value = isbn || "";
                        container.style.display = 'none';
                    };
                    container.appendChild(item);
                });
            } else { container.style.display = 'none'; }
        });
    }

    function toggleCondizioni() {
        const o = document.getElementById('condizioniOptions');
        o.style.display = o.style.display === 'block' ? 'none' : 'block';
    }

    function selectCondizione(val) {
        document.getElementById('selectedCondizione').innerText = val;
        document.getElementById('condizioni_hidden').value = val;
        document.getElementById('condizioniOptions').style.display = 'none';
    }

    let imagesArray = [];
    function previewImages(files) {
        const grid = document.getElementById('previewGrid');
        if (imagesArray.length + files.length > 3) { alert("Max 3 immagini!"); return; }
        Array.from(files).forEach(file => {
            imagesArray.push(file);
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `<img src="${e.target.result}"><button type="button" class="remove-img" onclick="removeImg(${imagesArray.length-1})">✕</button>`;
                grid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeImg(index) {
        imagesArray.splice(index, 1);
        document.getElementById('previewGrid').innerHTML = '';
        const currentFiles = [...imagesArray];
        imagesArray = [];
        previewImages(currentFiles);
    }

    document.addEventListener('click', function(e) {
        if (!document.getElementById('search').contains(e.target)) {
            document.getElementById('custom_results').style.display = 'none';
        }
        if (!document.getElementById('condizioniWrapper').contains(e.target)) {
            document.getElementById('condizioniOptions').style.display = 'none';
        }
    });
</script>