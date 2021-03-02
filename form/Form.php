<?php


namespace parthkapatel\phpmvc\form;


use parthkapatel\phpmvc\Model;

class Form
{
    public static function begin($action,$method) : Form
    {
        echo sprintf('<form action="%s" method="%s">',$action,$method);
        return new Form();

    }
    public static function end()
    {
        echo '</form>';
    }

    public function field(Model $model,$attribute): InputField
    {
        return new InputField($model,$attribute);
    }


}