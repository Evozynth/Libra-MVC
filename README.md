Libra - MVC
===========

Libra is a MVC (Multi View Controller) coded in php.
Created as part of a web development course at bth.se.

Installation
------------

Download Libra-MVC from github using the clone command.
Copy this line into git Bash or terminal.

`git clone git://github.com/libra3d/Libra-MVC.git`

First you have to make two directories writable: Libra-MVC and Libra-MVC/site/data.
The data folder is the place where Libra-MVC needs to be able to write and create files, such as the sqlite database.

`chmod 777 Libra-MVC; cd Libra-MVC; chmod 777 site/data`

Now you it's time to open your browser and navigate to the index page of Libra-MVC, i.e. `localhost/Libra-MVC/index.php`

Second, Libra-MVC needs to create a .htaccess file to properly direct pages, this file is created in *Libra-MVC/*   
To do this just follow the link ***Create .htaccess*** on the index page.

Third, Libra have some modules that need to be initialized.
You can do this through a controller. Point your browser to the ***modules/install*** link given on the index page.
 
If everything went fine you should see that the following three modules have been successfully installed:

* CMUser
* CMContent
* CMGuestbook

The information-boxes will tell you if the creation of the database and tables went well or if Libra-MVC was unable to create them.  
On success you will see information like login details for the created users.  
On fail it probably means you didn't change the permissions for the site/data folder.

Libra creates two default users: 

* An administrator with the name root and password root
* A user with the name doe and password doe


- - -

How to personalize your web site
--------------------------------

Libra-MVC allows you to change the content of the header-title, slogan and footer, as
well as replacing the logo and customizing the menu. You can also alter the styling/theme by writing 
your own CSS. Many customizations can be done by editing the properties of `$li->config['theme']` 
which is found in the file `site/config.php`.

###Change header-title, slogan and footer###
Open `site/config.php` in a text editor of your choice and scroll down till you see
`$li->config['theme']`. This is what holds the settings for the theme. In the array
you will find a key named `header`. It's default value is set to Libra, change this to your site's name.
In the same array you will find the keys `slogan` and `footer`, change these values to your liking.

Libra also populates the footer with debug information and useful links for web development.
You probably want to get rid of this content when your site goes live.
At the top of the file `site/config.php` you will find settings for debugging,
all starting with `$li->config['debug']`.
They are either set to true or false. True meaning that Libra will print out information about that particular area.
Simply set all these values to `false` to turn them of.
 
To remove the links, open the file `themes/grid/index.tpl.php` and delete (or comment out) the line that says `<?=get_tools()?>`.

###Change the logo###

Start by uploading your own image with the logo into the folder `site/themes/mytheme/`.
Keep in mind that the pixel dimensions of the image should not be too big, preferably the same as you want it to appear on the site.

Second, open the file `site/config.php` and scroll down to `$li->config['theme']`.
Change the value for *logo* to the name of the image you just uploaded.
Also change the values for *logo_width* and *logo_height* according to the pixel-dimensions of the image.
It's possible to set lower values which will shrink the image,
but that's not recommended because of decrease in performance and unnecessary bandwidth usage to load the image.

To change the styling of the site you need to write your own CSS. An example CSS file is provided - `site/themes/mytheme/style.css`,
that have some lines you can uncomment to see the effect.

How to create a new page
------------------------

In the sidebar on the index page of Libra you have links to all controllers and methods available. Click the link ***create*** under ***content***.
You should now be presented by a form. Let me go through the fields, top to bottom.

* **Title** - Pretty self explanatory, the title of the created page.
* **Key** - This is an unique identifier of the page, which could be used to navigate to the page (currently not implemented).
It should be a short string of text that describes the page content .
* **Content** - The page content.
* **Type** - Can be *post* or *page*. *post* would be an entry in a blog, so in this case type in *page*.
* **Filter** - The content can be filtered in various ways, both for security reasons and interpretation.
The options are:
    * plain (convert tags to html entities, this is the default if nothing is specified)
    * htmlpurify (recommended)
    * smartypants (makes your text look typographically nicer)
    * clickable (turns web addresses to clickable links)
    * bbcode (lets you write the content as bbcode, available tags are [b], [i], [u], [img] and [url])
    * markdown (converts content written in markdown to html)

You can use one or more, separated by commas i.e. *smartypants, clickable, htmlpurify, bbcode*.

After completing all the fields press *Create* - **take a note of the number** shown last in the address bar,
this number will be used shortly.

Open the file `site/src/CCMycontroller/CCMycontroller.php`.
This file contains a controller class named `CCMycontroller`.
All pages have it's own method, so we need to create one. The easiest way is to copy and modify an already existing method.
So copy the method *Index* and replace the method name to something resembling the new page, i.e. *MyNewPage*.
The method creates a new *model* `CMContent` that loads the content of a specific page. What content is loaded is
determined by the number passed in as an argument.
On the line that says `$content = new CMContent(5);` - change the number 5 to the number you previously noted down.

The method is then defining the information for the view, such as *page title* and what *template
file* to load. Change the text "About me" after `SetTitle` to the title of your page.  
This is how it could look after completing the steps:

    /**
     * My new page
     */
    public function MyNewPage() {
        $content = new CMContent(12);
        $this->views->SetTitle('Test'. htmlent($content['title']))
                    ->AddInclude(__DIR__.'/page.tpl.php', array(
                        'content' => $content,
                    ));
    }



