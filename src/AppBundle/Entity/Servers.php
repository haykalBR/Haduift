<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servers
 *
 * @ORM\Table(name="servers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServersRepository")
 */
class Servers
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=50)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15)
     */
    private $ip;
    /**
     * @var string
     *
     * @ORM\Column(name="sshUser", type="string", length=255)
     */
    private $sshUser;
    /**
     * @var string
     *
     * @ORM\Column(name="sshPassword", type="string", length=255)
     */
    private $sshPassword;
    /**
     * @var int
     *
     * @ORM\Column(name="sshPort", type="integer")
     */
    private $sshPort;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getSshUser()
    {
        return $this->sshUser;
    }

    /**
     * @param string $sshUser
     */
    public function setSshUser($sshUser)
    {
        $this->sshUser = $sshUser;
    }

    /**
     * @return string
     */
    public function getSshPassword()
    {
        return $this->sshPassword;
    }

    /**
     * @param string $sshPassword
     */
    public function setSshPassword($sshPassword)
    {
        $this->sshPassword = $sshPassword;
    }

    /**
     * @return int
     */
    public function getSshPort()
    {
        return $this->sshPort;
    }

    /**
     * @param int $sshPort
     */
    public function setSshPort($sshPort)
    {
        $this->sshPort = $sshPort;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }



}

