<?php defined('APP') or die('Accesso Negato') ?>
<style>
    .annunci-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 24px; }
    .book-card { background: white; border: 1px solid rgba(60,40,20,0.08); border-radius: 10px; overflow: hidden; box-shadow: var(--shadow); transition: transform 0.3s, box-shadow 0.3s; cursor: pointer; position: relative; }
    .book-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(60,40,20,0.14); }
    .card-img-wrap { width: 100%; aspect-ratio: 3/4; background: linear-gradient(135deg, var(--bg2), var(--bg3)); display: flex; align-items: center; justify-content: center; font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--gold); opacity: 0.45; overflow: hidden; }
    .card-img-wrap img { width: 100%; height: 100%; object-fit: cover; opacity: 1; }
    .card-body { padding: 16px 18px 20px; }
    .card-materia { font-size: 0.58rem; letter-spacing: 3px; text-transform: uppercase; color: var(--gold); margin-bottom: 5px; }
    .card-title { font-family: 'Cormorant Garamond', serif; font-size: 1.15rem; font-weight: 400; line-height: 1.3; margin-bottom: 4px; }
    .card-autore { font-size: 0.76rem; color: var(--muted); margin-bottom: 14px; }
    .card-footer { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .card-price { font-size: 1.1rem; font-weight: 500; color: var(--gold); }
    .card-cond { font-size: 0.58rem; letter-spacing: 1.5px; text-transform: uppercase; padding: 4px 10px; border-radius: 20px; background: var(--bg2); color: var(--muted); border: 1px solid var(--border); }
    .btn-rimuovi { display: block; width: 100%; padding: 10px; background: #c0392b; color: white; font-family: 'DM Sans', sans-serif; font-size: 0.7rem; letter-spacing: 1px; text-transform: uppercase; text-align: center; text-decoration: none; transition: opacity 0.2s; }
    .btn-rimuovi:hover { opacity: 0.85; }
    .empty { text-align: center; padding: 60px; color: var(--muted); font-size: 0.9rem; }

    /* MODAL */
    .modal-overlay { display: none; position: fixed; inset: 0; z-index: 2000; background: rgba(30,18,8,0.72); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.aperta { display: flex; }
    .modal-box { background: var(--bg); border-radius: 14px; max-width: 860px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 32px 80px rgba(30,18,8,0.35); animation: modalIn 0.28s cubic-bezier(.22,.68,0,1.2); }
    @keyframes modalIn { from { opacity: 0; transform: translateY(28px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .modal-inner { display: grid; grid-template-columns: 1fr 1fr; }
    @media (max-width: 620px) { .modal-inner { grid-template-columns: 1fr; } }
    .modal-gallery { padding: 28px 20px 28px 28px; display: flex; flex-direction: column; gap: 10px; }
    .gallery-main { width: 100%; aspect-ratio: 3/4; background: linear-gradient(135deg, var(--bg2), var(--bg3)); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-family: 'Cormorant Garamond', serif; font-size: 5rem; color: var(--gold); opacity: 0.5; overflow: hidden; position: relative; flex-shrink: 0; }
    .gallery-main img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }
    .gallery-main .placeholder-icon { font-family: 'Cormorant Garamond', serif; font-size: 5rem; color: var(--gold); opacity: 0.5; }
    .gallery-arrow { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.88); border: 1px solid var(--border); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 18px; color: var(--gold); transition: background 0.2s; z-index: 5; user-select: none; }
    .gallery-arrow:hover { background: white; }
    .gallery-arrow.prev { left: 10px; }
    .gallery-arrow.next { right: 10px; }
    .gallery-thumbs { display: flex; gap: 8px; }
    .gallery-thumb { width: calc(33.33% - 6px); aspect-ratio: 1/1; background: linear-gradient(135deg, var(--bg2), var(--bg3)); border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: var(--gold); opacity: 0.4; cursor: pointer; border: 2px solid transparent; transition: border-color 0.2s, opacity 0.2s; overflow: hidden; }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-thumb.attiva { border-color: var(--gold); opacity: 1; }
    .gallery-thumb:hover { opacity: 0.85; }
    .modal-dettagli { padding: 28px 28px 28px 20px; display: flex; flex-direction: column; border-left: 1px solid var(--border); }
    .modal-chiudi { align-self: flex-end; background: none; border: none; cursor: pointer; font-size: 1.4rem; color: var(--muted); padding: 0 0 16px 0; transition: color 0.2s; }
    .modal-chiudi:hover { color: var(--gold); }
    .modal-materia { font-size: 0.58rem; letter-spacing: 3px; text-transform: uppercase; color: var(--gold); margin-bottom: 6px; }
    .modal-titolo { font-family: 'Cormorant Garamond', serif; font-size: 1.75rem; font-weight: 400; line-height: 1.25; margin-bottom: 4px; }
    .modal-autore { font-size: 0.82rem; color: var(--muted); margin-bottom: 24px; }
    .modal-divider { height: 1px; background: var(--border); margin: 0 0 20px; }
    .modal-info-riga { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 14px; }
    .modal-info-label { font-size: 0.62rem; letter-spacing: 2px; text-transform: uppercase; color: var(--muted); }
    .modal-info-val { font-size: 0.92rem; color: var(--ink); font-weight: 500; text-align: right; }
    .modal-prezzo-big { font-size: 2rem; font-weight: 600; color: var(--gold); margin: 20px 0 8px; }
    .modal-cond-badge { display: inline-block; font-size: 0.62rem; letter-spacing: 1.5px; text-transform: uppercase; padding: 5px 14px; border-radius: 20px; background: var(--bg2); color: var(--muted); border: 1px solid var(--border); margin-bottom: 28px; }
    .modal-btn-rimuovi { display: block; width: 100%; padding: 13px; background: #c0392b; color: white; font-family: 'DM Sans', sans-serif; font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; text-align: center; text-decoration: none; border-radius: 6px; transition: opacity 0.2s; margin-top: auto; }
    .modal-btn-rimuovi:hover { opacity: 0.85; }
</style>

<div class="annunci-grid">
    <?php if (!empty($table)): ?>
        <?php foreach ($table as $a):
            $imgs     = !empty($a['immagini']) ? explode(',', $a['immagini']) : [];
            $primaImg = $imgs[0] ?? null;
        ?>
        <div class="book-card"
             data-titolo="<?= htmlspecialchars(strtolower($a['titolo'] ?? '')) ?>"
             data-autore="<?= htmlspecialchars(strtolower($a['autore'] ?? '')) ?>"
             data-id="<?= $a['id_annuncio'] ?>"
             data-materia="<?= htmlspecialchars($a['materia'] ?? '') ?>"
             data-titolo-full="<?= htmlspecialchars($a['titolo'] ?? '') ?>"
             data-autore-full="<?= htmlspecialchars($a['autore'] ?? '') ?>"
             data-prezzo="<?= number_format($a['prezzo_vendita'] ?? 0, 2, ',', '.') ?>"
             data-condizioni="<?= htmlspecialchars($a['condizioni'] ?? '') ?>"
             data-data="<?= htmlspecialchars($a['data'] ?? '') ?>"
             data-ora="<?= htmlspecialchars($a['ora'] ?? '') ?>"
             data-luogo="<?= htmlspecialchars($a['luogo'] ?? '') ?>"
             data-stato="<?= htmlspecialchars($a['stato'] ?? 'Disponibile') ?>"
             data-immagini="<?= htmlspecialchars($a['immagini'] ?? '') ?>"
             onclick="apriModalPref(this)">

            <div class="card-img-wrap">
                <?php if ($primaImg): ?>
                    <img src="<?= htmlspecialchars($primaImg) ?>" alt="Copertina">
                <?php else: ?>
                    &#9413;
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

            <a href="index.php?page=preferiti&action=store&id_annuncio=<?= $a['id_annuncio'] ?>"
               class="btn-rimuovi"
               onclick="event.stopPropagation(); return confirm('Rimuovere dai preferiti?')">&#128465; Rimuovi dai preferiti</a>

        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty">Non hai ancora salvato nessun annuncio nei preferiti.</p>
    <?php endif; ?>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="modal-overlay-pref" onclick="chiudiSeOverlayPref(event)">
    <div class="modal-box" role="dialog" aria-modal="true">
        <div class="modal-inner">
            <div class="modal-gallery">
                <div class="gallery-main" id="gallery-main-pref">
                    <button class="gallery-arrow prev" onclick="cambiaImmaginePref(-1)">&#8249;</button>
                    <span class="placeholder-icon" id="modal-placeholder-pref">&#9413;</span>
                    <img id="modal-img-grande-pref" src="" alt="" style="display:none">
                    <button class="gallery-arrow next" onclick="cambiaImmaginePref(1)">&#8250;</button>
                </div>
                <div class="gallery-thumbs" id="gallery-thumbs-pref"></div>
            </div>
            <div class="modal-dettagli">
                <button class="modal-chiudi" onclick="chiudiModalPref()">&#10005;</button>
                <p class="modal-materia" id="modal-materia-pref"></p>
                <h2 class="modal-titolo" id="modal-titolo-pref"></h2>
                <p class="modal-autore" id="modal-autore-pref"></p>
                <div class="modal-divider"></div>
                <div class="modal-info-riga"><span class="modal-info-label">Stato</span><span class="modal-info-val" id="modal-stato-pref"></span></div>
                <div class="modal-info-riga"><span class="modal-info-label">Data incontro</span><span class="modal-info-val" id="modal-data-pref"></span></div>
                <div class="modal-info-riga"><span class="modal-info-label">Ora</span><span class="modal-info-val" id="modal-ora-pref"></span></div>
                <div class="modal-info-riga"><span class="modal-info-label">Luogo</span><span class="modal-info-val" id="modal-luogo-pref"></span></div>
                <div class="modal-divider"></div>
                <div class="modal-prezzo-big" id="modal-prezzo-pref"></div>
                <span class="modal-cond-badge" id="modal-condizioni-pref"></span>
                <a href="#" class="modal-btn-rimuovi" id="modal-btn-rimuovi"
                   onclick="return confirm('Rimuovere dai preferiti?')">&#128465; Rimuovi dai preferiti</a>
            </div>
        </div>
    </div>
</div>

<script>
    let imgsPref = [];
    let imgCorrPref = 0;

    function apriModalPref(card) {
        document.getElementById('modal-materia-pref').textContent    = card.dataset.materia    || '';
        document.getElementById('modal-titolo-pref').textContent     = card.dataset.titoloFull || '';
        document.getElementById('modal-autore-pref').textContent     = card.dataset.autoreFull || '';
        document.getElementById('modal-prezzo-pref').textContent     = '€ ' + card.dataset.prezzo;
        document.getElementById('modal-condizioni-pref').textContent = card.dataset.condizioni  || '';
        document.getElementById('modal-stato-pref').textContent      = card.dataset.stato       || 'Disponibile';
        document.getElementById('modal-data-pref').textContent       = card.dataset.data        || '—';
        document.getElementById('modal-ora-pref').textContent        = card.dataset.ora         || '—';
        document.getElementById('modal-luogo-pref').textContent      = card.dataset.luogo       || '—';

        document.getElementById('modal-btn-rimuovi').href =
            'index.php?page=preferiti&action=store&id_annuncio=' + card.dataset.id;

        imgsPref = card.dataset.immagini ? card.dataset.immagini.split(',') : [];
        imgCorrPref = 0;
        costruisciThumbsPref();
        aggiornaImmaginePref();

        document.getElementById('modal-overlay-pref').classList.add('aperta');
        document.body.style.overflow = 'hidden';
    }

    function costruisciThumbsPref() {
        let container = document.getElementById('gallery-thumbs-pref');
        container.innerHTML = '';
        for (let i = 0; i < 3; i++) {
            let div = document.createElement('div');
            div.className = 'gallery-thumb' + (i === 0 ? ' attiva' : '');
            div.onclick = (function(idx){ return function(){ selezionaThumbPref(idx); }; })(i);
            if (imgsPref[i]) {
                let img = document.createElement('img');
                img.src = imgsPref[i]; img.alt = 'Foto ' + (i + 1);
                div.appendChild(img);
            } else { div.textContent = '⊡'; }
            container.appendChild(div);
        }
    }

    function aggiornaImmaginePref() {
        let grande = document.getElementById('modal-img-grande-pref');
        let placeholder = document.getElementById('modal-placeholder-pref');
        if (imgsPref[imgCorrPref]) {
            grande.src = imgsPref[imgCorrPref]; grande.style.display = 'block'; placeholder.style.display = 'none';
        } else {
            grande.style.display = 'none'; placeholder.style.display = 'block';
        }
        document.querySelectorAll('#gallery-thumbs-pref .gallery-thumb').forEach(function(t, i) {
            t.classList.toggle('attiva', i === imgCorrPref);
        });
    }

    function cambiaImmaginePref(dir) {
        let tot = Math.max(imgsPref.length, 1);
        imgCorrPref = (imgCorrPref + dir + tot) % tot;
        aggiornaImmaginePref();
    }

    function selezionaThumbPref(idx) { imgCorrPref = idx; aggiornaImmaginePref(); }

    function chiudiModalPref() {
        document.getElementById('modal-overlay-pref').classList.remove('aperta');
        document.body.style.overflow = '';
    }

    function chiudiSeOverlayPref(e) {
        if (e.target === document.getElementById('modal-overlay-pref')) chiudiModalPref();
    }

    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') chiudiModalPref(); });
</script>