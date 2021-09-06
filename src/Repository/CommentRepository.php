<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }
    
    public function getComments($by, $on){

        $conn = $this->getEntityManager()
        ->getConnection();
        $sql = "SELECT comment.* FROM comment 
        JOIN user 
        WHERE comment.subordinate_id_id=user.id 
        AND comment.manager_id_id=?
        AND comment.subordinate_id_id= ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $by);
        $stmt->bindValue(2, $on);
        $stmt->execute();
        //dd($stmt->fetchAll());
        return $stmt->fetchAll();

    }

    public function getAllComments($on){

        $conn = $this->getEntityManager()
        ->getConnection();

        $sql = "SELECT comment.*,user.first_name,user.last_name  FROM comment 
        JOIN user 
        WHERE comment.manager_id_id=user.id 
        AND comment.subordinate_id_id= ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $on);
        $stmt->execute();
        //dd($stmt->fetchAll());
        return $stmt->fetchAll();

    }
    

    public function removeComment($id){
        $em = $this->getEntityManager();
        $comment = $this->findOneBy(['id' => $id]);
        $em->remove($comment);
        $em->flush();
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}