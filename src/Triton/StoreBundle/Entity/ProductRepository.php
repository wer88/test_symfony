<?php
// Triton/StoreBundle/Repository/ProductRepository.php
namespace Triton\StoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM TritonStoreBundle:Product p ORDER BY p.name ASC')
            ->getResult();
    }

    public function findAllBigPrice()
    {
    	return $this->getEntityManager()
    		->createQuery('SELECT p FROM TritonStoreBundle:Product p WHERE p.price > :price ORDER BY p.price ASC')->setParameter('price', '19.99')
    		->getResult();
    }

    public function findOneByIdJoinedToCategory($id)
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT p, c FROM TritonStoreBundle:Product p JOIN p.category c WHERE p.id = :id')
			->setParameter('id', $id);		

		try {
			return $query->getSingleResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
}
?>