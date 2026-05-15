<?php defined('APP') or die('Accesso Negato') ?>
<!-- Vista ordini: mostra gli ordini acquistati o venduti e permette azioni di consegna/ripristino. -->
<style>
    /* TAB: navigazione tra ordini acquistati e venduti. */
    .ordini-tabs {
        display: flex;
        gap: 0;
        border-bottom: 2px solid var(--border);
        margin-bottom: 32px;
    }

    .ordini-tab {
        padding: 10px 28px;
        font-size: 0.68rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-decoration: none;
        color: var(--muted);
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: color 0.2s, border-color 0.2s;
    }

    .ordini-tab:hover {
        color: var(--gold);
    }

    .ordini-tab.active {
        color: var(--gold);
        border-bottom-color: var(--gold);
        font-weight: 500;
    }

    /* TABELLA: layout compatto per righe e intestazioni degli ordini. */
    .ordini-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    .ordini-table th {
        text-align: left;
        padding: 10px 14px;
        font-size: 0.6rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--muted);
        border-bottom: 1px solid var(--border);
    }

    .ordini-table td {
        padding: 14px 14px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        color: var(--ink);
    }

    .ordini-table tr:last-child td {
        border-bottom: none;
    }

    .ordini-table tr:hover td {
        background: var(--bg2);
    }

    /* BADGE STATO: indicatori colore per mostrare lo stato dell'ordine. */
    .badge {
        display: inline-block;
        font-size: 0.58rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 20px;
        border: 1px solid var(--border);
    }

    .badge-disponibile {
        background: #eaf6ee;
        color: #2e7d52;
        border-color: #b6dfca;
    }

    .badge-prenotato {
        background: #fff8e6;
        color: #a06000;
        border-color: #f0d89a;
    }

    .badge-concluso {
        background: var(--bg2);
        color: var(--muted);
    }

    /* PULSANTI AZIONE: azioni disponibili per gli ordini venduti non ancora conclusi. */
    .btn-consegna,
    .btn-ripristina {
        display: inline-block;
        padding: 6px 16px;
        font-size: 0.62rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        text-decoration: none;
        border-radius: 4px;
        transition: opacity 0.2s;
        white-space: nowrap;
    }

    .btn-consegna {
        background: var(--gold);
        color: white;
        margin-right: 6px;
    }

    .btn-consegna:hover {
        opacity: 0.82;
    }

    .btn-ripristina {
        background: white;
        color: #c0392b;
        border: 1px solid #c0392b;
    }

    .btn-ripristina:hover {
        opacity: 0.82;
    }

    .empty-msg {
        text-align: center;
        padding: 50px;
        color: var(--muted);
        font-size: 0.9rem;
    }
</style>

<?php
// Determina il tab attivo (default: ordinati)
$tab = $_GET['tab'] ?? 'ordinati';
?>

<!-- Barra di selezione tab per passare fra gli ordini acquistati e quelli venduti. -->
<div class="ordini-tabs">
    <a href="index.php?page=ordini&action=index&tab=ordinati"
        class="ordini-tab <?= $tab === 'ordinati' ? 'active' : '' ?>">
        Ordinati
    </a>
    <a href="index.php?page=ordini&action=index&tab=venduti"
        class="ordini-tab <?= $tab === 'venduti' ? 'active' : '' ?>">
        Venduti
    </a>
</div>

