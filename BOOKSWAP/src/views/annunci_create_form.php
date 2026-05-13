<?php defined('APP') or die('Accesso Negato'); ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600&family=Lato:ital,wght@0,300;0,400;0,700;1,300;1,400&display=swap" rel="stylesheet">
<style>
    @keyframes scrollBackground {
        from { background-position: 0 0; }
        to { background-position: -2000px 0; }
    }

    body {
        font-family: 'Lato', sans-serif;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
            url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=3000');
        background-size: auto 100%;
        background-repeat: repeat-x;
        min-height: 100vh;
        margin: 0;
        animation: scrollBackground 80s linear infinite;
    }

    .annuncio-card {
        background: #121212;
        color: #e0e0e0;
        border-radius: 15px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }

    .annuncio-card h2 {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 300;
        font-size: 2.2rem;
        letter-spacing: 1px;
        color: #fff;
    }

    .form-label {
        font-family: 'DM Sans', sans-serif;
        text-transform: uppercase;
        font-size: 0.68rem;
        letter-spacing: 2px;
        color: #a89880;
    }

    .form-control, .form-select {
        background: #1a1a1a;
        border: 1px solid #333;
        color: #fff;
        font-family: 'Lato', sans-serif;
        font-size: 0.9rem;
        border-radius: 8px;
    }

    .form-control:focus, .form-select:focus {
        background: #1a1a1a;
        border-color: #9c6b3c;
        color: #fff;
        box-shadow: 0 0 0 3px rgba(156,107,60,0.2);
    }

    .form-control::placeholder { color: #555; }

    /* dropdown risultati ricerca libro */
    #custom_results {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        width: 100%;
        background: white;
        border-radius: 8px;
        z-index: 1000;
        display: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    }

    #custom_results::before {
        content: "";
        position: absolute;
        bottom: 100%; left: 20px;
        border: 8px solid transparent;
        border-bottom-color: white;
    }

    #custom_results div {
        color: #333;
        padding: 10px 18px;
        cursor: pointer;
        font-family: 'Lato', sans-serif;
        font-size: 0.88rem;
        border-bottom: 1px solid #eee;
    }

    #custom_results div:last-child { border-bottom: none; }
    #custom_results div:hover { background: #f5f0ea; }

    /* select condizioni custom */
    .select-custom-wrapper { position: relative; cursor: pointer; }
    .select-trigger {
        background: #1a1a1a;
        border: 1px solid #333;
        padding: 10px 14px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: 'Lato', sans-serif;
        font-size: 0.9rem;
        color: #fff;
    }
    .select-options-cond {
        position: absolute;
        width: 100%;
        background: #1e1e1e;
        border: 1px solid #333;
        border-radius: 8px;
        z-index: 100;
        display: none;
        margin-top: 4px;
        overflow: hidden;
    }
    .select-options-cond div {
        padding: 10px 14px;
        border-bottom: 1px solid #2a2a2a;
        font-family: 'Lato', sans-serif;
        font-size: 0.88rem;
        color: #e0e0e0;
    }
    .select-options-cond div:last-child { border-bottom: none; }
    .select-options-cond div:hover { background: #2a2a2a; }

    /* upload immagini */
    .upload-container {
        border: 2px dashed #333;
        padding: 20px;
        text-align: center;
        border-radius: 10px;
        cursor: pointer;
        font-family: 'Lato', sans-serif;
        color: #a89880;
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }
    .upload-container:hover { border-color: #9c6b3c; }

    .preview-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 10px; }
    .preview-item { position: relative; height: 80px; }
    .preview-item img { width: 100%; height: 100%; object-fit: cover; border-radius: 5px; }
    .remove-img {
        position: absolute; top: -5px; right: -5px;
        background: #c0392b; color: white;
        border: none; border-radius: 50%;
        width: 20px; height: 20px;
        cursor: pointer; font-size: 11px;
    }

    .btn-submit {
        background: #b58d5b;
        color: white;
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-submit:hover { background: #9c6b3c; }
</style>

<<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="annuncio-card p-4 p-md-5 w-100" style="max-width: 620px;">

        <h2 class="text-center mb-4">Pubblica Annuncio</h2>

        <form action="index.php?page=annunci&action=store" method="post" enctype="multipart/form-data" id="publishForm">
            <input type="hidden" name="id_libro" id="id_libro_hidden">
            <input type="hidden" name="condizioni" id="condizioni_hidden" value="Nuovo (Mai aperto)">

            <!-- Titolo libro -->
            <div class="mb-3 position-relative search-container">
                <label class="form-label">Titolo del Libro</label>
                <input type="text" id="search" class="form-control"
                       oninput="get_libri()" placeholder="Cerca titolo..." autocomplete="off">
                <div id="custom_results"></div>
            </div>

            <!-- ISBN -->
            <div class="mb-3">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" id="isbn_input" class="form-control" placeholder="Codice ISBN">
            </div>

            <!-- Prezzo e Luogo -->
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label">Prezzo (€)</label>
                    <input type="number" step="0.01" name="prezzo_vendita" id="prezzoInput"
                           class="form-control" value="0.00" min="0" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Luogo di Scambio</label>
                    <input type="text" name="luogo" class="form-control" placeholder="Es. Biblioteca" required>
                </div>
            </div>

            <!-- Data e Ora -->
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label">Data</label>
                    <input type="date" name="data" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Ora</label>
                    <input type="time" name="ora" class="form-control" value="<?= date('H:i') ?>" required>
                </div>
            </div>

            <!-- Condizioni -->
            <div class="mb-3">
                <label class="form-label">Condizioni del libro</label>
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

            <!-- Immagini -->
            <div class="mb-4">
                <label class="form-label">Immagini (Max 3)</label>
                <div class="upload-container" onclick="document.getElementById('fileInput').click()">
                    Aggiungi fino a 3 immagini<br><small>Clicca qui</small>
                    <input type="file" id="fileInput" name="foto[]" multiple accept="image/*"
                           style="display:none" onchange="previewImages(this.files)">
                </div>
                <div id="previewGrid" class="preview-grid"></div>
            </div>

            <button type="submit" class="btn-submit">Pubblica Annuncio</button>
        </form>

    </div>
</div>

<script>
    // Logica JavaScript rimasta intatta
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