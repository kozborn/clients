<?php

namespace PiotrK\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use PiotrK\ClientBundle\Lib\Paginator;

class DefaultController extends Controller {

  public $entitiesLimit = 10;

  public function indexAction(Request $request, $page) {
    $em = $this->getDoctrine()->getManager();

    $entities = $em->getRepository('ClientBundle:Client')->getClients($page, $this->entitiesLimit);
    $total = $entities->count();
    $page = $page <= 0 ? 1 : $page;
    $offset = $this->getOffset($page, $total);

    $paginator = new Paginator($page, $total, $this->entitiesLimit);
    $entities = $entities->slice($offset, $this->entitiesLimit);

    return $this->render('ClientBundle:Default:index.html.twig', array(
          'entities' => $entities,
          'paginator' => $paginator,
    ));
  }

  public function reportAction(Request $request) {
    $filters = $request->request->all();
    $page = isset($filters['page']) ? $filters['page'] : 1;
    $em = $this->getDoctrine()->getManager();

    $entities = $em->getRepository('ClientBundle:Client')->getClientsFiltered($filters);
    $total = $entities->count();
    $page = $page <= 0 ? 1 : $page;
    $offset = $this->getOffset($page, $total);

    $entities = $entities->slice($offset, $this->entitiesLimit);
    $paginator = new Paginator($page, $total, $this->entitiesLimit);

    return $this->render('ClientBundle:Default:table.html.twig', array(
          'entities' => $entities,
          'paginator' => $paginator,));
  }

  public function removeAction(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();
    $client = $em->getRepository('ClientBundle:Client')->find($id);
    $response = new Response();
    if (!$client) {
      $json = array(
        'message' => "Client with Id: $id, cannot be found"
      );
      $response->setContent(json_encode($json));
      return $response;
    }

    $em->remove($client);
    $em->flush();
    $json = array(
      'message' => "Client with Id: $id, removed from database"
    );

    $response->setContent(json_encode($json));
    return $response;
  }

  private function getOffset($page, $total) {
    $offset = ($page - 1) * $this->entitiesLimit;
    return $offset = ($offset < $total) ? $offset : 0;
  }

}
