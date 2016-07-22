   <div class="page-effect">
  

    <?php if ($previous_page !== FALSE): ?>
        <a href="<?php echo HTML::chars($page->url($previous_page)) ?>" rel="prev" class="moreWidth arrow"><?php echo __('上一页') ?></a>
    <?php else: ?>
        <a  class="moreWidth arrow"><?php echo __('上一页') ?></a>
    <?php endif ?>
    <?if($total_pages <= 9) {?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == $current_page): ?>
                 <a class="current"><?php echo $i ?></a>
            <?php else: ?>
                  <a href="<?php echo HTML::chars($page->url($i)) ?>" ><?php echo $i ?></a>
            <?php endif ?>
        <?php endfor ?>
    <?}else{?>
        <?if($current_page <= 5) {?>
               <?php for ($i = 1; $i <= 10; $i++): ?>
                <?php if ($i == $current_page): ?>
                     <a class="current"><?php echo $i ?></a>
                <?php else: ?>
                      <a href="<?php echo HTML::chars($page->url($i)) ?>" ><?php echo $i ?></a>
                <?php endif ?>
            <?php endfor ?>   
        <?}else{?>
                      <?
                      $jian = 5;
                      $jia = 4;
                      if($current_page+$jia > $total_pages)  {
                          $jian += $current_page+$jia-$total_pages;
                          $jia = intval($total_pages)-intval($current_page);
                      }
                      ?>
                   <?php $temp = ($current_page - $jian) ? ($current_page - $jian) : 1; $temp2 = (($current_page + $jia) <= $total_pages)? ($current_page + $jia) : $total_pages;for ($i = $temp; $i <= $temp2; $i++): ?>
                    <?php if ($i == $current_page): ?>
                         <a class="current"><?php echo $i ?></a>
                    <?php else: ?>
                          <a href="<?php echo HTML::chars($page->url($i)) ?>" ><?php echo $i ?></a>
                    <?php endif ?>
                <?php endfor ?>    
            <?}?>
    <?}?>
                  
    <?php if ($next_page !== FALSE): ?>
       <a href="<?php echo HTML::chars($page->url($next_page)) ?>" class="arrow" rel="next"><?php echo __('下一页') ?></a>
    <?php else: ?>
        <a class="arrow"><?php echo __('下一页') ?></a>
    <?php endif ?>

   

    </div>

