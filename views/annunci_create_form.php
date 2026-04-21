<?php defined('APP') or die('Accesso Negato'); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookSwap | Nuovo Annuncio</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        /* Reset & Base */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(rgba(15,10,8,0.72), rgba(15,10,8,0.72)), 
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=3000');
            background-size: auto 110%;
            animation: driftBg 100s linear infinite;
        }
        @keyframes driftBg { from { background-position: 0 center; } to { background-position: -2400px center; } }

        /* Navbar */
        .bs-nav {
            width: 100%; display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 2.5rem; background: rgba(10,7,5,0.6); backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(156,107,60,0.18); position: sticky; top: 0; z-index: 100;
        }
        .bs-nav-brand { font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: #f5f0e8; text-decoration: none; letter-spacing: 1px; }
        .bs-nav-brand span { color: #c4935a; }
        .bs-nav-links { display: flex; gap: 0.3rem; list-style: none; }
        .bs-nav-links a {
            text-decoration: none; font-size: 0.78rem; letter-spacing: 1.5px; text-transform: uppercase;
            color: #a89880; padding: 0.45rem 0.9rem; border-radius: 6px; transition: 0.25s;
        }
        .bs-nav-links a:hover, .bs-nav-links a.active { border: 1px solid rgba(156,107,60,0.3); background: rgba(156,107,60,0.1); color: #f5f0e8; }

        /* Card & Forms */
        .page-body { flex: 1; display: flex; align-items: center; padding: 2rem; width: 100%; justify-content: center; }
        .annuncio-card {
            background: rgba(12, 9, 7, 0.88); backdrop-filter: blur(22px); border-radius: 18px;
            border: 1px solid rgba(156,107,60,0.22); padding: 3rem; width: 100%; max-width: 580px;
            position: relative; animation: fadeUp 0.7s ease-out;
        }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .annuncio-title { font-family: 'Cormorant Garamond', serif; font-size: 2.3rem; color: #f5f0e8; margin-bottom: 2rem; }
        .ann-field { margin-bottom: 1.3rem; position: relative; }
        .ann-field label { display: block; font-size: 0.68rem; letter-spacing: 2.5px; text-transform: uppercase; color: #a89880; margin-bottom: 0.5rem; }
        
        .ann-field input, .ann-select-face {
            width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(156,107,60,0.25);
            border-radius: 8px; padding: 0.75rem 1rem; color: #f0e8dc; outline: none; transition: 0.3s;
        }
        .ann-field input:focus { border-color: #9c6b3c; background: rgba(156,107,60,0.1); }

        /* Gestione Prezzo con Freccette a Tema */
        .price-input-wrapper { position: relative; display: flex; align-items: center; }
        .price-input-wrapper input { padding-right: 2.5rem; }
        
        /* Rimuove freccette default */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        .price-arrows {
            position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
            display: flex; flex-direction: column; gap: 2px;
        }
        .price-btn {
            background: none; border: none; color: #c4935a; cursor: pointer;
            font-size: 0.6rem; line-height: 1; padding: 2px 5px; border-radius: 3px;
            transition: 0.2s; display: flex; align-items: center; justify-content: center;
        }
        .price-btn:hover { background: rgba(156,107,60,0.2); color: #f5f0e8; }

        /* Dropdown */
        .book-search-wrap { position: relative; }
        .book-dropdown {
            position: absolute; top: 100%; left: 0; right: 0; background: rgba(18,12,8,0.98);
            border: 1px solid rgba(156,107,60,0.3); border-radius: 8px; z-index: 1000; display: none;
            max-height: 250px; overflow-y: auto;
        }
        .book-dropdown.open { display: block; }
        .book-dropdown-item { padding: 0.7rem 1rem; color: #c8bfb0; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .book-dropdown-item:hover { background: rgba(156,107,60,0.15); color: #f5f0e8; }

        /* Upload Area Centrata */
        .upload-container { width: 100%; display: flex; flex-direction: column; gap: 15px; margin-top: 0.5rem; }
        .btn-add-img {
            width: 100%; height: 100px; border: 2px dashed rgba(156,107,60,0.4);
            border-radius: 12px; display: flex; flex-direction: column; 
            align-items: center; justify-content: center;
            color: #c4935a; cursor: pointer; transition: 0.3s; background: rgba(156,107,60,0.05);
            text-align: center;
        }
        .btn-add-img.hidden { display: none; }
        .btn-add-img span { font-size: 2.2rem; line-height: 1; }
        .btn-add-img p { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; margin-top: 5px; }
        
        #img-preview-wrap { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .preview-box { position: relative; width: 100%; aspect-ratio: 1/1; }
        .img-preview-item { width: 100%; height: 100%; border-radius: 10px; object-fit: cover; border: 1px solid rgba(156,107,60,0.3); }
        .remove-img {
            position: absolute; top: -5px; right: -5px; background: #e89090; color: white;
            border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center;
            justify-content: center; font-size: 12px; cursor: pointer; border: 2px solid #0c0907; font-weight: bold;
        }

        #limit-msg { color: #c4935a; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; display: none; margin-bottom: 10px; }

        /* Layout Grid */
        .ann-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .btn-pubblica {
            width: 100%; margin-top: 1.5rem; padding: 1rem; background: linear-gradient(135deg, #9c6b3c, #c4935a);
            border: none; border-radius: 8px; color: #f5f0e8; letter-spacing: 3px; text-transform: uppercase; cursor: pointer;
        }

        .ann-select-menu {
            position: absolute; top: 100%; left: 0; right: 0; background: rgba(18,12,8,0.98);
            border: 1px solid rgba(156,107,60,0.3); border-radius: 8px; z-index: 1000; display: none;
        }
        .ann-select-menu.open { display: block; }
        .ann-select-opt { padding: 0.7rem 1rem; color: #c8bfb0; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.05); }
        input[type="date"], input[type="time"] { color-scheme: dark; }
    </style>
</head>
<body>

<nav class="bs-nav">
    <a href="index.php" class="bs-nav-brand">Book<span>Swap</span></a>
    <ul class="bs-nav-links">
        <li><a href="index.php?page=annunci">📋 Annunci</a></li>
        <li><a href="index.php?page=annunci&action=my" class="active">📌 I miei annunci</a></li>
        <li><a href="index.php?page=annunci&action=create">✏️ Nuovo</a></li>
    </ul>
</nav>

<div class="page-body">
    <div class="annuncio-card">
        <h2 class="annuncio-title">Pubblica il libro</h2>

        <form action="index.php?page=annunci&action=store" method="post" enctype="multipart/form-data" id="annuncio-form">
            <input type="hidden" name="id_libro" id="id_libro_hidden">

            <div class="ann-field">
                <label>Libro</label>
                <div class="book-search-wrap">
                    <input type="text" id="search" oninput="get_libri()" onfocus="get_libri()" placeholder="Cerca per titolo..." autocomplete="off">
                    <div class="book-dropdown" id="book-dropdown"></div>
                </div>
            </div>

            <div class="ann-row">
                <div class="ann-field">
                    <label>ISBN</label>
                    <input type="text" name="isbn" id="isbn" placeholder="978...">
                </div>
                <div class="ann-field">
                    <label>Prezzo (€)</label>
                    <div class="price-input-wrapper">
                        <input type="number" step="0.50" name="prezzo" id="prezzo" placeholder="0.00" value="0.00">
                        <div class="price-arrows">
                            <button type="button" class="price-btn" onclick="stepPrice(1)">▲</button>
                            <button type="button" class="price-btn" onclick="stepPrice(-1)">▼</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ann-row">
                <div class="ann-field"><label>Data</label><input type="date" name="data" value="<?=date('Y-m-d')?>"></div>
                <div class="ann-field"><label>Ora</label><input type="time" name="ora" value="<?=date('H:i')?>"></div>
            </div>

            <div class="ann-field">
                <label>Condizioni</label>
                <div class="ann-select-wrap">
                    <div class="ann-select-face" id="sel-face" tabindex="0"><span id="sel-text">Seleziona stato...</span> ▾</div>
                    <div class="ann-select-menu" id="sel-menu">
                        <div class="ann-select-opt" data-val="Nuovo">Nuovo</div>
                        <div class="ann-select-opt" data-val="Ottime">Ottime</div>
                        <div class="ann-select-opt" data-val="Buone">Buone</div>
                        <div class="ann-select-opt" data-val="Usato">Usato</div>
                    </div>
                    <select name="condizioni" id="condizioni" style="display:none">
                        <option value="">Seleziona...</option>
                        <option value="Nuovo">Nuovo</option>
                        <option value="Ottime">Ottime</option>
                        <option value="Buone">Buone</option>
                        <option value="Usato">Usato</option>
                    </select>
                </div>
            </div>

            <div class="ann-field">
                <label>Immagini (Max 3)</label>
                <div class="upload-container">
                    <div id="limit-msg">Limite raggiunto</div>
                    <div id="img-preview-wrap"></div>
                    <label for="img-input" class="btn-add-img" id="label-add-img">
                        <span>+</span>
                        <p>Clicca per caricare foto</p>
                    </label>
                    <input type="file" id="img-input" multiple accept="image/*" style="display:none">
                </div>
            </div>

            <button type="submit" class="btn-pubblica">Pubblica Annuncio</button>
        </form>
    </div>
</div>

<script>
    // Funzione per le freccette del prezzo
    function stepPrice(direction) {
        const input = document.getElementById('prezzo');
        let val = parseFloat(input.value) || 0;
        let step = parseFloat(input.step) || 0.50;
        let newVal = val + (direction * step);
        input.value = newVal < 0 ? "0.00" : newVal.toFixed(2);
    }

    // Gestione Immagini
    const imgInput = document.getElementById('img-input');
    const previewWrap = document.getElementById('img-preview-wrap');
    const labelAdd = document.getElementById('label-add-img');
    const limitMsg = document.getElementById('limit-msg');
    let selectedFiles = [];

    imgInput.onchange = function(e) {
        const newFiles = Array.from(e.target.files);
        const totalPotential = selectedFiles.length + newFiles.length;
        if (totalPotential >= 3) {
            const spaceLeft = 3 - selectedFiles.length;
            if (spaceLeft > 0) selectedFiles = [...selectedFiles, ...newFiles.slice(0, spaceLeft)];
            labelAdd.classList.add('hidden');
            limitMsg.style.display = "block";
        } else {
            selectedFiles = [...selectedFiles, ...newFiles];
        }
        renderPreviews();
        this.value = ""; 
    };

    function renderPreviews() {
        previewWrap.innerHTML = "";
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const box = document.createElement('div');
                box.className = 'preview-box';
                box.innerHTML = `<img src="${e.target.result}" class="img-preview-item"><div class="remove-img" onclick="removeImage(${index})">×</div>`;
                previewWrap.appendChild(box);
            };
            reader.readAsDataURL(file);
        });
        if(selectedFiles.length < 3) { labelAdd.classList.remove('hidden'); limitMsg.style.display = "none"; }
    }

    window.removeImage = function(index) {
        selectedFiles.splice(index, 1);
        renderPreviews();
    };

    document.getElementById('annuncio-form').onsubmit = function() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        imgInput.files = dt.files;
    };

    /* Ricerca Libri */
    const searchInput = document.getElementById('search');
    const isbnInput = document.getElementById('isbn');
    const hiddenId = document.getElementById('id_libro_hidden');
    const dropdown = document.getElementById('book-dropdown');

    function get_libri() {
        let cerca = searchInput.value;
        if (!cerca) { dropdown.classList.remove('open'); return; }
        fetch("libri.php?get_libri&testo=" + encodeURIComponent(cerca))
        .then(res => res.json())
        .then(data => {
            dropdown.innerHTML = "";
            data.forEach(r => {
                let i = document.createElement('div');
                i.className = 'book-dropdown-item'; i.textContent = r.titolo;
                i.onclick = () => { 
                    searchInput.value = r.titolo; 
                    hiddenId.value = r.id_libro; 
                    isbnInput.value = r.ISBN || ""; 
                    dropdown.classList.remove('open'); 
                };
                dropdown.appendChild(i);
            });
            dropdown.classList.toggle('open', data.length > 0);
        });
    }

    document.addEventListener('click', e => {
        if (!e.target.closest('.book-search-wrap')) dropdown.classList.remove('open');
        if (!e.target.closest('.ann-select-wrap')) document.getElementById('sel-menu').classList.remove('open');
    });

    document.getElementById('sel-face').onclick = () => document.getElementById('sel-menu').classList.toggle('open');
    document.querySelectorAll('.ann-select-opt').forEach(o => {
        o.onclick = function() {
            document.getElementById('sel-text').textContent = this.textContent;
            document.getElementById('condizioni').value = this.dataset.val;
            document.getElementById('sel-menu').classList.remove('open');
        };
    });
</script>
</body>
</html>