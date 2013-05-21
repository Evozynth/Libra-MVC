<?php
/**
 * Admin Control Panel
 * 
 * @package LibraCore
 */
class CCAdminControlPanel extends CObject implements IController {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Show admin control panel.
     */
    public function Index() {
        $users = $this->adminUser->GetAllUsers();
        $groups = $this->adminGroup->GetAllGroups();
        
        $this->views->SetTitle('ACP: Admin Control Panel')
                    ->AddInclude(__DIR__.'/index.tpl.php', array(
                        'profile' => $this->user,
                        'users' => $users,
                        'groups' => $groups,
                    ), 'primary');
    }
    
    /**
     * Create a new user.
     */
    public function CreateUser() {
        $form = new CFormUserCreate($this);
        if ($form->Check() === false) {
            $this->AddMessage('notice', 'You must fill in all values.');
            $this->RedirectToController('createuser');
        }
        $this->views->SetTitle('Create user')
                    ->AddInclude(__DIR__ . '/createUser.tpl.php', array('form' => $form->GetHTML()));
    }
    
    /**
     * Perform creation of a user as callback on a submitted form.
     * 
     * @param CForm $form The form that was submitted
     */
    public function DoCreateUser($form) {
        if ($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
            $this->AddMessage('error', 'Password does not match or is empty.');
            $this->RedirectToController('createuser');
        } elseif ($this->user->Create($form['acronym']['value'],
                               $form['password']['value'],
                               $form['name']['value'],
                               $form['email']['value']
                               )) {
            $this->AddMessage('success', "You have successfully created a new account.");
            $this->RedirectToController();
        }
    }
    
    /**
     * Save updates to profile information.
     * 
     * @param CForm $form The that was submitted.
     */
    public function DoProfileSave($form) {
        $this->adminUser['name'] = $form['name']['value'];
        $this->adminUser['email'] = $form['email']['value'];
        $ret = $this->adminUser->Save();
        $this->AddMessage($ret, 'Saved profile.', 'Failed saving profile.');
        $this->RedirectToController('edituser/'.$this->adminUser->editUser['id']);
    }
    
    /**
     * Controller to edit user information.
     * 
     * @param int $id The id of the user to edit.
     */
    public function EditUser($id) {
        $this->adminUser->GetUser($id);
        $groups = $this->adminGroup->GetGroupUserNotIn($id);
        
        $form = new CFormUserProfile($this, $this->adminUser);
        
        if ($form->Check() === false) {
            $this->AddMessage('notice', 'Some fields did not validate and the form could not be processed.');
            $this->RedirectToController('edituser/'.$id);
        }

        $this->views->SetTitle('Edit User Profile')
                    ->AddInclude(__DIR__ . '/editProfile.tpl.php', array(
                        'profile'           => $this->user,
                        'editUser'          => $this->adminUser,
                        'avaliableGroups'   => $groups,
                        'profile_form'      => $form->GetHTML(),
                    ));
    }
    
    /**
     * Change the users password
     * 
     * @param CForm $form The form submitted to edit user password.
     */
    public function DoChangePassword($form) {
        if ($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
            $this->AddMessage('error', 'Password does not match or is empty.');
        } else {
            $ret = $this->adminUser->ChangePassword($form['password']['value']);
            $this->AddMessage($ret, 'Saved new password.', 'Failed updating password');
        }
        $this->RedirectToController('edituser/'.$this->adminUser->editUser['id']);
    }
    
    /**
     * Delete a user.
     * 
     * @param int $id The id of the user to delete.
     */
    public function DeleteUser($id) {
        if ($this->user['hasRoleAdmin']) {
            $this->adminUser->DeleteUser($id);
        }
        $this->RedirectToController();
    }
    
    /**
     * Controller to edit group information.
     * 
     * @param int $id The id of the group.
     */
    public function EditGroup($id) {
        $this->adminGroup->GetGroup($id);
        $usersInGroup = $this->adminGroup->GetUsersInGroup($id);
        $form = new CFormGroup($this, $this->adminGroup);

        if ($form->Check() === false) {
            $this->AddMessage('notice', 'Some fields did not validate and the form could not be processed.');
            $this->RedirectToController('editGroup/'.$id);
        }

        $this->views->SetTitle('Edit Group Details')
                    ->AddInclude(__DIR__ . '/editGroup.tpl.php', array(
                        'profile'       => $this->user,
                        'editGroup'     => $this->adminGroup,
                        'usersInGroup'  => $usersInGroup,
                        'group_form'    => $form->GetHTML(),
                    ));
    }
    
    /**
     * Saves the group information to database.
     * 
     * @param CForm $form The form submitted to edit the group.
     */
    public function DoGroupSave($form) {
        $this->adminGroup['name'] = $form['name']['value'];
        $ret = $this->adminGroup->SaveGroup();
        $this->AddMessage($ret, 'Saved group.', 'Failed saving group.');
        $this->RedirectToController('editGroup/'.$this->adminGroup['id']);
    }
    
    /**
     * Method to delete a group.
     * 
     * @param int $id The id of the group to delete.
     */
    public function DeleteGroup($id) {
        $this->adminGroup->DeleteGroup($id);
        $this->session->UnsetEditGroup();
        $this->RedirectToController();
    }
    
    /**
     * Removes a user from a group.
     * 
     * @param int $userId Id of the user.
     * @param int $groupId Id of the group.
     */
    public function RemoveFromGroup($userId, $groupId) {
        $ret = $this->adminUser->RemoveFromGroup($userId, $groupId);
        $this->AddMessage($ret, 'Removed from group', 'Failed to remove from group');
        //$this->RedirectToController('edituser/'.$userId);
        $this->RedirectToController();
    }
    
    /**
     * Adds a user to a group.
     * 
     * @param int $userId Id of the user.
     * @param int $groupId Id of the group.
     */
    public function AddUserToGroup($userId, $groupId) {
        $ret = $this->adminUser->AddUserToGroup($userId, $groupId);
        $this->AddMessage($ret, 'User was added to group', 'Failed to add user to group');
        $this->RedirectToController('edituser/'.$userId);
    }
    
    /**
     * Create a new group.
     */
    public function CreateGroup() {
        $form = new CFormGroupCreate($this);
        if ($form->Check() === false) {
            $this->AddMessage('notice', 'You must fill in all values.');
            $this->RedirectToController('creategroup');
        }
        $this->views->SetTitle('Create Group')
                    ->AddInclude(__DIR__ . '/createGroup.tpl.php', array('form' => $form->GetHTML()));
    }
    
    /**
     * Perform creation of a user as callback on a submitted form.
     * 
     * @param CForm $form The form that was submitted
     */
    public function DoCreateGroup($form) {
        if ($this->adminGroup->CreateGroup($form['acronym']['value'], $form['name']['value'])) {
            $this->AddMessage('success', "You have successfully created a new group.");
            $this->RedirectToController();
        } else {
            $this->AddMessage('notice', "Failed to create group.");
            $this->RedirectToController('creategroup');
        }
    }
    
}
