<?php
    namespace App\Controllers;
    use App\Controllers\Controller;
    
    use App\Model\Entity;
    
    class formController extends Controller{
        public function indexAction(){
            if($_SERVER['REQUEST_METHOD'] == "POST"){                
                $value = strip_tags(trim($_POST['value']));
                if($_POST['_csrf_token'] == $_SESSION['_csrf']){

                    $db_table = new Entity();
                    $db_table->setValue($value);
                    if($db_table->save()){                        
                        //TODO SEND EMAIL
                        $message = `
                            You added data successfully;
                            Your ID is `.$db_table->getID().`
                        `;
                        mail(
                            'some@gmail.com',
                            'Some Subject',
                            $message,                            
                        );

                        //TODO send SMS are providing by APIs using curl usually. Mayby you have specific SMS provider, i can realize it's API here

                        redirect("/form/success?id=".(int)$db_table->getID());                        
                    }

                    return false;
                }
            }else{
                $_SESSION['_csrf'] = $this->csrf_token();            
                $this->view('index.index',['csrf'=>$_SESSION['_csrf']]);
            }
        }

        public function successAction(){
            $id = (int)$_GET['id'];
            if($id){
                $db_table = new Entity();
                $db_table->find($id);
            }
            $this->view('index.success_result',['entity'=>$db_table]);
        }
    }
?>