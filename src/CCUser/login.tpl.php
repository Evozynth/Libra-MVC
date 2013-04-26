<h1>Login</h1>
<hr>
<p>Here should a login form be, but for now you can login using these links.</p>
<ul>
    <li><a href="<?=create_url(null, 'login', 'doe/doe')?>">Login as doe (should work)</a></li>
    <li><a href="<?=create_url(null, 'login', 'root/root')?>">Login as root:root (should work)</a></li>
    <li><a href="<?=create_url(null, 'login', 'stanley.svensson@gmail.com/root')?>">Login as stanley.svensson@gmail.com:root (should work)</a></li>
    <li><a href="<?=create_url(null, 'login', 'admin/root')?>">Log in as admin:root (should fail)</a></li>
    <li><a href="<?=create_url(null, 'login', 'root/admin')?>">Login as root:admin (should fail)</a></li>
    <li><a href="<?=create_url(null, 'login', 'admin@gmail.com/root')?>">Login as admin@gmail.com:root (Shold fail, wrong email)</a></li>
    <li><a href="<?=create_url(null, 'logout')?>">Logout</a></li>
</ul>
