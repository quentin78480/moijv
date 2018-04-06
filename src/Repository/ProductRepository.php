<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Product::class);
    }

    public function findPaginated($page = 1) {
        $queryBuilder = $this->createQueryBuilder('p')
                ->leftJoin('p.owner', 'u')
                ->addSelect('u')
                ->leftJoin('p.tags', 't')
                ->addSelect('t')
                ->leftJoin('p.loans', 'l')
                ->where('l.status = :status1')
                ->setParameter('status1', 'finished')
                ->orWhere('l.status = :status2')
                ->orWhere('l.status is NULL')
                ->setParameter('status2', 'refused')
                ->orderBy('p.id', 'DESC');
        $pager = new DoctrineORMAdapter($queryBuilder);
        $pager = new Pagerfanta($pager);
        return $pager->setMaxPerPage(10)->setCurrentPage($page);
    }

    public function findPaginatedByUser(User $user, $page = 1) {
        $queryBuilder = $this->createQueryBuilder('p')
                ->leftJoin('p.owner', 'u')
                ->addSelect('u')
                ->leftJoin('p.tags', 't')
                ->addSelect('t')
                ->where('u = :user')
                ->setParameter('user', $user)
                ->orderBy('p.id', 'ASC');
        $pager = new DoctrineORMAdapter($queryBuilder);
        $pager = new Pagerfanta($pager);
        return $pager->setMaxPerPage(10)->setCurrentPage($page);
    }

    public function findPaginatedByTag(Tag $tag, $page = 1) {
        $queryBuilder = $this->createQueryBuilder('p')
                ->leftJoin('p.owner', 'u')
                ->addSelect('u')
                ->leftJoin('p.tags', 't2')
                ->leftJoin('p.tags', 't')
                ->addSelect('t')
                ->where('t2 = :tag')
                ->leftJoin('p.loans', 'l')
                ->setParameter('tag', $tag)
                ->orderBy('p.id', 'DESC');
        $orGroup = $queryBuilder->expr()->orX();
        $orGroup->add($queryBuilder->expr()->eq('l.status', ':status1'));
        $orGroup->add($queryBuilder->expr()->eq('l.status', ':status2'));
        $orGroup->add($queryBuilder->expr()->isNull('l.status'));

        $queryBuilder->andWhere($orGroup)
                ->setParameter('status1', 'finished')
                ->setParameter('status2', 'refused');
        
        $pager = new DoctrineORMAdapter($queryBuilder);
        $pager = new Pagerfanta($pager);
        return $pager->setMaxPerPage(10)->setCurrentPage($page);
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('p')
      ->andWhere('p.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('p.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Product
      {
      return $this->createQueryBuilder('p')
      ->andWhere('p.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