<?php if ($tab === 'ordinati'): ?>

    <!-- Sezione ordini acquistati: mostra i libri prenotati/acquistati dall'utente. -->
    <?php if (empty($ordinati)): ?>
        <p class="empty-msg">Non hai ancora acquistato nessun libro.</p>
    <?php else: ?>
        <table class="ordini-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titolo</th>
                    <th>Autore</th>
                    <th>Prezzo</th>
                    <th>Stato</th>
                    <th>Data</th>
                    <th>Luogo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordinati as $o): ?>
                    <?php
                    // Normalizza lo stato e sceglie la classe badge corretta.
                    $stato = htmlspecialchars($o['stato'] ?? 'prenotato');
                    $classe = match ($stato) {
                        'concluso' => 'badge-concluso',
                        'disponibile' => 'badge-disponibile',
                        default => 'badge-prenotato',
                    };
                    ?>
                    <tr>
                        <td style="color:var(--muted);font-size:0.8rem;"><?= $o['id_annuncio'] ?></td>
                        <td><strong><?= htmlspecialchars($o['titolo']) ?></strong></td>
                        <td style="color:var(--muted);"><?= htmlspecialchars($o['autore']) ?></td>
                        <td style="color:var(--gold);font-weight:500;">€ <?= number_format($o['prezzo_vendita'], 2, ',', '.') ?>
                        </td>
                        <td><span class="badge <?= $classe ?>"><?= $stato ?></span></td>
                        <td style="color:var(--muted);font-size:0.82rem;"><?= htmlspecialchars($o['data'] ?? '—') ?></td>
                        <td style="color:var(--muted);font-size:0.82rem;"><?= htmlspecialchars($o['luogo'] ?? '—') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

<?php elseif ($tab === 'venduti'): ?>

    <!-- Sezione ordini venduti: mostra gli annunci con compratore e azioni disponibilità. -->
    <?php if (empty($venduti)): ?>
        <p class="empty-msg">Nessun ordine da gestire.</p>
    <?php else: ?>
        <table class="ordini-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titolo</th>
                    <th>Autore</th>
                    <th>Prezzo</th>
                    <th>Compratore</th>
                    <th>Stato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($venduti as $v): ?>
                    <?php
                    // Normalizza lo stato e sceglie una classe colore per il badge.
                    $stato = htmlspecialchars($v['stato'] ?? 'prenotato');
                    $classe = match ($stato) {
                        'concluso' => 'badge-concluso',
                        'disponibile' => 'badge-disponibile',
                        default => 'badge-prenotato',
                    };
                    // Controllo se l'ordine è già concluso per disabilitare le azioni. 
                    $concluso = $stato === 'Concluso';
                    ?>
                    <tr>
                        <td style="color:var(--muted);font-size:0.8rem;"><?= $v['id_annuncio'] ?></td>
                        <td><strong><?= htmlspecialchars($v['titolo']) ?></strong></td>
                        <td style="color:var(--muted);"><?= htmlspecialchars($v['autore']) ?></td>
                        <td style="color:var(--gold);font-weight:500;">€ <?= number_format($v['prezzo_vendita'], 2, ',', '.') ?>
                        </td>
                        <td style="font-size:0.84rem;">
                            <?= htmlspecialchars(($v['nome_compratore'] ?? '') . ' ' . ($v['cognome_compratore'] ?? '')) ?>
                        </td>
                        <td><span class="badge <?= $classe ?>"><?= $stato ?></span></td>
                        <td style="white-space:nowrap;">
                            <?php if (!$concluso): ?>
                                <!-- Azioni consentite solo se l'ordine non è ancora concluso. -->
                                <div style="display:flex;gap:6px;flex-wrap:nowrap;">
                                    <a href="index.php?page=ordini&action=consegna&id_annuncio=<?= $v['id_annuncio'] ?>"
                                        class="btn-consegna"
                                        onclick="return confirm('Segna come consegnato? Le immagini verranno eliminate.')">
                                        Consegnato
                                    </a>
                                    <a href="index.php?page=ordini&action=ripristina&id_annuncio=<?= $v['id_annuncio'] ?>"
                                        class="btn-ripristina"
                                        onclick="return confirm('Annullare l\'ordine e rimettere l\'annuncio disponibile?')">
                                        Ripristina
                                    </a>
                                </div>
                            <?php else: ?>
                                <span style="font-size:0.72rem;color:var(--muted);letter-spacing:1px;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

<?php endif; ?>