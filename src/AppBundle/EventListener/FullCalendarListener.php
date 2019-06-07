<?php
/**
 * Created by PhpStorm.
 * User: haykel
 * Date: 24/05/19
 * Time: 09:12
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Toiba\FullCalendarBundle\Entity\Event;
use Toiba\FullCalendarBundle\Event\CalendarEvent;

class FullCalendarListener
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected $request;

    public function setRequest(RequestStack $request_stack)
    {
        $this->request = $request_stack->getCurrentRequest();
    }

    public function loadEvents(CalendarEvent $calendar)
    {
        $events=$this->em->getRepository('AppBundle:Events')->findAll();
        foreach ($events as $event){
            $event= new Event($event->getAction().'-'.$event->getId(),$event->getBeginDate(),$event->getEndDate());
            $event->setAllDay(true);
            $calendar->addEvent($event);

        }

    }
}
