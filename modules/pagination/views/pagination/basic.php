<p class="pagination">

	<?php if ($first_page !== FALSE): ?>
		<a href="<?php echo HTML::chars($page->url($first_page)) ?>" rel="first" class="first"><?php echo __('首页') ?></a>
	<?php else: ?>
		<span><?php echo __('首页') ?></span>
	<?php endif ?>

	<?php if ($previous_page !== FALSE): ?>
		<a href="<?php echo HTML::chars($page->url($previous_page)) ?>" rel="prev" class="prev"><?php echo __('上页') ?></a>
	<?php else: ?>
		<span><?php echo __('上页') ?></span>
	<?php endif ?>

	<?php for ($i = 1; $i <= $total_pages; $i++): ?>

		<?php if ($i == $current_page): ?>
			<strong><?php echo $i ?></strong>
		<?php else: ?>
			<a href="<?php echo HTML::chars($page->url($i)) ?>"><?php echo $i ?></a>
		<?php endif ?>

	<?php endfor ?>

	<?php if ($next_page !== FALSE): ?>
		<a href="<?php echo HTML::chars($page->url($next_page)) ?>" rel="next" class="next"><?php echo __('下页') ?></a>
	<?php else: ?>
		<span><?php echo __('下页') ?></span>
	<?php endif ?>

	<?php if ($last_page !== FALSE): ?>
		<a href="<?php echo HTML::chars($page->url($last_page)) ?>" rel="last" class="last"><?php echo __('末页') ?></a>
	<?php else: ?>
		<span><?php echo __('末页') ?></span>
	<?php endif ?>

</p><!-- .pagination -->