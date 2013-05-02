<?php
/**
 * Holding an instance of CLibra to enable use of $this in subclasses.
 * 
 * @package LibraCore
 */
class CObject {
    /**
     * Memebers
     */
    public $config;
    public $request;
    public $data;
    public $db;
    public $views;
    public $session;
    public $user;
    
    /**
     * Constructor, can be instanciated by sending in the $li reference.
     */
    protected function __construct($li = null) {
        if (!$li) {
            $li = CLibra::Instance();
        }
        $this->config   = &$li->config;
        $this->request  = &$li->request;
        $this->data     = &$li->data;
        $this->db       = &$li->db;
        $this->views    = &$li->views;
        $this->session  = &$li->session;
        $this->user     = &$li->user;
    }
    
    /**
     * Redirect to another url and store the session
     */
    protected function RedirectTo($urlOrController = null, $method = null) {
        $li = CLibra::Instance();
        if (isset($li->config['debug']['db-num-queries']) && $li->config['debug']['db-num-queries'] && isset($li->db)) {
            $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
        }
        if (isset($li->config['debug']['db-queries']) && $li->config['debug']['db-queries'] && isset($li->db)) {
            $this->session->SetFlash('database_queries', $this->db->GetQueries());
        }
        if (isset($li->config['debug']['timer']) && $li->config['debug']['timer']) {
            $this->session->SetFlash('timer', $li->timer);
        }
        $this->session->StoreInSession();
        header('Location: ' . $this->request->CreateUrl($urlOrController, $method));
    }
    
    /**
     * Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
     * 
     * @param string $method Name of the method, default is index method.
     */
    protected function RedirectToController($method = null) {
        $this->RedirectTo($this->request->controller, $method);
    }
    
    /**
     * Redirect to a controller and method. Uses RedirectTo().
     * 
     * @param string $controller Name of the controller or null for current controller.
     * @param string $method Name of the method, default is current method.
     */
    protected function RedirectToControllerMethod($controller = null, $method = null) {
        $controller = is_null($controller) ? $this->request->controller : null;
        $method = is_null($method) ? $this->request->method : null;
        $this->RedirectTo($this->request->Createurl($controller, $method));
    }
    
    /**
     * Save message in the session. Uses $this->session->AddMessage().
     * 
     * @param string $type The type of message, for example: notice, info, success, warning, error.
     * @param string $message The message.
     * @param string $alternative The message if the $type is set to false, defaults to null.
     */
    protected function AddMessage($type, $message, $alternative = null) {
        if ($type === false) {
            $type = 'error';
            $message = $alternative;
        } elseif ($type === true) {
            $type = 'success';
        }
        $this->session->AddMessage($type, $message);
    }
    
    /**
     * Create an url. Uses $this->request->CreateUrl().
     * 
     * @param string $urlOrController The relative url or the controller.
     * @param string $method The method to use, $url is then the controller or empty for current.
     * @param string $arguments The extra arguments to send to the method. 
     */
    protected function CreateUrl($urlOrController = null, $method = null, $arguments = null) {
        return $this->request->CreateUrl($urlOrController, $method, $arguments);
    }
}
