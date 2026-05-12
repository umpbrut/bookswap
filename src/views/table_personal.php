<?php defined('APP') or die('Accesso Negato') ?>
<style>
    .annunci-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 24px; }
    .book-card { background: white; border: 1px solid rgba(60,40,20,0.08); border-radius: 10px; overflow: hidden; box-shadow: var(--shadow); transition: transform 0.3s, box-shadow 0.3s; display: flex; flex-direction: column; height: 100%; cursor: pointer; position: relative; }
    .book-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(60,40,20,0.14); }
    .card-img-wrap { width: 100%; aspect-ratio: 3/4; background: linear-gradient(135deg, var(--bg2), var(--bg3)); display: flex; align-items: center; justify-content: center; font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--gold); opacity: 0.45; overflow: hidden; }
    .card-img-wrap img { width: 100%; height: 100%; object-fit: cover; opacity: 1; }
    .card-body { padding: 16px 18px 20px; flex-grow: 1; }
    .card-materia { font-size: 0.58rem; letter-spacing: 3px; text-transform: uppercase; color: var(--gold); margin-bottom: 5px; }
    .card-title { font-family: 'Cormorant Garamond', serif; font-size: 1.15rem; font-weight: 400; line-height: 1.3; margin-bottom: 4px; }
    .card-autore { font-size: 0.76rem; color: var(--muted); margin-bottom: 14px; }
    .card-footer { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .card-price { font-size: 1.1rem; font-weight: 500; color: var(--gold); }
    .card-cond { font-size: 0.58rem; letter-spacing: 1.5px; text-transform: uppercase; padding: 4px 10px; border-radius: 20px; background: var(--bg2); color: var(--muted); border: 1px solid var(--border); }
    .card-actions { display: flex; gap: 8px; padding: 0 18px 18px; }
    .btn-modifica, .btn-elimina { flex: 1; padding: 9px; border-radius: 5px; font-family: 'DM Sans', sans-serif; font-size: 0.7rem; letter-spacing: 1px; text-transform: uppercase; text-align: center; text-decoration: none; border: none; cursor: pointer; transition: opacity 0.2s; }
    .btn-modifica { background: var(--gold); color: white; }
    .btn-elimina  { background: #c0392b; color: white; }
    .btn-modifica:hover, .btn-elimina:hover { opacity: 0.85; }
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
    .modal-btn-actions { display: flex; gap: 10px; margin-top: auto; }
    .modal-btn-modifica, .modal-btn-elimina { flex: 1; display: block; padding: 13px; font-family: 'DM Sans', sans-serif; font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; text-align: center; text-decoration: none; border-radius: 6px; transition: opacity 0.2s; border: none; cursor: pointer; }
    .modal-btn-modifica { background: var(--gold); color: white; }
    .modal-btn-elimina  { background: #c0392b; color: white; }
    .modal-btn-modifica:hover, .modal-btn-elimina:hover { opacity: 0.85; }
</style>

<div class="annunci-grid">
    <?php if (!empty($table)): ?>
        <?php foreach ($table as $a):
            $id    = $a['id_annuncio'];
            $libro = $a['id_libro'] ?? '';
            $imgs  = !empty($a['immagini']) ? explode(',', $a['immagini']) : [];
            $primaImg = $imgs[0] ?? null;
        ?>
        <div class="book-card"
             data-titolo="<?= htmlspecialchars(strtolower($a['titolo'] ?? '')) ?>"
             data-autore="<?= htmlspecialchars(strtolower($a['autore'] ?? '')) ?>"
             data-id="<?= $id ?>"
             data-id-libro="<?= $libro ?>"
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
             onclick="apriModalP(this)">

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

            <div class="card-actions" onclick="event.stopPropagation()">
                <a href="index.php?page=annunci&action=update&id_annuncio=<?= $id ?>&id_libro=<?= $libro ?>"
                   class="btn-modifica">&#9999;&#65039; Modifica</a>
                <a href="index.php?page=annunci&action=destroy&id_annuncio=<?= $id ?>"
                   class="btn-elimina"
                   onclick="return confirm('Sei sicuro di voler eliminare questo annuncio?')">&#128465; Elimina</a>
            </div>

        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty">Non hai ancora pubblicato nessun annuncio.</p>
    <?php endif; ?>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="modal-overlay-personal" onclick="chiudiSeOverlayP(event)">
    <div class="modal-box" role="dialog" aria-modal="true">
        <div class="modal-inner">
            <div class="modal-gallery">
                <div class="gallery-main" id="gallery-main-p">
                    <button class="gallery-arrow prev" onclick="cambiaImmaginep(-1)">&#8249;</button>
                    <span class="placeholder-icon" id="modal-placeholder-p">&#9413;</span>
                    <img id="modal-img-grande-p" src="" alt="" style="display:none">
                    <button class="gallery-arrow next" onclick="cambiaImmaginep(1)">&#8250;</button>
                </div>
                <div class="gallery-thumbs" id="gallery-thumbs-p"></div>
            </div>
            <div class="modal-dettagli">
                <button class="modal-chiudi" onclick="chiudiModalP()">&#10005;</button>
                <p class="modal-materia" id="modal-materia-p"></p>
                <h2 class="modal-titolo" id="modal-titolo-p"></h2>
                <p class="modal-autore" id="modal-autore-p"></p>
                <div class="modal-divider"></div>
                <div class="modal-info-riga"><span class="modal-info-label">Stato annuncio</span><span class="modal-info-val" id="modal-stato-p"></span></div>
                <div class="modal-info-riga"><span class="modal-info-label">Data incontro</span><span class="modal-info-val" id="modal-data-p"></span></div>
                <div class="modal-info-riga"><span class="modal-info-label">Ora</span><span class="modal-info-val" id="modal-ora-p"></span></div>
                <div class="modal-info-riga"><span class="modal-info-label">Luogo</span><span class="modal-info-val" id="modal-luogo-p"></span></div>
                <div class="modal-divider"></div>
                <div class="modal-prezzo-big" id="modal-prezzo-p"></div>
                <span class="modal-cond-badge" id="modal-condizioni-p"></span>
                <div class="modal-btn-actions">
                    <a href="#" class="modal-btn-modifica" id="modal-btn-modifica">&#9999;&#65039; Modifica</a>
                    <a href="#" class="modal-btn-elimina" id="modal-btn-elimina"
                       onclick="return confirm('Sei sicuro di voler eliminare questo annuncio?')">&#128465; Elimina</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let imgsP = [];
    let imgCorrP = 0;

    function apriModalP(card) {
        document.getElementById('modal-materia-p').textContent    = card.dataset.materia    || '';
        document.getElementById('modal-titolo-p').textContent     = card.dataset.titoloFull || '';
        document.getElementById('modal-autore-p').textContent     = card.dataset.autoreFull || '';
        document.getElementById('modal-prezzo-p').textContent     = '€ ' + card.dataset.prezzo;
        document.getElementById('modal-condizioni-p').textContent = card.dataset.condizioni  || '';
        document.getElementById('modal-stato-p').textContent      = card.dataset.stato       || 'Disponibile';
        document.getElementById('modal-data-p').textContent       = card.dataset.data        || '—';
        document.getElementById('modal-ora-p').textContent        = card.dataset.ora         || '—';
        document.getElementById('modal-luogo-p').textContent      = card.dataset.luogo       || '—';

        let id = card.dataset.id, libro = card.dataset.idLibro;
        document.getElementById('modal-btn-modifica').href = 'index.php?page=annunci&action=update&id_annuncio=' + id + '&id_libro=' + libro;
        document.getElementById('modal-btn-elimina').href  = 'index.php?page=annunci&action=destroy&id_annuncio=' + id;

        imgsP = card.dataset.immagini ? card.dataset.immagini.split(',') : [];
        imgCorrP = 0;
        costruisciThumbsP();
        aggiornaImmagineP();

        document.getElementById('modal-overlay-personal').classList.add('aperta');
        document.body.style.overflow = 'hidden';
    }

    function costruisciThumbsP() {
        let container = document.getElementById('gallery-thumbs-p');
        container.innerHTML = '';
        for (let i = 0; i < 3; i++) {
            let div = document.createElement('div');
            div.className = 'gallery-thumb' + (i === 0 ? ' attiva' : '');
            div.onclick = (function(idx){ return function(){ selezionaThumbP(idx); }; })(i);
            if (imgsP[i]) {
                let img = document.createElement('img');
                img.src = imgsP[i]; img.alt = 'Foto ' + (i + 1);
                div.appendChild(img);
            } else { div.textContent = '⊡'; }
            container.appendChild(div);
        }
    }

    function aggiornaImmagineP() {
        let grande = document.getElementById('modal-img-grande-p');
        let placeholder = document.getElementById('modal-placeholder-p');
        if (imgsP[imgCorrP]) {
            grande.src = imgsP[imgCorrP]; grande.style.display = 'block'; placeholder.style.display = 'none';
        } else {
            grande.style.display = 'none'; placeholder.style.display = 'block';
        }
        document.querySelectorAll('#gallery-thumbs-p .gallery-thumb').forEach(function(t, i) {
            t.classList.toggle('attiva', i === imgCorrP);
        });
    }

    function cambiaImmaginep(dir) {
        let tot = Math.max(imgsP.length, 1);
        imgCorrP = (imgCorrP + dir + tot) % tot;
        aggiornaImmagineP();
    }

    function selezionaThumbP(idx) { imgCorrP = idx; aggiornaImmagineP(); }

    function chiudiModalP() {
        document.getElementById('modal-overlay-personal').classList.remove('aperta');
        document.body.style.overflow = '';
    }

    function chiudiSeOverlayP(e) {
        if (e.target === document.getElementById('modal-overlay-personal')) chiudiModalP();
    }

    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') chiudiModalP(); });
</script>