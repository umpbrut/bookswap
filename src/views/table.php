<?php defined('APP') or die('Accesso Negato') ?>
<style>
    /* BARRA RICERCA */
    .search-wrap {
        position: relative; max-width: 480px; margin-bottom: 20px;
    }
    .search-wrap input {
        width: 100%; padding: 12px 16px 12px 42px;
        border: 1px solid var(--border); border-radius: 6px;
        background: white; color: var(--ink);
        font-family: 'DM Sans', sans-serif; font-size: 0.9rem;
        outline: none; transition: border-color 0.3s;
    }
    .search-wrap input:focus { border-color: var(--gold); }
    .search-wrap input::placeholder { color: var(--muted); }
    .search-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--muted); font-size: 15px; pointer-events: none;
    }

    /* FORM FILTRI */
    .filtri-form {
        display: flex; flex-wrap: wrap; gap: 12px;
        align-items: flex-end; margin-bottom: 32px;
    }
    .filtri-form label {
        display: block; font-size: 0.6rem; letter-spacing: 2px;
        text-transform: uppercase; color: var(--muted); margin-bottom: 5px;
    }
    .filtri-form select,
    .filtri-form input[type="number"] {
        padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px;
        background: white; color: var(--ink);
        font-family: 'DM Sans', sans-serif; font-size: 0.85rem;
        outline: none; transition: border-color 0.3s;
    }
    .filtri-form select:focus,
    .filtri-form input[type="number"]:focus { border-color: var(--gold); }
    .filtri-form input[type="number"] { width: 120px; }
    .btn-filtra {
        padding: 10px 22px; background: var(--gold); color: white;
        border: none; border-radius: 6px; cursor: pointer;
        font-family: 'DM Sans', sans-serif; font-size: 0.75rem;
        letter-spacing: 2px; text-transform: uppercase; transition: background 0.2s;
    }
    .btn-filtra:hover { background: var(--gold2); }
    .btn-azzera {
        padding: 10px 18px; background: transparent; color: var(--muted);
        border: 1px solid var(--border); border-radius: 6px; cursor: pointer;
        font-family: 'DM Sans', sans-serif; font-size: 0.75rem;
        letter-spacing: 2px; text-transform: uppercase;
        text-decoration: none; transition: all 0.2s;
    }
    .btn-azzera:hover { border-color: var(--gold); color: var(--gold); }

    /* GRIGLIA CARD */
    .annunci-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 24px;
    }
    .book-card {
        background: white; border: 1px solid rgba(60,40,20,0.08);
        border-radius: 10px; overflow: hidden;
        box-shadow: var(--shadow);
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
        cursor: pointer;
    }
    .book-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(60,40,20,0.14); }
    .heart-link {
        position: absolute; top: 10px; right: 10px; z-index: 10;
        width: 32px; height: 32px; border-radius: 50%;
        background: rgba(255,255,255,0.92);
        border: 1px solid rgba(60,40,20,0.12);
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; font-size: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        transition: transform 0.2s;
    }
    .heart-link:hover { transform: scale(1.15); }
    .card-img-placeholder {
        width: 100%; aspect-ratio: 3/4;
        background: linear-gradient(135deg, var(--bg2), var(--bg3));
        display: flex; align-items: center; justify-content: center;
        font-family: 'Cormorant Garamond', serif; font-size: 3rem;
        color: var(--gold); opacity: 0.45;
    }
    .card-body { padding: 16px 18px 20px; }
    .card-materia { font-size: 0.58rem; letter-spacing: 3px; text-transform: uppercase; color: var(--gold); margin-bottom: 5px; }
    .card-title { font-family: 'Cormorant Garamond', serif; font-size: 1.15rem; font-weight: 400; line-height: 1.3; margin-bottom: 4px; }
    .card-autore { font-size: 0.76rem; color: var(--muted); margin-bottom: 14px; }
    .card-footer { display: flex; align-items: center; justify-content: space-between; }
    .card-price { font-size: 1.1rem; font-weight: 500; color: var(--gold); }
    .card-cond {
        font-size: 0.58rem; letter-spacing: 1.5px; text-transform: uppercase;
        padding: 4px 10px; border-radius: 20px;
        background: var(--bg2); color: var(--muted); border: 1px solid var(--border);
    }
    .empty { text-align: center; padding: 60px; color: var(--muted); font-size: 0.9rem; grid-column: 1/-1; }
    .nessun-risultato { display: none; text-align: center; padding: 60px; color: var(--muted); font-size: 0.9rem; grid-column: 1/-1; }

    /* ===================== MODAL ===================== */
    .modal-overlay {
        display: none;
        position: fixed; inset: 0; z-index: 2000;
        background: rgba(30,18,8,0.72);
        backdrop-filter: blur(4px);
        align-items: center; justify-content: center;
        padding: 20px;
    }
    .modal-overlay.aperta { display: flex; }

    .modal-box {
        background: var(--bg);
        border-radius: 14px;
        max-width: 860px; width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 32px 80px rgba(30,18,8,0.35);
        display: flex; flex-direction: column;
        animation: modalIn 0.28s cubic-bezier(.22,.68,0,1.2);
    }
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(28px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-inner {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }
    @media (max-width: 620px) {
        .modal-inner { grid-template-columns: 1fr; }
    }

    /* Colonna sinistra: galleria */
    .modal-gallery {
        padding: 28px 20px 28px 28px;
        display: flex; flex-direction: column; gap: 10px;
    }
    .gallery-main {
        width: 100%; aspect-ratio: 3/4;
        background: linear-gradient(135deg, var(--bg2), var(--bg3));
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Cormorant Garamond', serif; font-size: 5rem;
        color: var(--gold); opacity: 0.5;
        overflow: hidden; position: relative;
        flex-shrink: 0;
    }
    .gallery-arrow {
        position: absolute; top: 50%; transform: translateY(-50%);
        background: rgba(255,255,255,0.88);
        border: 1px solid var(--border);
        border-radius: 50%; width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 18px; color: var(--gold);
        transition: background 0.2s;
        z-index: 5; user-select: none;
    }
    .gallery-arrow:hover { background: white; }
    .gallery-arrow.prev { left: 10px; }
    .gallery-arrow.next { right: 10px; }

    .gallery-thumbs {
        display: flex; gap: 8px; flex-wrap: wrap;
    }
    .gallery-thumb {
        width: calc(33.33% - 6px); aspect-ratio: 1/1;
        background: linear-gradient(135deg, var(--bg2), var(--bg3));
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Cormorant Garamond', serif; font-size: 1.6rem;
        color: var(--gold); opacity: 0.45;
        cursor: pointer; border: 2px solid transparent;
        transition: border-color 0.2s, opacity 0.2s;
        overflow: hidden;
    }
    .gallery-thumb.attiva { border-color: var(--gold); opacity: 1; }
    .gallery-thumb:hover { opacity: 0.85; }

    /* Colonna destra: dettagli */
    .modal-dettagli {
        padding: 28px 28px 28px 20px;
        display: flex; flex-direction: column; gap: 0;
        border-left: 1px solid var(--border);
    }
    .modal-chiudi {
        align-self: flex-end;
        background: none; border: none; cursor: pointer;
        font-size: 1.4rem; color: var(--muted);
        line-height: 1; padding: 0 0 16px 0;
        transition: color 0.2s;
    }
    .modal-chiudi:hover { color: var(--gold); }
    .modal-materia { font-size: 0.58rem; letter-spacing: 3px; text-transform: uppercase; color: var(--gold); margin-bottom: 6px; }
    .modal-titolo {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.75rem; font-weight: 400; line-height: 1.25;
        margin-bottom: 4px;
    }
    .modal-autore { font-size: 0.82rem; color: var(--muted); margin-bottom: 24px; }
    .modal-divider { height: 1px; background: var(--border); margin: 0 0 20px; }
    .modal-info-riga {
        display: flex; align-items: baseline; justify-content: space-between;
        margin-bottom: 14px;
    }
    .modal-info-label { font-size: 0.62rem; letter-spacing: 2px; text-transform: uppercase; color: var(--muted); }
    .modal-info-val { font-size: 0.92rem; color: var(--ink); font-weight: 500; text-align: right; }
    .modal-prezzo-big { font-size: 2rem; font-weight: 600; color: var(--gold); margin: 20px 0 8px; }
    .modal-cond-badge {
        display: inline-block;
        font-size: 0.62rem; letter-spacing: 1.5px; text-transform: uppercase;
        padding: 5px 14px; border-radius: 20px;
        background: var(--bg2); color: var(--muted); border: 1px solid var(--border);
        margin-bottom: 28px;
    }
    .modal-btn-preferiti {
        display: block; width: 100%;
        padding: 13px; background: var(--gold); color: white;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase;
        text-align: center; text-decoration: none; border-radius: 6px;
        transition: background 0.2s; margin-top: auto;
    }
    .modal-btn-preferiti:hover { background: var(--gold2); }
    .modal-btn-preferiti.nascosto { display: none; }
</style>

<!-- RICERCA TESTO (JS, filtra le card già in pagina) -->
<div class="search-wrap">
    <span class="search-icon">&#9906;</span>
    <input type="text" id="search" placeholder="Cerca titolo o autore..." oninput="filtra()">
</div>

<!--
    FILTRI (form GET → controller → model → DB)
    Il controller legge $_GET e chiama selectAll() oppure selectByFiltri()
    I valori rimangono selezionati dopo il submit grazie a $_GET['materia'] ecc.
-->
<form class="filtri-form" method="GET" action="index.php">
    <input type="hidden" name="page" value="annunci">
    <input type="hidden" name="action" value="index">

    <div>
        <label>Materia</label>
        <select name="id_materia">
            <option value="">Tutte</option>
            <?php foreach ($materie as $m): ?>
            <option value="<?= $m['id_materia'] ?>"
                    <?= (($_GET['id_materia'] ?? '') == $m['id_materia']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($m['nome']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label>Classe</label>
        <select name="classe">
            <option value="">Tutte</option>
            <?php
            foreach ([1,2,3,4,5] as $c):
                $sel = (($_GET['classe'] ?? '') == $c) ? 'selected' : '';
            ?>
            <option value="<?= $c ?>" <?= $sel ?>><?= $c ?>ª</option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label>Prezzo max (€)</label>
        <input type="number" name="prezzo_max" min="0" step="0.50"
               placeholder="es. 20"
               value="<?= htmlspecialchars($_GET['prezzo_max'] ?? '') ?>">
    </div>

    <div>
        <label>Condizioni</label>
        <select name="condizioni">
            <option value="">Qualsiasi</option>
            <?php
            $condizioni = ['Nuovo (Mai aperto)','Ottime condizioni','Buone condizioni','Usato / Rovinato'];
            foreach ($condizioni as $c):
                $sel = (($_GET['condizioni'] ?? '') === $c) ? 'selected' : '';
            ?>
            <option value="<?= $c ?>" <?= $sel ?>><?= $c ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn-filtra">Filtra</button>
    <a href="index.php?page=annunci&action=index" class="btn-azzera">Azzera</a>
</form>

<!-- GRIGLIA: dati dal DB via $table -->
<div class="annunci-grid" id="annunci-grid">
    <?php if (!empty($table)): ?>
        <?php foreach ($table as $a): ?>
        <div class="book-card"
             data-titolo="<?= htmlspecialchars(strtolower($a['titolo'] ?? '')) ?>"
             data-autore="<?= htmlspecialchars(strtolower($a['autore'] ?? '')) ?>"
             data-id="<?= $a['id_annuncio'] ?>"
             data-id-libro="<?= $a['id_libro'] ?>"
             data-materia="<?= htmlspecialchars($a['materia'] ?? '') ?>"
             data-titolo-full="<?= htmlspecialchars($a['titolo'] ?? '') ?>"
             data-autore-full="<?= htmlspecialchars($a['autore'] ?? '') ?>"
             data-prezzo="<?= number_format($a['prezzo_vendita'] ?? 0, 2, ',', '.') ?>"
             data-condizioni="<?= htmlspecialchars($a['condizioni'] ?? '') ?>"
             data-data="<?= htmlspecialchars($a['data'] ?? '') ?>"
             data-ora="<?= htmlspecialchars($a['ora'] ?? '') ?>"
             data-luogo="<?= htmlspecialchars($a['luogo'] ?? '') ?>"
             data-stato="<?= htmlspecialchars($a['stato'] ?? 'Disponibile') ?>"
             data-immagini="<?= htmlspecialchars($a['links'] ?? '') ?>"
             onclick="apriModal(this)">

            <?php if (isset($_SESSION['id_utente'])): ?>
            <a href="index.php?page=preferiti&action=store&id_annuncio=<?= $a['id_annuncio'] ?>"
               class="heart-link" title="Aggiungi ai preferiti"
               onclick="event.stopPropagation()">❤️</a>
            <?php endif; ?>

            <?php 
                // Prendiamo la prima immagine del gruppo (se esiste)
                $links = !empty($a['links']) ? explode(',', $a['links']) : [];
                $img1 = !empty($links) ? $links[0] : null;
            ?>

            <div class="card-img-container" style="width:100%; aspect-ratio:3/4; overflow:hidden; background:#f0f0f0;">
                <?php if ($img1): ?>
                    <img src="<?= $img1 ?>" alt="Copertina" style="width:100%; height:100%; object-fit:cover;">
                <?php else: ?>
                    <div class="card-img-placeholder" style="height:100%; display:flex; align-items:center; justify-content:center;">&#9413;</div>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <p class="card-materia"><?= htmlspecialchars($a['materia'] ?? '') ?></p>
                <h3 class="card-title"><?= htmlspecialchars($a['titolo'] ?? '') ?></h3>
                <p class="card-autore"><?= htmlspecialchars($a['autore'] ?? '') ?></p>
                <div class="card-footer">
                    <span class="card-price">€ <?= number_format($a['prezzo_vendita'] ?? 0, 2, ',', '.') ?></span>
                    <span class="card-cond"><?= htmlspecialchars($a['condizioni'] ?? '') ?></span>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
        <p class="nessun-risultato" id="nessun-risultato">Nessun annuncio trovato.</p>
    <?php else: ?>
        <p class="empty">Nessun annuncio disponibile.</p>
    <?php endif; ?>
</div>

<!-- ==================== MODAL DETTAGLIO ==================== -->
<div class="modal-overlay" id="modal-overlay" onclick="chiudiSeOverlay(event)">
    <div class="modal-box" role="dialog" aria-modal="true">
        <div class="modal-inner">

            <!-- GALLERIA: 1 immagine grande + 3 thumbnails sotto -->
            <div class="modal-gallery">
                <div class="gallery-main" id="modal-img-main">
                    <button class="gallery-arrow prev" onclick="cambiaImmagine(-1)">&#8249;</button>
                    <span id="modal-placeholder-icon">&#9413;</span>
                    <button class="gallery-arrow next" onclick="cambiaImmagine(1)">&#8250;</button>
                </div>
                <div class="gallery-thumbs" id="gallery-thumbs">
                    <div class="gallery-thumb attiva" onclick="selezionaThumb(0)">&#9413;</div>
                    <div class="gallery-thumb" onclick="selezionaThumb(1)">&#9413;</div>
                    <div class="gallery-thumb" onclick="selezionaThumb(2)">&#9413;</div>
                </div>
            </div>

            <!-- DETTAGLI -->
            <div class="modal-dettagli">
                <button class="modal-chiudi" onclick="chiudiModal()" aria-label="Chiudi">&#10005;</button>

                <p class="modal-materia" id="modal-materia"></p>
                <h2 class="modal-titolo" id="modal-titolo"></h2>
                <p class="modal-autore" id="modal-autore"></p>

                <div class="modal-divider"></div>

                <div class="modal-info-riga">
                    <span class="modal-info-label">Stato annuncio</span>
                    <span class="modal-info-val" id="modal-stato"></span>
                </div>
                <div class="modal-info-riga">
                    <span class="modal-info-label">Data incontro</span>
                    <span class="modal-info-val" id="modal-data"></span>
                </div>
                <div class="modal-info-riga">
                    <span class="modal-info-label">Ora</span>
                    <span class="modal-info-val" id="modal-ora"></span>
                </div>
                <div class="modal-info-riga">
                    <span class="modal-info-label">Luogo</span>
                    <span class="modal-info-val" id="modal-luogo"></span>
                </div>

                <div class="modal-divider"></div>

                <div class="modal-prezzo-big" id="modal-prezzo"></div>
                <span class="modal-cond-badge" id="modal-condizioni"></span>

                <a href="#" class="modal-btn-preferiti" id="modal-btn-preferiti">&#10084;&#65039; Aggiungi ai preferiti</a>
            </div>

        </div>
    </div>
</div>

<script>
    /* ---- Ricerca testo: logica originale invariata ---- */
    function filtra() {
        let cerca = document.getElementById('search').value.toLowerCase();
        let cards = document.querySelectorAll('.book-card');
        let trovati = 0;

        for (let card of cards) {
            if (card.dataset.titolo.includes(cerca) || card.dataset.autore.includes(cerca)) {
                card.style.display = '';
                trovati++;
            } else {
                card.style.display = 'none';
            }
        }

        let msg = document.getElementById('nessun-risultato');
        if (msg) msg.style.display = trovati === 0 ? 'block' : 'none';
    }

    /* ---- Modal ---- */
    let immagineCorrente = 0;
    const NUM_IMMAGINI = 3; // In futuro si potrebbe passare dal DB
    let elencoImmagini = []; // Variabile globale per le immagini dell'annuncio aperto

    function apriModal(card) {
        // Popola i campi con i data-attribute della card
        document.getElementById('modal-materia').textContent    = card.dataset.materia    || '';
        document.getElementById('modal-titolo').textContent     = card.dataset.titoloFull || '';
        document.getElementById('modal-autore').textContent     = card.dataset.autoreFull || '';
        document.getElementById('modal-prezzo').textContent     = 'EUR ' + card.dataset.prezzo;
        document.getElementById('modal-condizioni').textContent = card.dataset.condizioni  || '';
        document.getElementById('modal-stato').textContent      = card.dataset.stato       || 'Disponibile';
        document.getElementById('modal-data').textContent       = card.dataset.data        || '—';
        document.getElementById('modal-ora').textContent        = card.dataset.ora         || '—';
        document.getElementById('modal-luogo').textContent      = card.dataset.luogo       || '—';

        // Bottone preferiti: visibile solo se utente loggato
        let btnPref = document.getElementById('modal-btn-preferiti');
        let sessione = <?= isset($_SESSION['id_utente']) ? 'true' : 'false' ?>;
        if (sessione) {
            btnPref.classList.remove('nascosto');
            btnPref.href = 'index.php?page=preferiti&action=store&id_annuncio=' + card.dataset.id;
        } else {
            btnPref.classList.add('nascosto');
        }

        // Gestione immagini
        const stringaImmagini = card.dataset.immagini;
        elencoImmagini = stringaImmagini ? stringaImmagini.split(',') : [];
        
        immagineCorrente = 0;
        aggiornaGalleria();

        document.getElementById('modal-overlay').classList.add('aperta');
        document.body.style.overflow = 'hidden';
    }

    function chiudiModal() {
        document.getElementById('modal-overlay').classList.remove('aperta');
        document.body.style.overflow = '';
    }

    function chiudiSeOverlay(e) {
        if (e.target === document.getElementById('modal-overlay')) chiudiModal();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') chiudiModal();
    });

    function cambiaImmagine(dir) {
        immagineCorrente = (immagineCorrente + dir + NUM_IMMAGINI) % NUM_IMMAGINI;
        aggiornaGalleria();
    }

    function selezionaThumb(idx) {
        immagineCorrente = idx;
        aggiornaGalleria();
    }

    function aggiornaGalleria() {
        const mainBox = document.getElementById('modal-img-main');
        const thumbsContainer = document.getElementById('gallery-thumbs');
        const placeholderIcon = document.getElementById('modal-placeholder-icon');

        // Svuota le miniature precedenti
        thumbsContainer.innerHTML = '';

        if (elencoImmagini.length > 0) {
            // Mostra immagine principale
            placeholderIcon.style.display = 'none';
            let imgMain = mainBox.querySelector('.img-fluida');
            if(!imgMain) {
                imgMain = document.createElement('img');
                imgMain.className = 'img-fluida';
                imgMain.style = 'width:100%; height:100%; object-fit:cover;';
                mainBox.appendChild(imgMain);
            }
            imgMain.src = elencoImmagini[immagineCorrente];
            imgMain.style.display = 'block';

            // Crea le miniature
            elencoImmagini.forEach((src, i) => {
                let thumb = document.createElement('div');
                thumb.className = 'gallery-thumb' + (i === immagineCorrente ? ' attiva' : '');
                thumb.innerHTML = `<img src="${src}" style="width:100%; height:100%; object-fit:cover; border-radius:5px;">`;
                thumb.onclick = () => selezionaThumb(i);
                thumbsContainer.appendChild(thumb);
            });
        } else {
            // Nessuna immagine: mostra placeholder
            placeholderIcon.style.display = 'block';
            if(mainBox.querySelector('.img-fluida')) mainBox.querySelector('.img-fluida').style.display = 'none';
        }
    }
</script>

<!-- i data-attribute sulla card servono solo a passare i dati già caricati dal DB alla modal via JavaScript — 
 non c'è nessuna chiamata aggiuntiva al server, i dati vengono dal $table che il controller già fornisce. -->