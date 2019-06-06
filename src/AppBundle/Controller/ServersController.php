<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Servers;
use phpseclib\Net\SSH2;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Server controller.
 *
 * @Route("servers")
 */
class ServersController extends Controller
{
    /**
     * Lists all server entities.
     *
     * @Route("/", name="servers_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $servers = $em->getRepository('AppBundle:Servers')->findAll();

        return $this->render('servers/index.html.twig', array(
            'servers' => $servers,
        ));
    }

    /**
     * Creates a new server entity.
     *
     * @Route("/new", name="servers_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $server = new Servers();
        $form = $this->createForm('AppBundle\Form\ServersType', $server);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($server);
            $em->flush();

            return $this->redirectToRoute('servers_index');
        }

        return $this->render('servers/new.html.twig', array(
            'server' => $server,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a server entity.
     *
     * @Route("/{id}", name="servers_show")
     * @Method("GET")
     */
    public function showAction(Servers $server)
    {
        $deleteForm = $this->createDeleteForm($server);

        return $this->render('servers/show.html.twig', array(
            'server' => $server,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing server entity.
     *
     * @Route("/edit/{id}", name="servers_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Servers $server)
    {
        $editForm = $this->createForm('AppBundle\Form\ServersType', $server);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('servers_index');
        }

        return $this->render('servers/edit.html.twig', array(
            'server' => $server,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a server entity.
     *
     * @Route("/delete/{id}", name="servers_delete")
     */
    public function deleteAction(Request $request, Servers $server)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($server);
            $em->flush();
        return $this->redirectToRoute('servers_index');


    }
    /**
     * Deletes a user entity.
     *
     * @Route("/restart/{id}", name="servers_restart",options={"expose"=true})
     */
    public function RestartServer (Servers $servers)
    {
        $connection = new SSH2($servers->getIp(), $servers->getSshPort());
        try {
            $connection->login($servers->getSshUser(), $servers->getSshPassword());
        } catch (\Exception $e) {
            //echo $e->getMessage();
        }
        if ($connection->isConnected()) {
            $connection->exec('sudo shutdown -r now  ');

        }
      return new JsonResponse('ok');
    }
}
