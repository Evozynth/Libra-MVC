<?php
/**
 * A guestbook controller as an example to show off some basic controller and model-stuff.
 * 
 * @package LibraCore
 */
class CCGuestbook extends CObject implements IController {
    private $pageTitle = 'Libra Guestbook Example';
    private $pageheader = '<h1>Guestbook example</h1><p>Showing off how to implement a guestbook in Libra.</p>';
    private $pageMessages = '<h2>Current messages</h2>';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Implementing interface IController. All controllers must have an index action.
     */
    public function Index() {
        $formAction = $this->request->CreateUrl('guestbook/handler');
        $this->pageForm = '
            <form action="'.$formAction.'" method="post">
                <p>
                    <label>Message:<br>
                    <textarea name="newEntry"></textarea></label>
                </p>
                <p>
                    <input type="submit" name="doAdd" value="Add message">
                    <input type="submit" name="doClear" value="Clear all messages">
                    <input type="submit" name="doCreate" value="Create table">
                </p>
            </form>
        ';
        
        $this->data['title'] = $this->pageTitle;
        $this->data['main'] = $this->pageheader . $this->pageForm . $this->pageMessages;
        
        $guestbook = $this->ReadAllFromDatabase();
        foreach ($guestbook as $val) {
            $this->data['main'] .= '<div style="background-color: #f6f6f6; border: 1px solid #ccc; margin-bottom: 1em; padding: 1em;"><p>At: '.$val['created'].'</p><p>'.$val['entry'].'</p></div>'."\n";

        }
    }
    
    /**
     * Add entry to the guestbook
     */
    public function Handler() {
        if (isset($_POST['doAdd'])) {
            $this->SaveNewToDatabase(strip_tags($_POST['newEntry']));
            // $entry = strip_tags($_POST['newEntry']);
            // $time = date('r');
            // $_SESSION['guestbook'][] = array('time' => $time, 'entry' => $entry);
        } elseif (isset($_POST['doClear'])) {
            $this->DeleteAllFromDatabase();
        } elseif (isset($_POST['doCreate'])) {
            $this->CreateTableInDatabase();
        }
        header('Location: ' . $this->request->CreateUrl('guestbook'));
    }
    
    /**
     * Save a new entry to database
     */
    private function CreateTableInDatabase() {
        try {
            $db = new PDO($this->config['database'][0]['dsn']);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            
            $stmt = $db->prepare("CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));");
            $stmt->execute();
        } catch (Exception $e) {
            die("Failed to open database: " . $this->config['database'][0]['dsn'] . "<br>" . $e);
        }
    }
    
    /**
     * Save a new entry to database
     */
    private function SaveNewToDatabase($entry) {
        $db = new PDO($this->config['database'][0]['dsn']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        
        $stmt = $db->prepare('INSERT INTO Guestbook (entry) VALUES (?);');
        $stmt->execute(array($entry));
        if($stmt->rowCount() != 1) {
            die("Failed to insert new guestbook item into database.");
        }
    }
    
    /**
     * Delete all entries in database
     */
    private function DeleteAllFromDatabase() {
        $db = new PDO($this->config['database'][0]['dsn']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        
        $stmt = $db->prepare('DELETE FROM Guestbook;');
        $stmt->execute();
    }
    
    /**
     * Read all entries from the database
     */
    private function ReadAllFromDatabase() {
        try {
            $db = new PDO($this->config['database'][0]['dsn']);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $db->prepare('SELECT * FROM Guestbook ORDER BY id DESC');
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (Exception $e) {
            return array();
        }
    }
    
}