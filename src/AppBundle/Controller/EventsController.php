<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Events;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Event controller.
 *
 * @Route("events")
 */
class EventsController extends Controller
{
    /**
     * Lists all event entities.
     *
     * @Route("/", name="events_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('AppBundle:Events')->findAll();

        return $this->render('events/index.html.twig', array(
            'events' => $events,
        ));
    }
    /**
     *
     * @Route("/clander", name="events_calendar")
     */
    public function Calendar()
    {
        return $this->render('events/calendar.html.twig');
    }
    /**
     * Creates a new event entity.
     *
     * @Route("/new", name="events_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $event = new Events();
        $form = $this->createForm('AppBundle\Form\EventsType', $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('events_show', array('id' => $event->getId()));
        }

        return $this->render('events/new.html.twig', array(
            'event' => $event,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="events_show",options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(Events $event)
    {

        return $this->render('events/show.html.twig', array(
            'event' => $event,
        ));
    }

    /**
     * Displays a form to edit an existing event entity.
     *
     * @Route("/edit/{id}", name="events_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Events $event)
    {
        $editForm = $this->createForm('AppBundle\Form\EventsType', $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $event->setStatus(false);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('events_edit', array('id' => $event->getId()));
        }

        return $this->render('events/edit.html.twig', array(
            'event' => $event,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a event entity.
     *
     * @Route("/delete/{id}", name="events_delete")
     */
    public function deleteAction(Request $request, Events $event)
    {


            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();

        return $this->redirectToRoute('events_index');
    }


}
