<h1>Edit User Profile</h1>
<?php if ($profile['hasRoleAdmin']): ?>
<p>You can view and update this users profile information.</p>

    <?=$profile_form?>
    <p>User were created at <?=$editUser['created']?> and last updated at <?=$editUser['updated']?>.</p>
    <p>User is member of <?=count($editUser['groups'])?> group(s).</p>
    <ul>
    <?php foreach ($editUser['groups'] as $group): ?>
        <li><?=$group['name']?> <a class="smaller-text" href='<?=create_url('acp/removeFromGroup/'.$editUser['id'].'/'.$group['id'])?>'>remove</a></li>
    <?php endforeach; ?>
    </ul>
    <p>Add user to group: </p>
    <?php if (!empty($avaliableGroups)): ?>
        <ul>
        <?php foreach ($avaliableGroups as $group): ?>
                <li><?=$group['name']?> <a class="smaller-text" href='<?=create_url('acp/addUserToGroup/'.$editUser['id'].'/'.$group['id'])?>'>Add</a></li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No more groups available</p>
    <?php endif; ?>
<?php else: ?>
    <p>You don't have permission to access this page.</p>
<?php endif; ?>