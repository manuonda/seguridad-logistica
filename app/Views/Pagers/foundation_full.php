<?php $pager->setSurroundCount(2) ?>

    <ul class="pagination">
    <?php if ($pager->hasPrevious()) : ?>
        <li class="page-item">
            <a class="page-link" href="<?= $pager->getFirst() ?>"  aria-label="<?= lang('Pager.first') ?>">
                <span aria-hidden="true"><?= lang('Pager.first') ?></span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>">
                <span aria-hidden="true"><?= lang('Pager.previous') ?></span>
            </a>
        </li>
    <?php endif ?>

    <?php 
    $lastLink = 0; 
    foreach ($pager->links() as $link) : ?>
        <li <?= $link['active'] ? 'class="page-item active"' : 'page-item ' ?>>
            <a  class="page-link"href="<?= $link['uri'] ?>" data-value="<?= $link['title'] ?>">
                <?= $link['title'] ?>
            </a>
        </li>
        
    <?php 
    $lastLink = intval($link['title'])+1;
    endforeach 
    
    ?>

    <?php if ($pager->hasNext()) : ?>
  
        <li class="page-item">
            <a class="page-link" href="<?= $pager->getNext() ?>"  data-value="<?= $lastLink ?>" aria-label="<?= lang('Pager.next') ?>">
                <span aria-hidden="true"><?= lang('Pager.next') ?></span>
            </a>
        </li>
         <li class="page-item">
            <a class="page-link" href="<?= $pager->getLast() ?>" data-value="<?= $pager->getLast() ?>"  aria-label="<?= lang('Pager.last') ?>">
                <span aria-hidden="true"><?= lang('Pager.last') ?></span>
            </a>
        </li>
     
    <?php endif ?>
    </ul>