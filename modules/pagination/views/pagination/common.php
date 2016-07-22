   <div class="page-effect">
    <?php if ($first_page !== FALSE): ?>
        <a class="arrow" href="<?php echo HTML::chars($page->url($first_page)) ?>" rel="first"><?php echo __('首页') ?></a>
    <?php else: ?>
        <a class="arrow"><?php echo __('首页') ?></a>
    <?php endif ?>

    <?php if ($previous_page !== FALSE): ?>
        <a href="<?php echo HTML::chars($page->url($previous_page)) ?>" rel="prev" class="moreWidth arrow"><?php echo __('上一页') ?></a>
    <?php else: ?>
        <a  class="moreWidth arrow"><?php echo __('上一页') ?></a>
    <?php endif ?>

    <?php if ($total_pages < 10): /* « Previous  1 2 3 4 5 6 7 8 9 10 11 12  Next » */ ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == $current_page): ?>
                 <a class="current"><?php echo $i ?></a>
            <?php else: ?>
                  <a href="<?php echo HTML::chars($page->url($i)) ?>" ><?php echo $i ?></a>
            <?php endif ?>
        <?php endfor ?>
    <?php elseif ($current_page < 7): /* « Previous  1 2 3 4 5 6 7 8 … 25 26  Next » */ ?>
        <?php for ($i = 1; $i <= 8; $i++): ?>
            <?php if ($i == $current_page): ?>
                <a class="current"><?php echo $i ?></a>
            <?php else: ?>
                 <a href="<?php echo HTML::chars($page->url($i)) ?>" ><?php echo $i ?></a>
            <?php endif ?>
        <?php endfor ?>
        <i>&hellip;</i>
        <a href="<?php echo HTML::chars($page->url($total_pages-1)) ?>"><?php echo $total_pages - 1 ?></a>
        <a href="<?php echo HTML::chars($page->url($total_pages)) ?>"><?php echo $total_pages ?></a>
    <?php elseif ($current_page > $total_pages - 8): /* « Previous  1 2 … 20 21 22 23 24 25 26  Next » */ ?>
        <a href="<?php echo HTML::chars($page->url(1)) ?>">1</a>
        <a href="<?php echo HTML::chars($page->url(2)) ?>">2</a>
        <i>&hellip;</i>
        <?php for ($i = $total_pages - 6; $i <= $total_pages; $i++): ?>
            <?php if ($i == $current_page): ?>
                <a class="current"><?php echo $i ?></a>
            <?php else: ?>
                <a href="<?php echo HTML::chars($page->url($i)) ?>" ><?php echo $i ?></a>
            <?php endif ?>
        <?php endfor ?>
    <?php else: /* « Previous  1 2 … 7 8 9 10 11 … 25 26  Next » */ ?>
        <a href="<?php echo HTML::chars($page->url(1)) ?>">1</a>
        <a href="<?php echo HTML::chars($page->url(2)) ?>">2</a>
        <i>&hellip;</i>
        <?php for ($i = $current_page - 2; $i <= $current_page + 2; $i++): ?>
            <?php if ($i == $current_page): ?>
                <a class="current"><?php echo $i ?></a>
            <?php else: ?>
                <a href="<?php echo HTML::chars($page->url($i)) ?>" ><?php echo $i ?></a>
            <?php endif ?>
        <?php endfor ?>
        <i>&hellip;</i>
        <a href="<?php echo HTML::chars($page->url($total_pages-1)) ?>"><?php echo $total_pages - 1 ?></a>
        <a href="<?php echo HTML::chars($page->url($total_pages)) ?>"><?php echo $total_pages ?></a>
    <?php endif ?>

    <?php if ($next_page !== FALSE): ?>
       <a href="<?php echo HTML::chars($page->url($next_page)) ?>" class="arrow" rel="next"><?php echo __('下一页') ?></a>
    <?php else: ?>
        <a class="arrow"><?php echo __('下一页') ?></a>
    <?php endif ?>

    <?php if ($last_page !== FALSE): ?>
        <a href="<?php echo HTML::chars($page->url($last_page)) ?>" rel="last" class="arrow"><?php echo __('最后一页') ?></a>
    <?php else: ?>
        <a class="arrow"><?php echo __('最后一页') ?></a>
    <?php endif ?>

    </div>

