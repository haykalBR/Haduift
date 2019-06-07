<?php
/**
 * Created by PhpStorm.
 * User: haykel
 * Date: 07/06/19
 * Time: 22:18
 */
namespace AppBundle\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use phpseclib\Net\SSH2;

Class ServerCommand extends ContainerAwareCommand{
    protected function configure()
    {
        $this
            ->setName('server:commande')
            ->setDescription('');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $events = $em->getRepository('AppBundle:Events')->findby(['status'=>false]);
        $date= new \DateTime();
        foreach ( $events as $event){
            if ($date>$event->getBeginDate()){

                $connection = new SSH2($event->getServers()->getIp(), $event->getServers()->getSshPort());
                try {
                    $connection->login($event->getServers()->getSshUser(), $event->getServers()->getSshPassword());
                } catch (\Exception $e) {
                    //echo $e->getMessage();
                }
                if ($connection->isConnected()) {
                    $connection->exec('sudo shutdown -r now  ');

                }
                $event->setStatus(true);
                $em->flush();
            }
        }

    }







}
