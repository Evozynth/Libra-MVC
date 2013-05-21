<h1>Index Controller</h1>
<p>Welcome to Libra index controller.</p>

<h2>Download</h2>
<p>You can download Libra from github.</p>
<blockquote>
    <code>git clone git://github.com/libra3d/Libra-MVC.git</code>
</blockquote>
<p>You can review its source directly on github: <a href="https://github.com/libra3d/Libra-MVC">https://github.com/libra3d/Libra-MVC</a></p>

<h2>Installation</h2>
<p>First you have to make two directories writable: <code>Libra-MVC</code> and <code>Libra-MVC/site/data</code>.
The data folder is the place where Libra-MVC needs to be able to write and create files, such as the database.</p>
<blockquote>
    <code>chmod 777 Libra-MVC; cd Libra-MVC; chmod 777 site/data</code>
</blockquote>

<p>Second, Libra-MVC needs to create a <code>.htaccess</code> file to properly direct pages, this file is created in <code>Libra-MVC/</code></p>
<blockquote>
    <a href="<?=create_url('src/CCInstall/createHtaccess.php')?>">Create .htaccess</a>
</blockquote>
<?php if (isset($result['htaccess'])): ?>
<div class="<?=$result['htaccess']['class']?>"><?=$result['htaccess']['message']?></div>
<?php endif; ?>
<br>
<p>Third, Libra has some modules that need to be initialized. You can do this through a 
controller. Point your browser to the following link.</p>
<blockquote>
    <a href="<?=create_url('modules/install')?>">modules/install</a>
</blockquote>