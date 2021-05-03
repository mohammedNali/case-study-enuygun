<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */



    public function getSumHours($url, $value)
    {
        return $this->createQueryBuilder('th')
            ->where('th.difficulty = :difficulty')
            ->setParameter('difficulty', $value)
            ->andWhere('th.url = :url')
            ->setParameter('url', $url)
            ->select('SUM(th.duration) as durationHours')
            ->getQuery()
            ->getOneOrNullResult()
        ;

    }


    public function calculate($provider, TaskRepository $taskRepository)
    {
        $sum1 = $this->getSumHours($provider, 1);
        $sum2 = $this->getSumHours($provider, 2);
        $sum3 = $this->getSumHours($provider, 3);
        $sum4 = $this->getSumHours($provider, 4);
        $sum5 = $this->getSumHours($provider, 5);

        $totalHours = $sum1['durationHours'] + $sum2['durationHours'] * 2 + $sum3['durationHours'] * 3 + $sum4['durationHours'] * 4 + $sum5['durationHours'] * 5;
        // If we have just 5 developer we divide by 675, and it comes from
        // 45 (weekly hours worked 1x for the first Developer1) for the second developer it will be 45*2 and so on...
        $totalWeek = round($totalHours/675);
        $remainHours = round($totalHours%675 / 15);
        return "Bu Providerin Bütün görevleri minimum olarak ".$totalWeek." hafta ve ". $remainHours. " saat içerisinde biteceğine inaniyoruz";
    }

}
