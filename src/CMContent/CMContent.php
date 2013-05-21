<?php
/**
 * A model for content stored in database.
 * 
 * @package LibraCore
 */
class CMContent extends CObject implements IHasSQL, ArrayAccess, IModule {
    
    /**
     * Properties
     */
    public $data;
    
    /**
     * Constructor
     */
    public function __construct($id = null) {
        parent::__construct();
        if ($id) {
            $this->LoadById($id);
        } else {
            $this->data = array();
        }
    }
    
    /**
     * Implementing ArrayAccess for $this->data
     */
    public function offsetSet($offset, $value) { if (is_null($offset)){ $this->data[] = $value; } else { $this->data[$offset] = $value; }}
    public function offsetExists($offset) { return isset($this->data[$offset]); }
    public function offsetUnset($offset) { unset($this->data[$offset]); }
    public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; } 
    
    /**
     * Implementing interface IHasSQL. Encapsulating all SQL used by this class.
     * 
     * @param string $key The string that is the key of the wanted SQL-entry in the array.
     */
    public static function SQL($key = null, $args = null) {
        $order_order = isset($args['order-order']) ? $args['order-order'] : 'ASC';
        $order_by = isset($args['order-by']) ? $args['order-by'] : 'id';
        $queries = array(
            'drop table content'    => "DROP TABLE IF EXISTS Content;",
            'create table content'  => "CREATE TABLE IF NOT EXISTS Content (
                                            id INTEGER PRIMARY KEY,
                                            key TEXT KEY, 
                                            type TEXT,
                                            title TEXT,
                                            data TEXT,
                                            filter TEXT,
                                            idUser INT,
                                            created DATETIME default (datetime('now')),
                                            updated DATETIME default NULL,
                                            deleted DATETIME default NULL,
                                            FOREIGN KEY (idUser) REFERENCES User(id)
                                        );",
            'insert content'            => "INSERT INTO Content (key, type, title, data, filter, idUser) VALUES (?,?,?,?,?,?);",
            'select * by id'            => "SELECT c.*, u.acronym AS owner FROM Content AS c INNER JOIN User AS u ON c.idUser=u.id WHERE c.id=? AND deleted IS NULL;",
            'select * by key'           => "SELECT c.*, u.acronym AS owner FROM Content AS c INNER JOIN User AS u ON c.idUser=u.id WHERE c.key=? AND deleted IS NULL;",
            'select * by type'          => "SELECT c.*, u.acronym AS owner FROM Content AS c INNER JOIN User AS u ON c.idUser=u.id WHERE c.type=? AND deleted IS NULL ORDER BY {$order_by} {$order_order};",
            'select *'                  => "SELECT c.*, u.acronym AS owner FROM Content AS c INNER JOIN User AS u ON c.idUser=u.id WHERE deleted IS NULL;",
            'update content'            => "UPDATE Content SET key=?, type=?, title=?, data=?, filter=?, updated=datetime('now') WHERE id=?;",
            'update content as deleted' => "UPDATE Content SET deleted=datetime('now') WHERE id=?;",
        );
        if (!isset($queries[$key])) {
            throw new Exception("No such SQL query, key '$key' was not found.");
        }
        return $queries[$key];
    }
    
    /**
     * Implementing interface IModule. managa install/update/deinstall and equal actions.
     */
    public function Manage($action = null) {
        switch ($action) {
            case 'install':
                try {
                    $this->db->ExecuteQuery(self::SQL('drop table content'));
                    $this->db->ExecuteQuery(self::SQL('create table content'));
                    $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world', 'post', 'Hello World', "This is a demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
                    $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-again', 'post', 'Hello World Again', "This is another demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
                    $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-once-more', 'post', 'Hello World Once More', "This is one more demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
                    $this->db->ExecuteQuery(self::SQL('insert content'), array('home', 'page', 'Home Page', "This is a demo page, this could be your personal home-page.", 'plain', $this->user['id']));
                    $this->db->ExecuteQuery(self::SQL('insert content'), array('about', 'page', 'About Page', "This is a demo page, this could be your personal about-page.", 'plain', $this->user['id']));
                    $this->db->ExecuteQuery(self::SQL('insert content'), array('download', 'page', 'Download Page', "This is a demo page, this could be your personal download-page.", 'plain', $this->user['id']));
                    $this->db->ExecuteQuery(self::SQL('insert content'), array('bbcode', 'page', 'Page with BBCode', "This is a demo page with some BBCode-formatting.\n\n[b]Text in bold[/b] and [i]text in italic[/i] and [url=http://www.dbwebb.se]a link to dbwebb.se[/url]. You can also include images using bbcode, such as the Lydia logo: [img]http://dbwebb.se/lydia/current/themes/core/logo_80x80.png[/img]", 'bbcode', $this->user['id']));
                    $this->db->ExecuteQuery(self::SQL('insert content'), array('htmlpurify', 'page', 'Page with HTMLPurifier', "This is a demo page with some HTML code intended to run through <a href='http://htmlpurifier.org/' target=\"_blank\">HTMLPurify</a>. Edit the source and insert HTML code and see if it works.\n\n<b>Text in bold</b> and <i>text in italic</i> and <a href='http://dbwebb.se'>a link to dbwebb.se</a>. JavaScript, like this: <javascript>alert('hej');</javascript> should however be removed.", 'htmlpurify', $this->user['id']));
                    $this->db->ExecuteQuery(self::SQL('insert content'), array('markdown', 'page', 'Page with Markdown', "This is a demo page with some Markdown text intended to run through [Markdown](https://github.com/michelf/php-markdown). Edit the source and insert Markdown text and see if it works.\n\n**Text in bold** and *text in italic* and [a link to dbwebb.se](http://dbwebb.se).", 'markdown', $this->user['id']));
                    $this->db->ExecuteQuery(self::SQL('insert content'), array('smartypants', 'page', 'Page with SmartyPants', "This is a demo page with some html code intended to run through <a href='http://michelf.ca/projects/php-smartypants/' target='_blank'>SmartyPants Typographer</a>. Edit the source and insert html code and see if it works.\n\nStraight qoutes is converted to curly -- \"Text in double qoutes\" and 'Text in single qoutes'. Three consecutive dots (...) into an ellipsis entity.", 'smartypants', $this->user['id']));
                    $this->db->ExecuteQuery(self::SQL('insert content'), array('Combined filters', 'page', 'Page with Combined filters', "This is a demo page with some html code intended to run through smartypants, bbcode, htmlpurify and clickable. Edit the source and insert html code to see if it works.\n\nStraight qoutes is converted to curly -- \"Text in double qoutes\" and 'Text in single qoutes'. Three consecutive dots (...) into an ellipsis entity.\n\nLinks should atutomatically be created -- http://php.net/.\n\n It should work also with bbcode mixed in: [b]this should be bould text[/b]. \n\n", 'smartypants, clickable, htmlpurify, bbcode', $this->user['id']));
                    return array('success', 'Successfully created the database tables and created a deafult "Hello World" blog post, owned by you.');
                } catch(Exception $e) {
                    return array('error', 'Could not create table. Make sure site/data is writeable');
                    //die("$e<br>Failed to open database: " . $this->config['database'][0]['dsn']);
                }
                break;
            
            default:
                throw new Exception('Unsupported action for this module.');
                break;
        }
        
    }
    
    /**
     * Save content. If it has a id, use it to update current entry or else insert new entry.
     *
     * @returns boolean true if success else false.
     */
    public function Save() {
        $msg = null;
        if ($this['id']) {
            $this->db->ExecuteQuery(self::SQL('update content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this['id']));
            $msg = 'update';
        } else {
            $this->db->ExecuteQuery(self::SQL('insert content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this->user['id']));
            $this['id'] = $this->db->LastInsertId();
            $msg = 'created';
        }
        $rowcount = $this->db->RowCount();
        if ($rowcount) {
            $this->AddMessage('success', "Successfully {$msg} content '" . htmlEnt($this['key']) . "'.");
        } else {
            $this->AddMessage('error', "Failed to {$msg} content '" . htmlEnt($this['key']) . "'.");
        }
        return $rowcount === 1;
    }
    
    public function Delete() {
        if ($this['id']) {
            $this->db->ExecuteQuery(self::SQL('update content as deleted'), array($this['id']));
        }
        $rowcount = $this->db->RowCount();
        if ($rowcount) {
            $this->AddMessage('success', "Successfully set content '" . htmlent($this['key']) . "' as deleted.");
        } else {
            $this->AddMessage('error', "Failed to set content '". htmlent($this['key']) ."' as deleted.");
        }
        return $rowcount ===1;
    }
    
    /**
     * Load content by id.
     * 
     * @param int $id The id of the content.
     * @return boolean True if success else false.
     */
    public function LoadById($id) {
        $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by id'), array($id));
        if (empty($res)) {
            $this->AddMessage('error', "Failed to load content with id '$id'.");
            return false;
        } else {
            $this->data = $res[0];
        }
        return true;
    }
    
    /**
     * List all content.
     * 
     * @return array with listing or null if empty.
     */
    public function ListAll($args = null) {
        try {
            if (isset($args) && isset($args['type'])) {
                return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by type', $args), array($args['type']));
            } else {
                return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select *', $args));
            }
        } catch(Exception $e) {
            echo $e;
            return null;
        }
    }
    
    /**
     * Filter content according to a filter. Uses CTextFilter to process data.
     * 
     * @param string $data String of text to filter and format according its filter settings.
     * @param string $filter The type of filter to use, plain, ...
     * @return string With the filtered data.
     */
    public static function Filter($data, $filter) {
        $filter = explode(',', trim($filter, ' ,'));
        return CTextFilter::Filter($data, $filter);
       
    }
    
    /**
     * Get the filtered content.
     * 
     * @return string The filtered data.
     */
    public function GetFilteredData() {
        return self::Filter($this['data'], $this['filter']);
    }
}