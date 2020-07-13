<?php
    namespace Controller;

    use Model\Services\IndexService;
    use core\View;

    class IndexController{
        public function error($errorCode){
            $result = [
                'success' => false,
                'errorCode' => $errorCode,
                'msg' => 'method or class not found'
            ];

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        public function home(){
            /*echo var_dump('IndexController');
            $service = new IndexService();
            $service->home();*/

            View::render('combatInfo');
        }
    }
?>