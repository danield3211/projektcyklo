<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * Helper pro vlajku
 * country je dvoupísmenný ISO kód, např. "au", "fr"
 */
function flagSpan(string $country): string
{
    if (!$country) return '';
    return '<span class="fi fi-' . strtolower(esc($country)) . '" title="' . strtoupper(esc($country)) . '"></span>';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-flag-fill me-2 text-primary"></i>Přehled závodů</h1>
</div>

<?php if (empty($races)): ?>
    <div class="alert alert-info">Žádné závody nebyly nalezeny.</div>
<?php else: ?>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
    <?php foreach ($races as $race): ?>
    <div class="col">
        <a href="<?= base_url('races/' . $race['id'] . '/years') ?>" class="text-decoration-none text-dark">
            <div class="card race-card shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <?= flagSpan($race['country']) ?>
                        <span class="fw-semibold lh-sm"><?= esc($race['default_name']) ?></span>
                    </div>
                    <?php if ($race['type']): ?>
                        <span class="badge bg-secondary stage-badge"><?= esc($race['type']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-2 px-3">
                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>Zobrazit ročníky</small>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<!-- Stránkování -->
<div class="mt-4 d-flex justify-content-center">
    <?= $pager->links('default', 'bootstrap_pagination') ?>
</div>

<?php endif; ?>

<?= $this->endSection() ?>
