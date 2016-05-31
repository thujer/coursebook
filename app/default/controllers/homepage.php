<?php

    namespace Runtime\App\Controller;

    use \Runtime\controller;

    if(!defined('RUNTIME_TEST'))
        die('Unauthorized access !');
    
    /**
     * Default app controller
     * @author Tomas Hujer
     */
    class homepage extends controller {

        public function defaultAction() {

            header('Location: /meeting/list');
            exit;

            return $this->render_view(array(
            ));
        }

    }
