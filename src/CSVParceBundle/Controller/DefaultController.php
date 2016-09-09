<?php

namespace CSVParceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CSVParceBundle\Entity\Product;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CSVParceBundle:Default:index.html.twig');
    }
}
