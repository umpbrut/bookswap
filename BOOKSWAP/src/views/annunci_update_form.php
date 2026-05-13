<?php 
$annuncio = $table[0];
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">

        <form action="index.php?page=annunci&action=edit" method="post">

            <input type="hidden" name="id_annuncio" value="<?= $annuncio['id_annuncio'] ?>">
            <input type="hidden" name="id_libro" id="id_libro_hidden" value="<?= $annuncio['id_libro'] ?>">

            <!-- Libro -->
            <div class="mb-3 position-relative">
                <label class="form-label" style="font-family:'DM Sans',sans-serif; font-size:0.68rem; letter-spacing:2px; text-transform:uppercase; color:var(--muted);">Libro</label>
                <input type="text" id="search" class="form-control" list="lista_libri"
                       oninput="get_libri()" placeholder="Cerca un libro..." autocomplete="off"
                       value="<?= htmlspecialchars($annuncio['titolo'] ?? '') ?>">
                <datalist id="lista_libri"></datalist>
            </div>

            <!-- Prezzo e Ora -->
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label" style="font-family:'DM Sans',sans-serif; font-size:0.68rem; letter-spacing:2px; text-transform:uppercase; color:var(--muted);">Prezzo (€)</label>
                    <input type="number" step="0.01" name="prezzo_vendita" id="prezzo_vendita"
                           class="form-control" value="<?= $annuncio['prezzo_vendita'] ?>" required>
                </div>
                <div class="col-6">
                    <label class="form-label" style="font-family:'DM Sans',sans-serif; font-size:0.68rem; letter-spacing:2px; text-transform:uppercase; color:var(--muted);">Ora</label>
                    <input type="time" name="ora" id="ora"
                           class="form-control" value="<?= $annuncio['ora'] ?>" required>
                </div>
            </div>

            <!-- Luogo -->
            <div class="mb-3">
                <label class="form-label" style="font-family:'DM Sans',sans-serif; font-size:0.68rem; letter-spacing:2px; text-transform:uppercase; color:var(--muted);">Luogo</label>
                <input type="text" name="luogo" id="luogo"
                       class="form-control" value="<?= htmlspecialchars($annuncio['luogo']) ?>" required>
            </div>

            <!-- Condizioni e Stato -->
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label" style="font-family:'DM Sans',sans-serif; font-size:0.68rem; letter-spacing:2px; text-transform:uppercase; color:var(--muted);">Condizioni</label>
                    <select name="condizioni" id="condizioni" class="form-select">
                        <option value="Nuovo (Mai aperto)"     <?= ($annuncio['condizioni'] == 'Nuovo (Mai aperto)')  ? 'selected' : '' ?>>Nuovo (Mai aperto)</option>
                        <option value="Ottime condizioni"      <?= ($annuncio['condizioni'] == 'Ottime condizioni')   ? 'selected' : '' ?>>Ottime condizioni</option>
                        <option value="Buone condizioni"       <?= ($annuncio['condizioni'] == 'Buone condizioni')    ? 'selected' : '' ?>>Buone condizioni</option>
                        <option value="Usato / Rovinato"       <?= ($annuncio['condizioni'] == 'Usato / Rovinato')    ? 'selected' : '' ?>>Usato / Rovinato</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label" style="font-family:'DM Sans',sans-serif; font-size:0.68rem; letter-spacing:2px; text-transform:uppercase; color:var(--muted);">Stato Annuncio</label>
                    <select name="stato" class="form-select">
                        <option value="Disponibile"    <?= ($annuncio['stato'] == 'Disponibile')    ? 'selected' : '' ?>>Disponibile</option>
                        <option value="Non disponibile" <?= ($annuncio['stato'] == 'Non disponibile') ? 'selected' : '' ?>>Non Disponibile</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-100 py-3"
                style="background:#9c6b3c; color:white; border:none; border-radius:8px;
                       font-family:'DM Sans',sans-serif; font-size:0.75rem;
                       font-weight:600; letter-spacing:3px; text-transform:uppercase;
                       transition:background 0.2s; cursor:pointer;"
                onmouseover="this.style.background='#b8844f'"
                onmouseout="this.style.background='#9c6b3c'">
                Salva Modifiche
            </button>

        </form>

    </div>
</div>

<script>
    function get_libri() {
        let cerca = document.getElementById('search').value;
        let lista = document.getElementById('lista_libri');
        let hiddenInput = document.getElementById('id_libro_hidden');

        if (cerca == "") {
            hiddenInput.value = "";
            return;
        }

        fetch("libri.php?get_libri&testo=" + cerca)
        .then(res => res.json())
        .then(data => {
            lista.innerHTML = "";
            data.forEach(riga => {
                let option = document.createElement('option');
                option.value = riga.titolo;
                option.setAttribute('data-id', riga.id_libro);
                lista.appendChild(option);
            });

            let opzioneTrovata = Array.from(lista.options).find(opt => opt.value === cerca);
            if (opzioneTrovata) {
                hiddenInput.value = opzioneTrovata.getAttribute('data-id');
            }
        });
    }
</script>