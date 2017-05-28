<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 5/28/17
 * Time: 4:24 PM
 */

namespace WebBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        dump('homepage');
        die;
    }
}