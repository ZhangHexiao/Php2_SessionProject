<?php
namespace StrangeController\Controllers;

use Ascmvc\Mvc\Controller;

class FooController extends Controller
{
    public function indexAction($vars = null)
    {


        return "This is foo";
    }
}