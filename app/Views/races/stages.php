<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('races') ?>">Závody</a></li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('races/' . $race['id'] . '/years') ?>"><?= esc($race['default_name']) ?></a>
        </li>
        <li class="breadcrumb-item active">
            Etapy <?= esc($raceYear['real_name']) ?> (<?= esc($raceYear['year']) ?>)
        </li>
    </ol>
</nav>

<h1 class="h3 mb-1"><?= esc($raceYear['real_name']) ?></h1>
<p class="text-muted mb-4">Přehled etap – ročník <?= esc($raceYear['year']) ?></p>

<?php if (empty($stages)): ?>
    <div class="alert alert-info">Pro tento ročník nejsou evidovány žádné etapy.</div>
<?php else: ?>

<div class="table-responsive shadow-sm rounded">
<table class="table table-hover align-middle mb-0 bg-white">
    <thead class="table-dark">
        <tr>
            <th class="text-center">#</th>
            <th>Datum</th>
            <th>Start</th>
            <th>Cíl</th>
            <th class="text-end">Délka (km)</th>
            <th>Typ parcoursu</th>
            <th class="text-end">Převýšení (m)</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($stages as $stage): ?>
        <tr>
            <td class="text-center fw-bold">
                <?= $stage['number'] !== null ? esc($stage['number']) : '<span class="text-muted">—</span>' ?>
            </td>
            <td>
                <?php
                    $d = new DateTime($stage['date']);
                    echo $d->format('j. n. Y');
                ?>
            </td>
            <td><?= esc($stage['departure']) ?></td>
            <td><?= esc($stage['arrival']) ?></td>
            <td class="text-end"><?= number_format((float)$stage['distance'], 1, ',', ' ') ?></td>
            <td>
                <?= esc($stage['parcour_name'] ?? '—') ?>
            </td>
            <td class="text-end">
                <?= $stage['vertical_meters'] ? number_format((int)$stage['vertical_meters'], 0, ',', ' ') : '<span class="text-muted">—</span>' ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php endif; ?>

<div class="mt-4">
    <a href="<?= base_url('races/' . $race['id'] . '/years') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Zpět na ročníky
    </a>
</div>

<?= $this->endSection() ?>
