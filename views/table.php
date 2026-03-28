<?php
defined('APP') or die('Accesso Negato');

if (!empty($table)) {
    $keys = array_keys($table[0]);
    echo "<div class='table-responsive'>";
    echo "<table class='table table-striped table-hover border'>";
    echo "<thead class='table-dark'><tr>";
    foreach ($keys as $key) {
        echo "<th>" . ucfirst($key) . "</th>";
    }
    echo '</tr></thead><tbody>';

    foreach ($table as $record) {
        echo "<tr>";
        foreach ($record as $field) {
            echo "<td>$field</td>";
        }
        echo "</tr>";
    }
    echo "</tbody></table></div>";
}