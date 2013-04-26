<?php
/**
 * A model for an authenticated user.
 * 
 * @package LibraCore
 */
class CMUser extends CObject implements IHasSQL {
    
    /**
     * Constructor
     */
    public function __construct($li = null) {
        parent::__construct($li);
    }
    
    /**
     * Implementing interface IHasSQL. Encapsulating all SQL used bu this class.
     * 
     * @param string $key The string that is the key of the wanted SQL-entry in the array.
     */
    public static function SQL($key=null) {
        $queries = array(
            'drop table user'           => "DROP TABLE IF EXISTS User;",
            'drop table group'          => "DROP TABLE IF EXISTS Groups;",
            'drop table user2group'     => "DROP TABLE IF EXISTS User2Groups;",
            'create table user'         => "CREATE TABLE IF NOT EXISTS User (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, email TEXT, password TEXT, created DATETIME default (datetime('now')));",
            'create table group'        => "CREATE TABLE IF NOT EXISTS Groups (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, created DATETIME default (datetime('now')));",
            'create table user2group'   => "CREATE TABLE IF NOT EXISTS User2Groups (idUser INTEGER, idGroups INTEGER, created DATETIME default (datetime('now')), PRIMARY KEY(idUser, idGroups));",
            'insert into user'          => "INSERT INTO User (acronym, name, email, password) VALUES (?,?,?,?);",
            'insert into group'         => "INSERT INTO Groups (acronym, name) VALUES (?, ?);",
            'insert into user2group'    => "INSERT INTO User2Groups (idUser, idGroups) VALUES (?,?);",
            'check user password'       => "SELECT * FROM User WHERE password=? AND (acronym=? OR email=?);",
            'get group memberships'     => "SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;",
        );
        
        if (!isset($queries[$key])) {
            throw new Exception("No such SQL query, key '$key' was not found.");
        }
        return $queries[$key];
    }
    
    /**
     * Init the database and create appropriate tables.
     */
    public function Init() {
        try {
            $this->db->ExecuteQuery(self::SQL('drop table user2group'));
            $this->db->ExecuteQuery(self::SQL('drop table group'));
            $this->db->ExecuteQuery(self::SQL('drop table user'));
            $this->db->ExecuteQuery(self::SQL('create table user'));
            $this->db->ExecuteQuery(self::SQL('create table group'));
            $this->db->ExecuteQuery(self::SQL('create table user2group'));
            $this->db->ExecuteQuery(self::SQL('insert into user'), array('root', 'The Administrator', 'stanley.svensson@gmail.com', 'root'));
            $idRootUser = $this->db->LastInsertId();
            $this->db->ExecuteQuery(self::SQL('insert into user'), array('doe', 'John Doe', 'doe@mail.com', 'doe'));
            $idDoeUser = $this->db->LastInsertId();
            $this->db->ExecuteQuery(self::SQL('insert into group'), array('admin', 'The Administrator Group'));
            $idAdminGroup = $this->db->LastInsertId();
            $this->db->ExecuteQuery(self::SQL('insert into group'), array('user', 'The User Group'));
            $idUserGroup = $this->db->LastInsertId();
            $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idAdminGroup));
            $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idUserGroup));
            $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idDoeUser, $idUserGroup));
            $this->session->AddMessage('notice', 'Successfully created the database tables and created a default admin user as root:root and an ordinary user doe:doe.');
        } catch(Exception $e) {
            die("$e<br>Failed to open database: " . $this->config['database'][0]['dsn']);
        }
    }
    
    /**
     * Login by authenticate the user and password. Store user information in session if success.
     * 
     * @param string $acronym The Email adress or user acronym.
     * @param string $password The password that should match the acronym or email adress.
     * @return boolean true if match else false.
     */
    public function Login($acronymOrEmail, $password) {
        $user = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('check user password'), array($password, $acronymOrEmail, $acronymOrEmail));
        $user = (isset($user[0])) ? $user[0] : null;
        unset($user['password']);
        if ($user) {
            $user['groups'] = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('get group memberships'), array($user['id']));
            foreach ($user['groups'] as $val) {
                if ($val['id'] == 1) {
                    $user['hasRoleAdmin'] = true;
                }
                if ($val['id'] == 2) {
                    $user['hasRoleUser'] = true;
                }
            }
            $this->session->SetAuthenticatedUser($user);
            $this->session->AddMessage('success', "Welcome '{$user['name']}'.");
        } else {
            $this->session->AddMessage('notice', "Could not login, user does not exist or password did not match.");
        }
        return ($user != null);
    }
    
    /**
     * Logout.
     */
    public function Logout() {
        $this->session->UnsetAuthenticatedUser();
        $this->session->AddMessage('success', "You have logged out");
    }
    
    /**
     * Does the session contain an authenticated user?
     * 
     * @return boolean true or false
     */
    public function IsAuthenticated() {
        return ($this->session->GetAuthenticatedUser() != false);
    }
    
    /**
     * Get profile information on user.
     * 
     * @return array with user profile or null if anonymous user.
     */
    public function GetProfile() {
        return $this->session->GetAuthenticatedUser();
    }
    
    /**
     * Get the user acronym.
     * 
     * @return string with user acronym or null
     */
    public function GetAcronym() {
        $profile = $this->GetProfile();
        return isset($profile['acronym']) ? $profile['acronym'] : null;
    }
    
    /**
     * Does the user have the admin role?
     * 
     * @return boolean true or false.
     */
    public function IsAdministrator() {
        $profile = $this->GetProfile();
        return isset($profile['hasRoleAdmin']) ? $profile['hasRoleAdmin'] : false;
    }
}
