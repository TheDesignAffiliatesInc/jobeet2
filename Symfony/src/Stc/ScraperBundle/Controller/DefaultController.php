<?php

namespace Stc\ScraperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('StcScraperBundle:Default:index.html.twig');
    }

    public function themeAction()
    {
        return $this->render('StcScraperBundle:Default:index_simple.html.twig');
    }
}
