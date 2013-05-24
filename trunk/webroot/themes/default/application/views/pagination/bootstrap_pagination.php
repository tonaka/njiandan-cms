<div class="pagination">
	<ul>
    <?php if ($first_page): ?>
        <li><a href="<?php echo str_replace('{page}', 1, $url) ?>">&lsaquo;&nbsp;第一页</a></li>
    <?php endif ?>
    <?php if ($previous_page): ?>
        <li><a href="<?php echo str_replace('{page}', $previous_page, $url) ?>">&lt;</a></li>
    <?php endif ?>
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <?php if ($i == $current_page): ?>
            <li class="disabled"><a><?php echo $i ?></a></li>
        <?php else: ?>
            <li><a href="<?php echo str_replace('{page}', $i, $url) ?>"><?php echo $i ?></a></li>
        <?php endif ?>
    <?php endfor ?>
    <?php if ($next_page): ?>
        <li><a href="<?php echo str_replace('{page}', $next_page, $url) ?>">&gt;</a></li>
    <?php endif ?>
    <?php if ($last_page): ?>
        <li><a href="<?php echo str_replace('{page}', $last_page, $url) ?>">最后一页&nbsp;&rsaquo;</a></li>
    <?php endif ?>
	</ul>
</div>