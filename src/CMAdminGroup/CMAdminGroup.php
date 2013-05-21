<?php
/**
 * A model to administrate groups.
 * 
 * @package LibraCore
 */
class CMAdminGroup extends CObject implements IHasSQL, ArrayAccess {

    /**
     * Properties
     */
    public $editGroup; // The group to be edited

    /**
     * Constructor
     */

    public function __construct($li = null) {
        parent::__construct($li);
        $editGroup = $this->session->GetEditGroup();
        $this->editGroup = is_null($editGroup) ? array() : $editGroup;
    }
    
    /**
     * Implementing ArrayAccess for $this->editGroup.
     */
    public function offsetSet($offset, $value) { if (is_null($offset)) { $this->editGroup[] = $value; } else { $this->editGroup[$offset] = $value; } }
    public function offsetExists($offset) { return isset($this->editGroup[$offset]); }
    public function offsetUnset($offset) { unset($this->editGroup[$offset]); }
    public function offsetGet($offset) { return isset($this->editGroup[$offset]) ? $this->editGroup[$offset] : null; } 

    /**
     * Implementing interface IHasSQL. Encapsulating all SQL used by this class.
     * 
     * @param string $key The string that is the key of the wanted SQL-entry in the array.
     */
    public static function SQL($key = null) {
        $queries = array(
            'select all groups'         => "SELECT * FROM Groups;",
            'select group'              => "SELECT * FROM Groups WHERE id=?;",
            'update group'              => "UPDATE Groups SET name=?, updated=datetime('now') WHERE id=?;",
            'delete group'              => "DELETE FROM Groups WHERE id=?;",
            'select groups user not in' => "SELECT * FROM Groups LEFT JOIN User2Groups ON Groups.id = User2Groups.idGroups AND User2Groups.idUser = ? WHERE User2Groups.idGroups IS NULL;",
            'select users in group'     => "SELECT * FROM User AS u INNER JOIN User2Groups AS ug ON u.id=ug.idUser WHERE ug.idGroups=?",
            'insert into group'         => "INSERT INTO Groups (acronym, name) VALUES (?, ?);",
        );

        if (!isset($queries[$key])) {
            throw new Exception("No such SQL query, key '$key' was not found.");
        }
        return $queries[$key];
    }
    
    /**
     * Get all groups in the database.
     * 
     * @return array With all available groups in database
     */
    public function GetAllGroups() {
        $groups = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select all groups'));
        return $groups;
    }
    
    /**
     * Method to load a group into the session and $li->adminGroup.
     * 
     * @param int $id The id of the group to load.
     * @return boolean true if group was successfully loaded, else false.
     */
    public function GetGroup($id) {
        $editGroup = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select group'), array($id));
        $editGroup = (isset($editGroup[0]) ? $editGroup[0] : null);
        if ($editGroup) {
            $this->editGroup = $editGroup;
            $this->session->SetEditGroup($this->editGroup);
        } else {
            $this->editGroup = null;
            $this->session->UnsetEditGroup();
        }
        return ($editGroup != null);
    }
    
    /**
     * Used to get the users that are mambers of the group.
     * 
     * @param int $id The id of the group.
     * @return array with all users member of group.
     */
    public function GetUsersInGroup($id) {
        $usersInGroup = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select users in group'), array($id));
        return $usersInGroup;
    }
    
    /**
     * A method to get all groups that user is not member of.
     * 
     * @param type $id The id of the user.
     * @return array With the groups.
     */
    public function GetGroupUserNotIn($id) {
        $groups = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select groups user not in'), array($id));
        return $groups;
    }
    
    /**
     * Save group informatiion to database.
     * 
     * @return boolean true if success, else false.
     */
    public function SaveGroup() {
        $this->db->ExecuteQuery(self::SQL('update group'), array($this['name'], $this['id']));
        return $this->db->RowCount() === 1;
    }
    
    /**
     * Deletes a group. The members of the group are removed before deleting to
     * avoid that next created group gets this group's members. 
     * 
     * @param int $id The id of the groups to remove.
     * @return boolean true if success, else false.
     */
    public function DeleteGroup($id) {
        // Remove all users from the group
        $usersInGroup = $this->GetUsersInGroup($id);
        foreach ($usersInGroup as $user) {
            $this->adminUser->RemoveFromGroup($user['id'], $id);
        }
        $this->db->ExecuteQuery(self::SQL('delete group'), array($id));
        return $this->db->RowCount() === 1;
    }
    
    /**
     * Create a new group.
     * 
     * @param string $acronym The acronym.
     * @param string $name The groups full name.
     * @return boolean true if group was created or else false and sets failure message in session.
     */
    public function CreateGroup($acronym, $name) {
        $this->db->ExecuteQuery(self::SQL('insert into group'), array($acronym, $name));
        if ($this->db->RowCount() == 0) {
            $this->AddMessage('error', 'Failed to create user.');
            return false;
        }
        return true;
    }

}