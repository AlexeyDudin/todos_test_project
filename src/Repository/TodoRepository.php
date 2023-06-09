<?php

namespace App\Repository;

use App\Entity\Todo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Todo>
 *
 * @method Todo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todo[]    findAll()
 * @method Todo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Todo::class);
    }

    public function add(Todo $entity): void {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Todo $entity): void {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function getAll():array {
        return $this->findAll();
    }

    public function findById(int $id):?Todo {
        return $this->find($id);
    }

    private function findByQueryBuilder(QueryBuilder $qb):array {
        $query = $qb->getQuery();
        return $query->execute();
    }

    public function commitChanges() {
        $this->getEntityManager()->flush();
    }

    public function getArchiveTodos():array {
        $qb = $this->createQueryBuilder('t')
            ->where('t.executed = TRUE');
        return $this->findByQueryBuilder($qb);
    }

    public function getActiveTodos():array {
        $qb = $this->createQueryBuilder('t')
            ->where('t.executed = FALSE');
        return $this->findByQueryBuilder($qb);
    }

//    /**
//     * @return Todo[] Returns an array of Todo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Todo
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
