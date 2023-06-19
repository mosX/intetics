<?php
    namespace App\Model;
    use App\DB;
    
    class Entity{
        private $id = '';
        private $value = '';
        
        public function setValue($value){
            $this->value = $value;
        }

        public function getValue(){
            return $this->value;
        }
        public function getID(){
            return $this->id;
        }

        public function find($id){
            $db = DB::getInstance();

            $db->setQuery(sprintf("SELECT * FROM `users` WHERE `users`.`id` = %d LIMIT 1", (int)$id));
            $cur = $db->query();
            $object = $cur->fetch_assoc();
            
            $this->id = $object['id'];
            $this->value = $object['value'];
        }

        public function save(){
            $db = DB::getInstance();
            
            $db->setQuery(sprintf("INSERT INTO `users` (`value`) VALUES (%s)", $db->Quote($this->value)));
            
            
            $status = $db->query();            
            
            if($status){                
                $this->id = $db->getLastInsertedID();                
            }
            
            return $status;
        }
    }
?>