<?php
/**
 * Sample controller for a site builder.
 */
class CCMycontroller extends CObject implements IController {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * The page about me.
     */
    public function Index() {
        $content = new CMContent(5);
        $this->views->SetTitle('About me'. htmlent($content['title']))
                    ->AddInclude(__DIR__.'/page.tpl.php', array(
                        'content' => $content,
                    ));
    }
    
    /**
     * The guestbook.
     */
    public function Guestbook() {
        $guestbook = new CMGuestbook();
        $form = new CFormMyGuestbook($guestbook);
        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', 'The form could not be processed.');
            $this->RedirectToControllerMethod();
        } elseif ($status === true) {
            $this->RedirectToControllerMethod();
        }
        
        $this->views->SetTitle('My Guestbook')
                    ->AddInclude(__DIR__.'/guestbook.tpl.php', array(
                        'entries' => $guestbook->ReadAll(),
                        'form' => $form,
                    ));
    }
}

/**
 * Form for the guestbook.
 */
class CFormMyGuestbook extends CForm {

    /**
     * Properties
     */
    private $object;

    /**
     * Constructor
     */
    public function __construct($object) {
        parent::__construct();
        $this->object = $object;
        $this->AddElement(new CFormElementTextArea('data', array('label' => 'Add entry:')))
             ->AddElement(new CFormELementSubmit('add', array('callback' => array($this, 'DoAdd'), 'callback-args' => array($object))));
    }
    
    /**
     * Callback to add the form content to database.
     */
    public function DoAdd($form, $object) {
        return $object->Add(strip_tags($form['data']['value']));
    }

}