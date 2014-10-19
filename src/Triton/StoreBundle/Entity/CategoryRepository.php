<?php

namespace Triton\StoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
	public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c.id , c.name FROM TritonStoreBundle:Category c ORDER BY c.name ASC')
            ->getResult();
    }

}
