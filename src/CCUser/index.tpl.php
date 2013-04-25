<h1>User profile</h1>
<ul>
    <li><a href="<?=create_url(null, 'init')?>">Init database, create tables and create default admin user</a></li>
    <li><a href="<?=create_url(null, 'login', 'root/root')?>">Login as root:root (should work)</a></li>
    <li><a href="<?=create_url(null, 'login', 'stanley.svensson@gmail.com/root')?>">Login as stanley.svensson@gmail.com:root (should work)</a></li>
    <li><a href="<?=create_url(null, 'login', 'admin/root')?>">Log in as admin:root (should fail)</a></li>
    <li><a href="<?=create_url(null, 'login', 'root/admin')?>">Login as root:admin (should fail)</a></li>
    <li><a href="<?=create_url(null, 'login', 'admin@gmail.com/root')?>">Login as admin@gmail.com:root (Shold fail, wrong email)</a></li>
    <li><a href="<?=create_url(null, 'logout')?>">Logout</a></li>
</ul>
<p>This is what is known on the current user.</p>

<?php if ($is_authenticated): ?>
    <p>User is authenticated.</p>
    <pre><?=print_r($user, true);?></pre>
<?php else: ?>
    <p>User is anonymous and not authenticated.</p>
<?php endif; ?>
