<h1>Blog</h1>
<p>All blog-like list of all content with the type "post", <a href="<?=create_url("content")?>">View all content</a>.</p>

<?php if ($contents != null): ?>
    <?php foreach ($contents as $val):?>
        <h2><?=esc($val['title'])?></h2>
        <p class="smaller-text"><em>posted on <?=$val['created']?> by <?=$val['owner']?></em></p>
        <p><?=filter_data($val['data'], $val['filter'])?></p>
        <p class="smaller-text silent"><a href="<?=create_url("content/edit/{$val['id']}")?>">edit</a></p>
    <?php endforeach; ?>
<?php else: ?>
    <p>no posts exists.</p>
<?php endif; ?>