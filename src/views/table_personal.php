<?php defined('APP') or die('Accesso Negato') ?>
<style>
    /* Riusa gli stessi stili di table.php — se includi table.php prima non serve riscriverli.
       Qui aggiungiamo solo i bottoni azione specifici della pagina personal. */
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
    }
    .book-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(60,40,20,0.14); }
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
    .card-footer { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .card-price { font-size: 1.1rem; font-weight: 500; color: var(--gold); }
    .card-cond {
        font-size: 0.58rem; letter-spacing: 1.5px; text-transform: uppercase;
        padding: 4px 10px; border-radius: 20px;
        background: var(--bg2); color: var(--muted); border: 1px solid var(--border);
    }
    /* Azioni modifica / elimina */
    .card-actions { display: flex; gap: 8px; padding: 0 18px 18px; }
    .btn-modifica, .btn-elimina {
        flex: 1; padding: 9px; border-radius: 5px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.7rem; letter-spacing: 1px; text-transform: uppercase;
        text-align: center; text-decoration: none;
        border: none; cursor: pointer; transition: opacity 0.2s;
    }
    .btn-modifica { background: var(--gold); color: white; }
    .btn-elimina  { background: #c0392b; color: white; }
    .btn-modifica:hover, .btn-elimina:hover { opacity: 0.85; }
    .empty { text-align: center; padding: 60px; color: var(--muted); font-size: 0.9rem; }
</style>

<div class="annunci-grid">
    <?php if (!empty($table)): ?>
        <?php foreach ($table as $a): ?>
        <?php
            $id    = $a['id_annuncio'];
            $libro = $a['id_libro'] ?? '';
        ?>
        <div class="book-card">

            <div class="card-img-placeholder">&#9413;</div>

            <div class="card-body">
                <p class="card-materia"><?= htmlspecialchars($a['materia'] ?? '') ?></p>
                <h3 class="card-title"><?= htmlspecialchars($a['titolo'] ?? $a['id_libro'] ?? '') ?></h3>
                <p class="card-autore"><?= htmlspecialchars($a['autore'] ?? '') ?></p>
                <div class="card-footer">
                    <span class="card-price">€ <?= number_format($a['prezzo_vendita'] ?? 0, 2, ',', '.') ?></span>
                    <span class="card-cond"><?= htmlspecialchars($a['condizioni'] ?? '') ?></span>
                </div>
            </div>

            <div class="card-actions">
                <a href="index.php?page=annunci&action=update&id_annuncio=<?= $id ?>&id_libro=<?= $libro ?>"
                   class="btn-modifica">✏️ Modifica</a>
                <a href="index.php?page=annunci&action=destroy&id_annuncio=<?= $id ?>"
                   class="btn-elimina"
                   onclick="return confirm('Sei sicuro di voler eliminare questo annuncio?')">🗑 Elimina</a>
            </div>

        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty">Non hai ancora pubblicato nessun annuncio.</p>
    <?php endif; ?>
</div>