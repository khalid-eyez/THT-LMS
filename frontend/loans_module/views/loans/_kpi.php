<?php
/**
 * KPI Card (height-consistent even if hint is missing)
 *
 * @var string $title
 * @var string $value
 * @var string $icon
 * @var string $hint
 */

$hint = trim((string) $hint);
?>

<style>
    /* Keep cards equal height within the grid row */
    .notika-kpi-card {
        height: 100%;
        display: flex;
        align-items: stretch;
    }

    /* Make meta a full-height column so hint can sit at bottom */
    .notika-kpi-card .kpi-meta {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
    }

    /* Reserve space for hint even when it's empty */
    .notika-kpi-card .kpi-hint {
        margin-top: auto;      /* pushes hint to the bottom */
        min-height: 18px;      /* keeps height consistent */
        line-height: 18px;
    }
</style>

<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
    <div class="notika-kpi-card">
        <div class="kpi-icon"><i class="<?= $icon ?>"></i></div>

        <div class="kpi-meta">
            <div class="kpi-title"><?= htmlspecialchars($title) ?></div>
            <div class="kpi-value"><?= htmlspecialchars($value) ?></div>

            <!-- Keep element height even if hint is missing -->
            <div class="kpi-hint"><?= $hint !== '' ? htmlspecialchars($hint) : '&nbsp;' ?></div>
        </div>
    </div>
</div>
