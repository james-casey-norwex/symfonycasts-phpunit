<?php
/**
 * Created by PhpStorm.
 * User: james.casey
 * Date: 1/28/2019
 * Time: 8:41 AM
 */

namespace AppBundle\Exception;


class NotABuffetException extends \Exception
{
    protected $message = 'please do not mix the carnivorous and non-carnivorous.';
}