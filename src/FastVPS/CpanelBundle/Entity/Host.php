<?php

namespace FastVPS\CpanelBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

use Doctrine\ORM\Mapping as ORM;

/** @Entity
 * @ORM\Table(name="hosts")
 * @ORM\Entity(repositoryClass="FastVPS\CpanelBundle\EntityRepository\HostRepository")
 */
class Host {

    /**
     * @Id @Column(type="integer", unique=true, nullable=false)
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(length=140, type="string", name="hostname", unique=true, nullable=false)
     */
    private $hostName;

    /** @Column(type="datetime", name="creation_date") */
    private $creationDate;


    public function __construct() {
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setHostName($name) {
        $this->hostName = $name;
    }

    public function getHostName() {
        return $this->hostName;
    }

    public function setCreationDate() {
        $this->creationDate = new \DateTime();
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

}