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
        
//         echo "</tr>";
//     }
//     echo "</tbody></table></div>";
// }


defined('APP') or die('Accesso Negato');

if (!empty($table)) {
    echo "<div class='row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4'>";
    
    foreach ($table as $record) {
        $id = $record['id_annuncio'];
        $titolo = $record['titolo'] ?? 'Titolo non disponibile';
        $prezzo = $record['prezzo'] ?? '0.00';
        $luogo = $record['luogo'] ?? 'Non specificato';
        $condizioni = $record['condizioni'] ?? 'Buone';

        // Immagine segnaposto basata sul titolo per un effetto visivo carino
        $imgUrl = "https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=400";
        ?>
        
        <div class="col">
            <div class="card h-100 border-0 shadow-sm book-card" style="border-radius: 15px; overflow: hidden; transition: transform 0.3s;">
                <div style="height: 200px; overflow: hidden; position: relative;">
                    <img src="<?= $imgUrl ?>" class="card-img-top" alt="Copertina" style="object-fit: cover; height: 100%; width: 100%;">
                    <span class="badge bg-dark position-absolute top-0 end-0 m-3" style="background-color: var(--library-accent) !important;">
                        € <?= number_format($prezzo, 2) ?>
                    </span>
                </div>
                
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold" style="font-family: 'Playfair Display', serif; color: var(--library-dark);">
                        <?= htmlspecialchars($titolo) ?>
                    </h5>
                    <p class="card-text text-muted small mb-2">
                        <i class="bi bi-geo-alt"></i> 📍 <?= htmlspecialchars($luogo) ?>
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-uppercase x-small fw-bold text-secondary" style="font-size: 0.7rem; letter-spacing: 1px;">
                            <?= htmlspecialchars($condizioni) ?>
                        </span>
                        <a href="index.php?page=annunci&action=dettaglio&id=<?= $id ?>" 
                           class="btn btn-outline-dark btn-sm rounded-pill px-3">Vedi</a>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .book-card:hover { transform: translateY(-10px); }
        </style>

        <?php
    }
    echo "</div>";
} else {
    echo "<div class='alert alert-light text-center border-0 py-5' style='background-color: var(--library-soft)'>
            <p class='mb-0 text-muted italic'>Nessun annuncio trovato nel catalogo.</p>
          </div>";
}