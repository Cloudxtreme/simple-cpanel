<?php

namespace FastVPS\CpanelBundle\EntityRepository;

use Doctrine\ORM\EntityRepository;

class HostRepository extends EntityRepository {

    public function findAll() {

        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('q')
            ->from('FastVPSCpanelBundle:Host', 'q')
            ->orderBy('q.hostName', 'ASC')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $result;

    }

    public function findOneById($id) {

        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->setParameter('id', $id)
            ->select('q')
            ->from('FastVPSCpanelBundle:Host', 'q')
            ->where('q.id = :id')
            ->orderBy('q.hostName', 'ASC')
            ->getQuery()
            ->getSingleResult();



        return $result;

    }

}