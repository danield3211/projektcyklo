<?php

/**
 * Uložte jako: app/Views/bootstrap_pagination.php
 * A zaregistrujte v app/Config/Pager.php:
 *
 *   public array $templates = [
 *       'default_full'   => 'App\Views\bootstrap_pagination',
 *       'default_simple' => 'App\Views\bootstrap_pagination',
 *       'bootstrap_pagination' => 'App\Views\bootstrap_pagination',
 *   ];
 */

$pager->setSurroundCount(2);
?>

<nav aria-label="Stránkování">
<ul class="pagination justify-content-center flex-wrap">

    <?php if ($pager->hasPreviousPage()): ?>
    <li class="page-item">
        <a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="První">
            <span aria-hidden="true">&laquo;&laquo;</span>
        </a>
    </li>
    <li class="page-item">
        <a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="Předchozí">
            <span aria-hidden="true">&laquo;</span>
        </a>
    </li>
    <?php else: ?>
    <li class="page-item disabled">
        <span class="page-link">&laquo;&laquo;</span>
    </li>
    <li class="page-item disabled">
        <span class="page-link">&laquo;</span>
    </li>
    <?php endif; ?>

    <?php foreach ($pager->links() as $link): ?>
    <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
        <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
    </li>
    <?php endforeach; ?>

    <?php if ($pager->hasNextPage()): ?>
    <li class="page-item">
        <a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="Další">
            <span aria-hidden="true">&raquo;</span>
        </a>
    </li>
    <li class="page-item">
        <a class="page-link" href="<?= $pager->getLast() ?>" aria-label="Poslední">
            <span aria-hidden="true">&raquo;&raquo;</span>
        </a>
    </li>
    <?php else: ?>
    <li class="page-item disabled">
        <span class="page-link">&raquo;</span>
    </li>
    <li class="page-item disabled">
        <span class="page-link">&raquo;&raquo;</span>
    </li>
    <?php endif; ?>

</ul>
</nav>
