<?php

class TestController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        /**
         * Insert to database
         */
        $insert = new Test();
        $insert->name = 'test ' . Test::count() . time();
        $insert->save();

        $insert->name = 'test 123456789-' . time();
        $insert->save();
        $test = Test::find(array(
            'sort' => array('name' => 1),
            'limit' => 4,
        ));

        // set to another view
        $this->view->pick('index/index');
        $this->view->data = $test;
    }

}

