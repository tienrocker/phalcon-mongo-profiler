<?php

class TestController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $test = Test::find(array(
            array('name' => '55ffab64aa9f7fa811000029'),
            'limit' => 4,
        ));

        // set to another view
        $this->view->pick('index/index');
        $this->view->data = $test;
    }

}

