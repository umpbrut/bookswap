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
    <!-- Parametri nascosti per il router -->
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
    <!-- Azzera = torna all'index senza parametri -->
    <a href="index.php?page=annunci&action=index" class="btn-azzera">Azzera</a>
</form>

<!-- GRIGLIA: dati dal DB via $table -->
<div class="annunci-grid" id="annunci-grid">
    <?php if (!empty($table)): ?>
        <?php foreach ($table as $a): ?>
        <div class="book-card"
             data-titolo="<?= htmlspecialchars(strtolower($a['titolo'] ?? '')) ?>"
             data-autore="<?= htmlspecialchars(strtolower($a['autore'] ?? '')) ?>">

            <?php if (isset($_SESSION['id_utente'])): ?>
            <a href="index.php?page=preferiti&action=store&id_annuncio=<?= $a['id_annuncio'] ?>"
               class="heart-link" title="Aggiungi ai preferiti">❤️</a>
            <?php endif; ?>

            <div class="card-img-placeholder">&#9413;</div>

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

<script>
    /* Ricerca testo: filtra le card già presenti in pagina mentre scrivi */
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
</script>
