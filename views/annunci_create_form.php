<form action="index.php?page=annunci&action=store" method="post">

<div class="search-container" style="position: relative; width: 300px;">
  
  <input type="text" id="search" list="lista_libri" oninput="get_libri()" placeholder="Inizia a scrivere..." autocomplete="off" 
    style="width: 100%; padding: 8px; box-sizing: border-box;">

<datalist id="lista_libri"></datalist>

<script>
    function get_libri(){
        let cerca = search.value;
        let testo ="";
        if(cerca != ""){
            testo = `&testo=${cerca}`;
        }
        
        fetch("libri.php?get_libri" + testo)
        .then(res => res.json())
        .then(data => {
            lista_libri.innerHTML = "";
            
            for(let riga of data){
                lista_libri.innerHTML += `<option value="${riga.titolo}"></option>`;
            }
        })
    }
</script>
  
</div>

<br>

<label for="prezzo">Prezzo (€):</label>
    <input type="number" step="0.01" name="prezzo" id="prezzo" required>

    <br><br>

    <label for="data">Data:</label>
    <input type="date" name="data" id="data" value="<?php echo date('Y-m-d'); ?>" required>

    <br><br>

    <label for="ora">Ora:</label>
    <input type="time" name="ora" id="ora" value="<?php echo date('H:i'); ?>" required>

    <br><br>

    <label for="luogo">Luogo:</label>
    <input type="text" name="luogo" id="luogo" placeholder="Es. Biblioteca" required>

    <br><br>

    <label for="condizioni">Condizioni del libro:</label>
    <select name="condizioni" id="condizioni">
        <option value="Nuovo">Nuovo</option>
        <option value="Ottime">Ottime</option>
        <option value="Buone">Buone</option>
        <option value="Usato">Usato/Rovinato</option>
    </select>

    <br><br>

    <button type="submit">Pubblica Annuncio</button>

</form>