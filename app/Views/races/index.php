<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>



<!-- HERO -->
<div class="hero-banner">
    <i class="bi bi-bicycle hero-icon-big"></i>
    <div class="container">
        <div class="hero-eyebrow">
            <i class="bi bi-trophy-fill"></i> Cyklistická databáze
        </div>
        <h1 class="hero-title">Cyklistické <span>závody</span><br>světa</h1>
        <p class="hero-subtitle">Kompletní přehled profesionálních cyklistických závodů, jejich ročníků, etap a výsledků.</p>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="num"><?= number_format(count($races ?? []) > 0 ? 2135 : 0) ?><span>+</span></div>
                <div class="lbl">Závodů</div>
            </div>
            <div class="hero-stat">
                <div class="num">12<span>K+</span></div>
                <div class="lbl">Ročníků</div>
            </div>
            <div class="hero-stat">
                <div class="num">20<span>K+</span></div>
                <div class="lbl">Etap</div>
            </div>
        </div>
    </div>
</div>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title"><i class="bi bi-flag-fill text-danger"></i> Přehled závodů</h2>
    </div>

    <?php if (empty($races)): ?>
        <div class="alert alert-info">Žádné závody nebyly nalezeny.</div>
    <?php else: ?>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
        <?php foreach ($races as $race): ?>
        <div class="col">
            <a href="<?= base_url('races/' . $race['id'] . '/years') ?>" class="race-card">
                <div class="race-card-accent"></div>
                <div class="race-card-body">
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <?= flagSpan($race['country']) ?>
                        <span class="race-card-name"><?= esc($race['default_name']) ?></span>
                    </div>
                    <?php if ($race['type']): ?>
                        <span class="race-card-badge"><?= esc($race['type']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="race-card-footer">
                    <i class="bi bi-calendar3"></i> Zobrazit ročníky
                    <i class="bi bi-chevron-right ms-auto"></i>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        <?= $pager->links('default', 'bootstrap_pagination') ?>
    </div>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>