<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = ($raceYear !== null);
$title  = $isEdit ? 'Upravit ročník' : 'Přidat ročník';
$action = $isEdit
    ? base_url('races/' . $race['id'] . '/years/' . $raceYear['id'] . '/update')
    : base_url('races/' . $race['id'] . '/years/store');
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('races') ?>">Závody</a></li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('races/' . $race['id'] . '/years') ?>"><?= esc($race['default_name']) ?></a>
        </li>
        <li class="breadcrumb-item active"><?= $title ?></li>
    </ol>
</nav>

<div class="row justify-content-center">
<div class="col-lg-7">

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <h2 class="h5 mb-0"><i class="bi bi-<?= $isEdit ? 'pencil' : 'plus-circle' ?> me-2"></i><?= $title ?></h2>
    </div>
    <div class="card-body p-4">

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ((array)session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= $action ?>" method="post" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>

            <!-- Skryté pole: id závodu -->
            <input type="hidden" name="id_race" value="<?= esc($race['id']) ?>">

            <!-- Neaktivní pole: obecný název závodu -->
            <div class="form-floating mb-3">
                <input type="text"
                       class="form-control bg-light"
                       id="default_name"
                       value="<?= esc($race['default_name']) ?>"
                       disabled
                       readonly>
                <label for="default_name">Obecný název závodu</label>
            </div>

            <!-- Název závodu v daném ročníku -->
            <div class="form-floating mb-3">
                <input type="text"
                       class="form-control <?= session('errors.real_name') ? 'is-invalid' : '' ?>"
                       id="real_name"
                       name="real_name"
                       placeholder="Název závodu v ročníku"
                       value="<?= esc(old('real_name', $raceYear['real_name'] ?? '')) ?>"
                       required>
                <label for="real_name">Název závodu v daném ročníku <span class="text-danger">*</span></label>
                <?php if (session('errors.real_name')): ?>
                    <div class="invalid-feedback"><?= esc(session('errors.real_name')) ?></div>
                <?php endif; ?>
            </div>

            <!-- Ročník (dropdown) -->
            <div class="form-floating mb-3">
                <select class="form-select <?= session('errors.year') ? 'is-invalid' : '' ?>"
                        id="year"
                        name="year"
                        required>
                    <option value="">— Vyberte ročník —</option>
                    <?php foreach ($years as $y => $label): ?>
                        <option value="<?= $y ?>"
                            <?= (string)(old('year', $raceYear['year'] ?? '')) === (string)$y ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="year">Ročník <span class="text-danger">*</span></label>
                <?php if (session('errors.year')): ?>
                    <div class="invalid-feedback"><?= esc(session('errors.year')) ?></div>
                <?php endif; ?>
            </div>

            <!-- Datum od -->
            <div class="form-floating mb-3">
                <input type="date"
                       class="form-control"
                       id="start_date"
                       name="start_date"
                       value="<?= esc(old('start_date', $raceYear['start_date'] ?? '')) ?>">
                <label for="start_date">Datum zahájení</label>
            </div>

            <!-- Datum do -->
            <div class="form-floating mb-3">
                <input type="date"
                       class="form-control"
                       id="end_date"
                       name="end_date"
                       value="<?= esc(old('end_date', $raceYear['end_date'] ?? '')) ?>">
                <label for="end_date">Datum ukončení</label>
                <div class="form-text">Pro jednodenní závod zadejte stejné datum jako zahájení.</div>
            </div>

            <!-- Kategorie UCI Tour (dropdown z DB) -->
            <div class="form-floating mb-3">
                <select class="form-select <?= session('errors.uci_tour') ? 'is-invalid' : '' ?>"
                        id="uci_tour"
                        name="uci_tour"
                        required>
                    <option value="">— Vyberte kategorii UCI Tour —</option>
                    <?php foreach ($uciTourTypes as $id => $name): ?>
                        <option value="<?= $id ?>"
                            <?= (string)(old('uci_tour', $raceYear['uci_tour'] ?? '')) === (string)$id ? 'selected' : '' ?>>
                            <?= esc($name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="uci_tour">Kategorie (UCI Tour) <span class="text-danger">*</span></label>
                <?php if (session('errors.uci_tour')): ?>
                    <div class="invalid-feedback"><?= esc(session('errors.uci_tour')) ?></div>
                <?php endif; ?>
            </div>

            <!-- Kategorie závodníků (E = Elite, U = Under 23, J = Junior) -->
            <div class="form-floating mb-3">
                <select class="form-select <?= session('errors.category') ? 'is-invalid' : '' ?>"
                        id="category"
                        name="category"
                        required>
                    <option value="">— Vyberte kategorii závodníků —</option>
                    <?php
                    $cats = ['E' => 'Elite', 'U' => 'Under 23', 'J' => 'Junior'];
                    foreach ($cats as $val => $label):
                        $selected = (old('category', $raceYear['category'] ?? '')) === $val ? 'selected' : '';
                    ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="category">Kategorie závodníků <span class="text-danger">*</span></label>
            </div>

            <!-- Pohlaví -->
            <div class="form-floating mb-3">
                <select class="form-select" id="sex" name="sex">
                    <option value="">— Vyberte pohlaví —</option>
                    <?php
                    $sexOpts = ['M' => 'Muži', 'W' => 'Ženy'];
                    foreach ($sexOpts as $val => $label):
                        $selected = (old('sex', $raceYear['sex'] ?? '')) === $val ? 'selected' : '';
                    ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="sex">Pohlaví</label>
            </div>

            <!-- Země (ISO kód) -->
            <div class="form-floating mb-3">
                <input type="text"
                       class="form-control <?= session('errors.country') ? 'is-invalid' : '' ?>"
                       id="country"
                       name="country"
                       placeholder="Kód země"
                       maxlength="5"
                       value="<?= esc(old('country', $raceYear['country'] ?? '')) ?>"
                       required>
                <label for="country">Kód země (ISO 2, např. cz, fr) <span class="text-danger">*</span></label>
            </div>

            <!-- Logo (upload) -->
            <div class="mb-4">
                <label for="logo" class="form-label fw-semibold">
                    Logo závodu
                    <?php if ($isEdit && $raceYear['logo']): ?>
                        <small class="text-muted fw-normal">(ponechte prázdné pro zachování stávajícího)</small>
                    <?php endif; ?>
                </label>
                <?php if ($isEdit && $raceYear['logo']): ?>
                    <div class="mb-2">
                        <img src="<?= base_url('uploads/logos/' . esc($raceYear['logo'])) ?>"
                             alt="Aktuální logo"
                             class="logo-thumb border rounded p-1">
                    </div>
                <?php endif; ?>
                <input type="file"
                       class="form-control"
                       id="logo"
                       name="logo"
                       accept="image/*">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Uložit změny' : 'Přidat ročník' ?>
                </button>
                <a href="<?= base_url('races/' . $race['id'] . '/years') ?>"
                   class="btn btn-outline-secondary">
                    Zrušit
                </a>
            </div>

        </form>
    </div>
</div>

</div><!-- /col -->
</div><!-- /row -->

<?= $this->endSection() ?>
