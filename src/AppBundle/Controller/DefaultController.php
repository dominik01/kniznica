<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    protected $params;

    protected function getRepo($name){
        if(empty($this->params['em'])) $this->params['em'] = $this->getDoctrine()->getManager();
        $repo = $this->params['em']->getRepository('AppBundle:'.ucfirst($name));
        return $repo;
    }


    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    public function createNewBookAction(Request $request){
        $data = [
            'name' => 'Harry Potter',
            'author' => "J.K.Rowling",
            'year' => '1997',
            'gender' => 'fantasy',
            'description' => 'You are a wizard Harry...'
        ];
        $this->getRepo('Book')->addNewBook($data);
        return $this->render('AppBundle:Landing:index.html.twig', array('added'=>'1'));
    }
}
