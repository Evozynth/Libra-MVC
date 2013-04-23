<?php
/**
 * A model for a guestbook, to show of some basic controller and model-stuff
 * 
 * @package LibraCore
 */
class CMGuestbook extends CObject implements IHasSQL {
    
    /**
     * Constructor
     */
    public function __contruct() {
        parent::__construct();
    }
    
    /**
     * Implementing interface IHasSQL. Encapsulating all SQL used by this class.
     * 
     * @param string $key the string that is the key of the wanted SQL-entry in the array.
     */
    public static function SQL($key = null) {
        $queries = array(
            'create table guestbook' => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));",
            'insert into guestbook' => 'INSERT INTO Guestbook (entry) VALUES (?);',
            'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id DESC;',
            'delete from guestbook' => 'DELETE FROM Guestbook',
        );
        if (!isset($queries[$key])) {
            throw new Exception("No such SQL query, key '$key' was not found.");
        }
        return $queries[$key];
    }
    
    
    /**
     * Creates a new guestbook table if not already exists.
     */
    public function Init() {
        try {
            $this->db->ExecuteQuery(self::SQL('create table guestbook'));
            $this->session->AddMessage('notice', 'Successfully created the database tables (or left them untouched if they already existed).');
        } catch (Exception $e) {
            die("$e<br>Failed to open database: " . $this->config['database'][0]['dsn']);
        }
    }
    
    /**
     * Save a new entry to the guestbook and save to database.
     */
    public function Add($entry) {
        $this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($entry));
        if($this->db->rowCount() != 1) {
            die("Failed to insert new guestbook item into database.");
        }
        $this->session->AddMessage('success', 'Successfully inserted new message.');
    }
    
    /**
     * Delete all entries in guestbook table in the database.
     */
    public function DeleteAll() {
        $this->db->ExecuteQuery(self::SQL('delete from guestbook'));
        $this->session->AddMessage('info', 'Removed all messages from the database table.');
    }
    
    /**
     * Read all entries from the guestbook table in the database.
     */
    public function ReadAll() {
        try {
            $this->db->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from guestbook'));
        } catch (Exception $e) {
            return array();
        }
    }
    
}