The last step is to create a menu item that points to your new page. To do this, open `site/config.php` and
navigate down till you find `$li->config['menus']`. You will see two sets of menu definitions *'navbar'* and *'my-navbar'*.

We are going to add a new entry to *'my-navbar'*. Paste the following code on a new line after the *'guestbook'* entry.  

    'mynewpage' => array('label' => 'MyNewPage', 'url' => 'my/mynewpage'),

* The first parameter *'mynewpage'* should be unique, otherwise it overwrites previous entries with the same name.  
* *'label'* sets the text shown on the menu item.  
* *'url'* defines what the menu item links to in the form of *controller/method*. Since we created the method
*MyNewPage* in the controller class *CCMycontroller* the url becomes *'my/mynewpage'*.


    NOTE: $li->config['controllers'] is a routing table where 'my' points to 'CCMycontroller'


How to create a blog
--------------------

Libra is already prepared with a model and database table for a blog.
Parts of the blog system uses the same resources as regular pages, i.e. creation of blog posts
is done through the same page as creation of new pages and the data is stored in the same database table.

The first thing to do is to create a new method in `site/src/CCMycontroller/CCMycontroller.php`.  
Copy the code below and paste it into the class `CCMycontroller`.
    
    /**
     * The blog.
     */
    public function Blog() {
        $content = new CMContent();
        $this->views->SetTitle('My Blog')
                    ->AddInclude(__DIR__.'/blog.tpl.php', array(
                        'contents' => $content->ListAll(array('type' => 'post', 'order-by' => 'title', 'order-order' => 'DESC')),
                    ));
    }

This is what the code does:  
The method is named *Blog* and doesn't take any parameters.
A new CMContent object is created, this is the model which gives access to the blog content.
A title is set to the page and the *AddInclude* method of the *views* object loads a template file which resides in the 
same directory as the current controller. It also creates an array with the key *'contents'*, the value of this key
is the data retrieved from the *ListAll* method of *$content*, which loads all the posts from the database.

Next, we need to create the template file. Create a new file named `blog.tpl.php` in `site/src/CCMycontroller/`.

Copy and paste the code below into the new file and save it.

    <h1>Blog</h1>
    <p>All nice news and blogposts about me. <a href="<?=create_url("content")?>">View all content</a></p>

    <?php if ($contents != null): ?>
        <?php foreach ($contents as $val): ?>
            <h2><?=esc($val['title'])?></h2>
            <p class='smaller-text'><em>Posted on: <?=$val['created']?> by <?=$val['owner']?></em></p>
            <p><?=filter_data($val['data'], $val['filter'])?></p>
            <p class='smaller-text silent'><a href='<?=create_url("content/edit/{$val['id']}")?>'>edit</a></p>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts exists.</p>
    <?php endif; ?>

In this file the blog data is accessed through the variable *$contents* which is an array. The foreach loop 
goes through the array and echoes out the blog content such as *'title'*, *'created'* and *'data'*.
Links are created to an edit-page for each post and at the top a link goes to an overview page to see all the sites
pages and blog posts.

Lastly, we create a new menu item with a link to the blog.
Open `site/config.php` and scroll down to `$li->config['menus']`.  
Add this line to the *'my-navbar'* array:

    'blog'      => array('label' => 'My Blog', 'url' => 'my/blog'),

The menu should now have a new link to your blog.
