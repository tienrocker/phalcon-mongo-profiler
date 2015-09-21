<?php

class Test extends \Collection
{
    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $created_at;

    /**
     *
     * @var integer
     */
    public $updated_at;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'test';
    }

    public function initialize()
    {
        $this->setConnectionService('mongo');
    }

}
