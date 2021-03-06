<?php

namespace Pms\CoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PurchaseOrderItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PurchaseOrderItemRepository extends EntityRepository
{
    public function getAll()
    {
        return $this->findAll();
    }

    public function create($data)
    {
        $this->_em->persist($data);
        $this->_em->flush();
    }

    public function delete($data)
    {
        $this->_em->remove($data);
        $this->_em->flush();
    }

    public function update($data)
    {
        $this->_em->persist($data);
        $this->_em->flush();
        return $this->_em;
    }

    public function totalQuantity($item)
    {
        $total = $this->_em->getRepository('PmsCoreBundle:PurchaseOrderItem')
            ->createQueryBuilder('poi')
            ->select('SUM(poi.quantity) as totalQuantity')
            ->where('poi.status = 1')
            ->andWhere('poi.item = ?1')
            ->setParameter('1', $item);
        $totalItemQuantity = $total->getQuery()->getResult();

        return $totalItemQuantity;
    }
}
