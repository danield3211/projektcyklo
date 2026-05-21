<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * Formátuje datum ročníku:
 * - jednodenní závod: jen jedno datum
 * - vícedenní: od – do
 * Formát: "5. ledna 2024" (česky)
 */
function formatRaceDates(string $startDate, string $endDate): string
{
    $start = new DateTime($startDate);
    $end   = new DateTime($endDate);

    $formatter = new IntlDateFormatter('cs_CZ', IntlDateFormatter::LONG, IntlDateFormatter::NONE);

    if ($startDate === $endDate) {
        return $formatter->format($start);
    }

    // Stejný měsíc a rok → zkrátíme
    if ($start->format('Y-m') === $end->format('Y-m')) {
        $dayFormatter = new IntlDateFormatter('cs_CZ', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'd.');
        return $dayFormatter->format($start) . ' – ' . $formatter->format($end);
    }

    return $formatter->format($start) . ' – ' . $formatter->format($end);
}

function flagSpan(string $country): string
{
    if (!$country) return '';
    return '<span class="fi fi-' . strtolower(esc($country)) . '" title="' . strtoupper(esc($country)) . '"></span>';
}
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('races') ?>">Závody</a></li>
        <li class="breadcrumb-item active"><?= esc($race['default_name']) ?></li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1 class="h3 mb-0">
        <?= flagSpan($race['country']) ?>
        <?= esc($race['default_name']) ?>
    </h1>
    <a href="<?= base_url('races/' . $race['id'] . '/years/create') ?>"
       class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Přidat ročník
    </a>
</div>

<?php if (empty($raceYears)): ?>
    <div class="alert alert-info">Pro tento závod zatím nejsou žádné ročníky.</div>
<?php else: ?>

<div class="table-responsive shadow-sm rounded">
<table class="table table-hover align-middle mb-0 bg-white">
    <thead class="table-dark">
        <tr>
            <th>Název</th>
            <th>Ročník</th>
            <th>Datum</th>
            <th>Logo</th>
            <th>Kategorie (UCI Tour)</th>
            <th>Země</th>
            <th class="text-center">Etapy</th>
            <th class="text-end">Akce</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($raceYears as $ry): ?>
        <tr>
            <td class="fw-semibold"><?= esc($ry['real_name']) ?></td>
            <td><?= esc($ry['year']) ?></td>
            <td>
                <?php
                    // Pokus o IntlDateFormatter; fallback na základní formát
                    if (class_exists('IntlDateFormatter')) {
                        echo formatRaceDates($ry['start_date'], $ry['end_date']);
                    } else {
                        $s = new DateTime($ry['start_date']);
                        $e = new DateTime($ry['end_date']);
                        echo $ry['start_date'] === $ry['end_date']
                            ? $s->format('j. n. Y')
                            : $s->format('j. n.') . ' – ' . $e->format('j. n. Y');
                    }
                ?>
            </td>
            <td>
                <?php if ($ry['logo']): ?>
                    <img src="<?= base_url('uploads/logos/' . esc($ry['logo'])) ?>"
                         alt="logo"
                         class="logo-thumb img-fluid"
                         loading="lazy">
                <?php else: ?>
                    <span class="text-muted small">—</span>
                <?php endif; ?>
            </td>
            <td>
                <?= esc($uciTourTypes[$ry['uci_tour']] ?? 'Neznámá (' . $ry['uci_tour'] . ')') ?>
            </td>
            <td>
                <?= flagSpan($ry['country']) ?>
                <span class="ms-1 text-uppercase small"><?= esc($ry['country']) ?></span>
            </td>
            <td class="text-center">
                <?php
                    // Počet etap
                    $stageCount = (new \App\Models\StageModel())
                        ->where('id_race_year', $ry['id'])
                        ->countAllResults();
                ?>
                <?php if ($stageCount > 0): ?>
                    <a href="<?= base_url('raceyears/' . $ry['id'] . '/stages') ?>"
                       class="badge bg-primary text-decoration-none fs-6">
                        <?= $stageCount ?>
                    </a>
                <?php else: ?>
                    <span class="text-muted">0</span>
                <?php endif; ?>
            </td>
            <td class="text-end">
                <a href="<?= base_url('races/' . $race['id'] . '/years/' . $ry['id'] . '/edit') ?>"
                   class="btn btn-sm btn-outline-secondary me-1" title="Upravit">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="<?= base_url('races/' . $race['id'] . '/years/' . $ry['id'] . '/delete') ?>"
                      method="post" class="d-inline"
                      onsubmit="return confirm('Opravdu chcete odstranit tento ročník?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Smazat">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<!-- Stránkování -->
<div class="mt-4 d-flex justify-content-center">
    <?= $pager->links('default', 'bootstrap_pagination') ?>
</div>

<?php endif; ?>

<?= $this->endSection() ?>
