<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Ob\HighchartsBundle\Highcharts\Highchart;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $monitoring = count($em->getRepository('AppBundle:Servers')->findBy(array('type'=> "Monitoring")));
        $scanner = count($em->getRepository('AppBundle:Servers')->findBy(array('type'=> "Scanner")));
        $ids = count($em->getRepository('AppBundle:Servers')->findBy(array('type'=> "IDS")));


        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        $ob->title->text('Servers');


        $data = array(
            array('Monitoring', $monitoring),
            array('Scanner', $scanner),
            array('Refuse', $ids),
        );
        $ob->series(array(array('type' => 'pie', 'data' => $data)));

        return $this->render('default/index.html.twig', array(
            'chart' => $ob
        ));
    }
}
