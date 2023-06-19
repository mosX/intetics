<?php
    namespace App\Controllers;
    
    class Controller{
        protected function view($view=null, $params = null){
            if(!$view) return;
            
            $arr = explode('.',$view);
            $path = BASE_DIR.DS.'views'.DS.implode(DS,$arr).'.php';
            
            if (file_exists($path)) {
                if($params){
                    foreach($params as $key=>$value){
                        ${$key} = $value;
                    }
                }
                
                require_once $path;
            }
        }
        
        protected function csrf_token(){
            return bin2hex(random_bytes(10));
        }
    }
?>