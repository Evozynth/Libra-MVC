<h1>Index Controller</h1>
<p>Welcome to Libra index controller.</p>

<h2>Download</h2>
<p>You can download Libra from github.</p>
<blockquote>
    <code>git clone git://github.com/libra3d/Libra-MVC.git</code>
</blockquote>
<p>You can review its source directly on github: <a href="https://github.com/libra3d/Libra-MVC">https://github.com/libra3d/Libra-MVC</a></p>

<h2>Installation</h2>
<p>First you have to make the data-directory writable. This is the place where Libra needs
to be able to write and create files.</p>
<blockquote>
    <code>cd Libra-MVC; chmod 777 site/data</code>
</blockquote>

<p>Second, Libra has some modules that need to be initialized. You can do this through a 
controller. Point your browser to the following link.</p>
<blockquote>
    <a href="<?=create_url('modules/install')?>">modules/install</a>
</blockquote>