<?php
/**
 * A model to administrate users.
 * 
 * @package LibraCore
 */
class CMAdminUser extends CObject implements IHasSQL, ArrayAccess {

    /**
     * Properties
     */
    public $editUser; // The user to be edited

    /**
     * Constructor
     */

    public function __construct($li = null) {
        parent::__construct($li);
        $editUser = $this->session->GetEditUser();
        $this->editUser = is_null($editUser) ? array() : $editUser;
    }
    
    /**
     * Implementing ArrayAccess for $this->editUser.
     */
    public function offsetSet($offset, $value) { if (is_null($offset)) { $this->editUser[] = $value; } else { $this->editUser[$offset] = $value; } }
    public function offsetExists($offset) { return isset($this->editUser[$offset]); }
    public function offsetUnset($offset) { unset($this->editUser[$offset]); }
    public function offsetGet($offset) { return isset($this->editUser[$offset]) ? $this->editUser[$offset] : null; } 

    /**
     * Implementing interface IHasSQL. Encapsulating all SQL used by this class.
     * 
     * @param string $key The string that is the key of the wanted SQL-entry in the array.
     */
    public static function SQL($key = null) {
        $queries = array(
            'select all users'          => "SELECT * FROM User;",
            'select user'               => "SELECT * FROM User WHERE (id=?);",
            'update profile'            => "UPDATE User SET name=?, email=?, updated=datetime('now') WHERE id=?;",
            'update password'           => "UPDATE User SET algorithm=?, salt=?, password=?, updated=datetime('now') WHERE id=?;",
            'delete user'               => "DELETE FROM User WHERE id=?;",
            'get group memberships'     => "SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;",
            'remove user from group'    => "DELETE FROM User2Groups WHERE idUser=? AND idGroups=?;",
            'add user to group'         => "INSERT INTO User2Groups (idUser, idGroups) VALUES (?,?);",
        );

        if (!isset($queries[$key])) {
            throw new Exception("No such SQL query, key '$key' was not found.");
        }
        return $queries[$key];
    }

    /**
     * Get all users from database.
     * 
     * @return array with all users in database.
     */
    public function GetAllUsers() {
        $users = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select all users'));
        return $users;
    }

    /**
     * Get a user specified by id.
     * 
     * @param int $id The id of the user.
     * @return array with the user information.
     */
    public function GetUser($id) {
        $editUser = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select user'), array($id));
        $editUser = (isset($editUser[0]) ? $editUser[0] : null);
        unset($editUser['algorithm']);
        unset($editUser['salt']);
        unset($editUser['password']);
        if ($editUser) {
            $editUser['groups'] = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('get group memberships'), array($id));

            $this->editUser = $editUser;
            $this->session->SetEditUser($this->editUser);
        } else {
            $this->session->UnsetEditUser();
            $this->editUser = null;
        }

        return ($editUser != null);
    }
    
    /**
     * Save user profile to database and update user profile in session.
     * 
     * @return boolean true if success else false.
     */
    public function Save() {
            $this->db->ExecuteQuery(self::SQL('update profile'), array($this['name'], $this['email'], $this['id']));
            $this->session->SetEditUser($this->editUser);
            return $this->db->RowCount() === 1;
    }
    
    /**
     * Crete password.
     * 
     * @param string $plain The password in plain text to use as base.
     * @param string $algorithm Stating what algorithm to use, plain, md5, md5salt, sha1, sha1salt. Defaults to the setting of site/config.php
     * @return array With 'salt' and 'password'.
     */
    public function CreatePassword($plain, $algorithm = null) {
        $password = array(
            'algorithm' => ($algorithm ? $algorithm : CLibra::Instance()->config['hashing_algorithm']),
            'salt' => null
        );
        switch ($password['algorithm']) {
            case 'sha1salt': $password['salt'] = sha1(microtime()); $password['password'] = sha1($password['salt'].$plain); break;
            case 'md5salt': $password['salt'] = md5(microtime()); $password['password'] = md5($password['salt'].$plain); break;
            case 'sha1': $password['password'] = sha1($plain); break;
            case 'md5': $password['password'] = md5($plain); break;
            case 'plain': $password['password'] = $plain; break;
            default: throw new Exception('Unknown hashing algorithm');
        }
        return $password;
    }
    
    /**
     * Change user password.
     * 
     * @param string $password The new password.
     * @return boolean true if success else false.
     */
    public function ChangePassword($plain) {
        $password = $this->CreatePassword($plain);
        $this->db->ExecuteQuery(self::SQL('update password'), array($password['algorithm'], $password['salt'], $password['password'], $this['id']));
        return $this->db->RowCount() === 1;
    }
    
    /**
     * Delete user.
     * 
     * @param int $id The id of the user to delete.
     * @return true if success else false.
     */
    public function DeleteUser($id) {
        $this->GetUser($id);
        $groups = (isset($this->editUser['groups']) ? $this->editUser['groups'] : null);
        foreach ($groups as $group) {
            $this->RemoveFromGroup($id, $group['id']);
        }
        $this->db->ExecuteQuery(self::SQL('delete user'), array($id));
        return $this->db->RowCount() === 1;
    }
    
    /**
     * Remove a user from a group.
     * 
     * @param int $userId Id of the user.
     * @param int $groupId Id of the group.
     * @return boolean true if success else false.
     */
    public function RemoveFromGroup($userId, $groupId) {
        $this->db->ExecuteQuery(self::SQL('remove user from group'), array($userId, $groupId));
        return $this->db->RowCount() === 1;
    }
    
    /**
     * Add a user to a group.
     * 
     * @param int $userId Id of the user.
     * @param int $groupId Id of the group.
     * @return boolean true if user was successfully added to the group else false.
     */
    public function AddUserToGroup($userId, $groupId) {
        $this->db->ExecuteQuery(self::SQL('add user to group'), array($userId, $groupId));
        return $this->db->RowCount() === 1;
    }

}