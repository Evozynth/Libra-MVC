<h1>Edit Group</h1>
<?php if ($profile['hasRoleAdmin']): ?>
<p>You can view and update the group information.</p>
    <?=$group_form?>
    <p>Group were created at <?=$editGroup['created']?> and last updated at <?=$editGroup['updated']?>.</p>
    <p>There are <?=count($usersInGroup)?> member(s) of this group</p>
    <ul>
    <?php foreach ($usersInGroup as $user): ?>
        <li><?=$user['name']?> <a class="smaller-text" href="<?=create_url('acp/removeFromGroup/'.$user['id'].'/'.$user['idGroups'])?>">Remove from group</a></li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>You don't have permission to access this page.</p>
<?php endif; ?>