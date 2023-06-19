<?php
namespace App;
use App\DB;

class Facade {
    private $_db;
    function run(){
        $this->_db = DB::getInstance()->connect();
        
        $this->initSession();
        
        $this->parsePath();        
        $this->page();
        $this->output();
    }

    public function initSession(){
        session_start();
    }

    protected function page() {
        if(!empty($this->_path['0'])){
            $this->_controller = str_replace('-', '_', $this->_path['0']);
            if (!empty($this->_path['1'])) {
                $this->_action = str_replace('-', '_', $this->_path['1']);
            } else {
                $this->_action = 'index';
            }
        } else {
            $this->_action = 'index';
            $this->_controller = 'index';
        }
        
        $path = __DIR__ . DS . 'Controllers' .  DS . $this->_controller . 'Controller.php';
        $namespace = 'App\\Controllers\\'. $this->_controller . 'Controller';
        
        if (file_exists($path)) {
            $objName = $this->_controller . 'Controller';                        
            $actName = $this->_action . 'Action';
            
            if (method_exists($namespace,$actName)) {
                $this->controller = new $namespace($this);
                
                ob_start();
                    $this->controller->$actName();
                    unset($this->controller);
                    $this->maincontent = ob_get_contents();
                ob_end_clean();                
                
                return;
            }
        }
    }

    function output() {
        if ($this->_controller == 'error') {
            header('HTTP/1.0 404 Not Found');
        }

        include(BASE_DIR . DS . 'templates' . DS . 'template.php');
    }

    protected function parsePath() {
        $REQUEST_URI = $_SERVER["REQUEST_URI"];

        if (!empty($_SERVER['QUERY_STRING']))
            $REQUEST_URI = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER["REQUEST_URI"]);

        $path = explode('/', strtolower($REQUEST_URI));
        array_shift($path);
        if (substr($REQUEST_URI, -1) != '/' && 'GET' == $_SERVER['REQUEST_METHOD']) {
            $filename = $path[count($path)-1];

            if (!preg_match("/^.+\..{2,5}$/", $filename)) {
                @header('HTTP/1.1 301 Moved Permanently');
                redirect($REQUEST_URI . '/' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
                die();
            }
        }

        if (empty($path[count($path)-1]))
            array_pop($path);

        $this->_path = $path;
    }

    
}
?>