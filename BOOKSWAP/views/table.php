<?php
defined('APP') or die('Accesso Negato');

if (!empty($table) && isset($table[0])) {
    $keys = array_keys($table[0]);
    
    echo "<div class='table-responsive mt-4'>";
    echo "<table class='table table-striped table-hover border shadow-sm'>";
    echo "<thead class='table-dark'><tr>";
    
    foreach ($keys as $key) {
        echo "<th>" . ucfirst(str_replace('_', ' ', $key)) . "</th>";
    }
    
    echo '</tr></thead><tbody>';

    // Ciclo sui record per popolare le righe
    foreach ($table as $record) {
        echo "<tr>";
        foreach ($record as $field) {
            // Gestione dei valori nulli o vuoti per una visualizzazione pulita
            $value = ($field !== null && $field !== '') ? htmlspecialchars($field) : '<span class="text-muted small">n/d</span>';
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }
    
    echo "</tbody></table></div>";
} else {
    // Messaggio di fallback se non ci sono annunci o dati da mostrare
    echo "
    <div class='alert alert-info border-0 shadow-sm text-center mt-4' role='alert'>
        <h4 class='alert-heading'>Nessun dato trovato</h4>
        <p class='mb-0'>Al momento non ci sono record disponibili in questa sezione.</p>
    </div>";
}