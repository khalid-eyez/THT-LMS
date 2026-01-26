<?php
/** @var string $title */
/** @var string $value */
/** @var string $icon */
/** @var string $hint */
?>
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
    <div class="notika-kpi-card">
        <div class="kpi-icon"><i class="<?= $icon ?>"></i></div>
        <div class="kpi-meta">
            <div class="kpi-title"><?= htmlspecialchars($title) ?></div>
            <div class="kpi-value"><?= htmlspecialchars($value) ?></div>
            <div class="kpi-hint"><?= htmlspecialchars($hint) ?></div>
        </div>
    </div>
</div>
