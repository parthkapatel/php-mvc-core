<?php


namespace parthkapatel\phpmvc;


use parthkapatel\phpmvc\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}