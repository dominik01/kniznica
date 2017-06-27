<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{

    //INTERNAL  METHODS

    protected $params;
    protected $session;

    protected function getRepo($name){
        if(empty($this->params['em'])) $this->params['em'] = $this->getDoctrine()->getManager();
        $repo = $this->params['em']->getRepository('AppBundle:'.ucfirst($name));
        return $repo;
    }

    protected function getPostData($request){
        $retval = array();
        if(!empty($request)){
            $post = $request->request->all();
            $content = $request->getContent();
            if(!empty($post)) $retval['post'] = $post;
            if(!empty($content)) $retval['content'] = $content;
        }
        return $retval;
    }

    protected function getSession(){
        if (empty($this->session)){
            $this->session = new Session();
        }
        return $this->session;
    }

    //END

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * ZOZNAM VSEKYCH KNIH
     */
    public function indexAction(Request $request)
    {
        $msg = $this->getSession()->getFlashBag()->get('msg');
        if(!empty($msg)){
            $msg = $msg[0];
        }
        $books = $this->getRepo('Book')->findAll();
        return $this->render('AppBundle:Landing:index.html.twig', array('books' => $books, 'msg' => $msg));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * OBRAZOVKA NA PRIDAVANIE KNIHY
     */
    public function createNewBookAction(Request $request){

        return $this->render('AppBundle:Landing:newbook.html.twig', array());
    }

    /**
     * @param Request $request
     * @param $type
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * EDITACIA ALEBO ULOZENIE NOVEJ KNIHY
     */
    public function saveBookAction(Request $request, $type){
        $data = $this->getPostData($request);
        //AK JE AKCIA PRIDAJ
        if($type == 1){
            $this->getRepo('Book')->addNewBook($data['post']);
            $this->getSession()->getFlashBag()->add('msg', 'added');
        }
        //Ak je akcia EDITUJ
        if($type == 2){
            $retval = $this->getRepo('Book')->editBook($data['post']);
            $msg = ($retval == 1) ? 'edited' : 'error';
            $this->getSession()->getFlashBag()->add('msg', $msg);
        }

        return $this->redirectToRoute('kniznica_landing');

    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * OBRAZOVKA PRE EDITACIU KNIHY
     */
    public function editBookAction($id){
        $book = $this->getRepo('Book')->find($id);
        if(!empty($book)){
            return $this->render('AppBundle:Landing:editbook.html.twig', array('book' => $book));
        }
        else{
            $this->getSession()->getFlashBag()->add('msg', 'error');
            return $this->redirectToRoute('kniznica_landing');
        }

    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * VYMAZANIE KNIHY podla ID
     */
    public function deleteBookAction($id){
        $retval = $this->getRepo('Book')->deleteBook($id);
        $msg = ($retval == 1) ? 'deleted' : 'error';
        $this->getSession()->getFlashBag()->add('msg', $msg);
        return $this->redirectToRoute('kniznica_landing');
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * DETAIL KNIHY PODLA ID
     */
    public function detailBookAction($id){
        $book = $this->getRepo('Book')->find($id);
        if(!empty($book)){
            return $this->render('AppBundle:Landing:detailBook.html.twig', array('book' => $book));
        }
        else{
            $this->getSession()->getFlashBag()->add('msg', 'error');
            return $this->redirectToRoute('kniznica_landing');
        }
    }
}
