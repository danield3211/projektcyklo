<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
function formatRaceDates(string $startDate, string $endDate): string
{
    $start = new DateTime($startDate);
    $end   = new DateTime($endDate);
    if (class_exists('IntlDateFormatter')) {
        $formatter = new IntlDateFormatter('cs_CZ', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        if ($startDate === $endDate) return $formatter->format($start);
        if ($start->format('Y-m') === $end->format('Y-m')) {
            $dayFmt = new IntlDateFormatter('cs_CZ', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'd.');
            return $dayFmt->format($start) . ' – ' . $formatter->format($end);
        }
        return $formatter->format($start) . ' – ' . $formatter->format($end);
    }
    if ($startDate === $endDate) return (new DateTime($startDate))->format('j. n. Y');
    return (new DateTime($startDate))->format('j. n.') . ' – ' . (new DateTime($endDate))->format('j. n. Y');
}


?>

<!-- PAGE HEADER -->
<div class="page-header-bar">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('races') ?>"><i class="bi bi-house me-1"></i>Závody</a></li>
                <li class="breadcrumb-item active"><?= esc($race['default_name']) ?></li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h1 class="mb-0 d-flex align-items-center gap-2">
                <?= flagSpan($race['country']) ?>
                <?= esc($race['default_name']) ?>
            </h1>
            <a href="<?= base_url('races/' . $race['id'] . '/years/create') ?>" class="btn btn-red">
                <i class="bi bi-plus-circle me-1"></i>Přidat ročník
            </a>
        </div>
    </div>
</div>

<div class="container">

<?php if (empty($raceYears)): ?>
    <div class="alert alert-info rounded-3">
        <i class="bi bi-info-circle me-2"></i>Pro tento závod zatím nejsou žádné ročníky.
    </div>
<?php else: ?>

<div class="table-card">
<table class="table table-hover align-middle mb-0">
    <thead>
        <tr>
            <th>Název</th>
            <th>Ročník</th>
            <th>Datum</th>
            <th>Logo</th>
            <th>Kategorie UCI</th>
            <th>Země</th>
            <th class="text-center">Etapy</th>
            <th class="text-end">Akce</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($raceYears as $ry): ?>
        <tr>
            <td class="fw-semibold"><?= esc($ry['real_name']) ?></td>
            <td>
                <span class="badge rounded-pill" style="background:#f0f2f8;color:#333;font-size:.85rem;font-weight:600">
                    <?= esc($ry['year']) ?>
                </span>
            </td>
            <td>
                <small class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i><?= formatRaceDates($ry['start_date'], $ry['end_date']) ?>
                </small>
            </td>
            <td>
                <?php if ($ry['logo']): ?>
                    <img src="<?= base_url('uploads/logos/' . esc($ry['logo'])) ?>"
                         alt="logo" class="logo-thumb" loading="lazy">
                <?php else: ?>
                    <span class="text-muted small">—</span>
                <?php endif; ?>
            </td>
            <td>
                <span class="badge rounded-pill" style="background:#fff0f0;color:#c1121f;font-size:.8rem;font-weight:600;border:1px solid #fecdd3">
                    <?= esc($uciTourTypes[$ry['uci_tour']] ?? '?') ?>
                </span>
            </td>
            <td>
                <?= flagSpan($ry['country']) ?>
                <span class="ms-1 text-uppercase" style="font-size:.78rem;color:#888"><?= esc($ry['country']) ?></span>
            </td>
            <td class="text-center">
                <?php
                    $stageCount = (new \App\Models\StageModel())
                        ->where('id_race_year', $ry['id'])
                        ->countAllResults();
                ?>
                <?php if ($stageCount > 0): ?>
                    <a href="<?= base_url('raceyears/' . $ry['id'] . '/stages') ?>"
                       class="badge rounded-pill text-decoration-none"
                       style="background:var(--red,#e63946);color:#fff;font-size:.88rem;padding:.35em .75em">
                        <i class="bi bi-list-ol me-1"></i><?= $stageCount ?>
                    </a>
                <?php else: ?>
                    <span class="text-muted small">—</span>
                <?php endif; ?>
            </td>
            <td class="text-end">
                <a href="<?= base_url('races/' . $race['id'] . '/years/' . $ry['id'] . '/edit') ?>"
                   class="btn btn-sm btn-outline-secondary rounded-3 me-1" title="Upravit">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="<?= base_url('races/' . $race['id'] . '/years/' . $ry['id'] . '/delete') ?>"
                      method="post" class="d-inline"
                      onsubmit="return confirm('Opravdu chcete odstranit tento ročník?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Smazat">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<div class="mt-4 d-flex justify-content-center">
    <?= $pager->links('default', 'bootstrap_pagination') ?>
</div>

<?php endif; ?>
</div>

<?= $this->endSection() ?>