<h1>Admin Control Panel</h1>
<?php if (isset($profile) && $profile['hasRoleAdmin']): ?>
<p><strong>Users</strong> <a class="smaller-text" href='<?=create_url('acp/createuser')?>'>Create new user</a></p>
<table style="font-size: 0.75em;">
    <thead>
        <th>Id</th>
        <th>Acronym</th>
        <th>Name</th>
        <th>Email</th>
        <th>Created</th>
        <th>Updated</th>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?=$user['id']?></td>
            <td><?=$user['acronym']?></td>
            <td><?=$user['name']?></td>
            <td><?=$user['email']?></td>
            <td><?=$user['created']?></td>
            <td><?=$user['updated']?></td>
            <td><a href='<?=create_url('acp/edituser/'.$user['id'])?>'>edit</a></td>
            <td><a href='<?=create_url('acp/deleteuser/'.$user['id'])?>'>delete</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p><strong>Groups</strong> <a class="smaller-text" href='<?=create_url('acp/createGroup')?>'>Create new group</a></p>
<table style="font-size: 0.75em;">
    <thead>
        <th>Id</th>
        <th>Acronym</th>
        <th>Name</th>
        <th>Created</th>
        <th>Updated</th>
    </thead>
    <tbody>
    <?php foreach ($groups as $group): ?>
        <tr>
            <td><?=$group['id']?></td>
            <td><?=$group['acronym']?></td>
            <td><?=$group['name']?></td>
            <td><?=$group['created']?></td>
            <td><?=$group['updated']?></td>
            <td><a href='<?=create_url('acp/editgroup/'.$group['id'])?>'>edit</a></td>
            <td><a href='<?=create_url('acp/deletegroup/'.$group['id'])?>'>delete</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
    
<?php else: ?>
<p>You don't have access to this page</p>
<?php endif; ?>