<?php
// defined('APP') or die('Accesso Negato');

// if (!empty($table)) {
//     $keys = array_keys($table[0]);
//     echo "<div class='table-responsive'>";
//     echo "<table class='table table-striped table-hover border'>";
//     echo "<thead class='table-dark'><tr>";
//     foreach ($keys as $key) {
//         echo "<th>" . ucfirst($key) . "</th>";
//     }
//     echo '</tr></thead><tbody>';

//     foreach ($table as $record) {
//         echo "<tr>";
//         foreach ($record as $field) {
//             echo "<td>$field</td>";
//         }

//         $id = $record['id_annuncio'];

//         echo "<td>
//                 <a href='index.php?page=preferiti&action=store&id_annuncio=$id'>
//                 Aggiungi ❤️
//                 </a>
//             </td>";
        
//         echo "</tr>";
//     }
//     echo "</tbody></table></div>";
//}
defined('APP') or die('Accesso Negato') ?>
<style>
    /* BARRA RICERCA */
    .search-wrap {
        position: relative; max-width: 480px; margin-bottom: 32px;
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
    #custom_results {
        position: absolute; top: calc(100% + 6px); left: 0; width: 100%;
        background: white; border: 1px solid var(--border); border-radius: 8px;
        z-index: 200; display: none;
        box-shadow: 0 8px 24px rgba(60,40,20,0.12);
    }
    #custom_results div {
        padding: 11px 16px; border-bottom: 1px solid var(--border);
        color: var(--ink); font-size: 0.86rem; cursor: pointer;
    }
    #custom_results div:last-child { border-bottom: none; }
    #custom_results div:hover { background: var(--bg2); color: var(--gold); }

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

    /* cuore preferiti */
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
    .card-materia {
        font-size: 0.58rem; letter-spacing: 3px;
        text-transform: uppercase; color: var(--gold); margin-bottom: 5px;
    }
    .card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.15rem; font-weight: 400; line-height: 1.3; margin-bottom: 4px;
    }
    .card-autore { font-size: 0.76rem; color: var(--muted); margin-bottom: 14px; }
    .card-footer { display: flex; align-items: center; justify-content: space-between; }
    .card-price { font-size: 1.1rem; font-weight: 500; color: var(--gold); }
    .card-cond {
        font-size: 0.58rem; letter-spacing: 1.5px; text-transform: uppercase;
        padding: 4px 10px; border-radius: 20px;
        background: var(--bg2); color: var(--muted); border: 1px solid var(--border);
    }
    .empty { text-align: center; padding: 60px; color: var(--muted); font-size: 0.9rem; }
</style>

<!-- RICERCA -->
<div class="search-wrap">
    <span class="search-icon">&#9906;</span>
    <input type="text" id="search" placeholder="Cerca titolo o autore..." autocomplete="off" oninput="get_annunci()">
    <div id="custom_results"></div>
</div>

<!-- GRIGLIA -->
<div class="annunci-grid" id="annunci-grid">
    <?php if (!empty($table)): ?>
        <?php foreach ($table as $a): ?>
        <div class="book-card">

            <!-- Preferiti -->
            <?php if (isset($_SESSION['id_utente'])): ?>
            <a href="index.php?page=preferiti&action=store&id_annuncio=<?= $a['id_annuncio'] ?>"
               class="heart-link" title="Aggiungi ai preferiti">❤️</a>
            <?php endif; ?>

            <!-- Immagine placeholder (aggiungi <img> se hai il campo immagine) -->
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
    <?php else: ?>
        <p class="empty">Nessun annuncio disponibile.</p>
    <?php endif; ?>
</div>

<script>
    /* Ricerca annunci con fetch — stesso schema di get_libri() */
    function get_annunci() {
        let cerca = document.getElementById('search').value;
        let container = document.getElementById('custom_results');

        if (cerca.length < 2) {
            container.style.display = 'none';
            return;
        }

        fetch("libri.php?cerca_annunci&testo=" + encodeURIComponent(cerca))
        .then(res => res.json())
        .then(data => {
            container.innerHTML = "";
            if (data.length > 0) {
                container.style.display = 'block';
                data.forEach(riga => {
                    let titolo = riga.titolo || riga.Titolo;
                    let autore = riga.autore || riga.Autore || '';
                    let item = document.createElement('div');
                    item.innerText = titolo + (autore ? ' — ' + autore : '');
                    item.onclick = function() {
                        /* quando l'utente seleziona: porta all'URL filtrato */
                        window.location.href = "index.php?page=annunci&titolo=" + encodeURIComponent(titolo);
                    };
                    container.appendChild(item);
                });
            } else {
                container.style.display = 'none';
            }
        });
    }

    /* Chiudi dropdown cliccando fuori */
    document.addEventListener('click', function(e) {
        let wrap = document.getElementById('search').parentElement;
        if (!wrap.contains(e.target)) {
            document.getElementById('custom_results').style.display = 'none';
        }
    });
</script>