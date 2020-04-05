<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getCategoriesUser(User $user)
    {
        $categories = [];
        $allCategories = $this->findAll();
        foreach ($allCategories as $category) {
            $currentTasks = $category->getTasksPerUser($user);
            $currentTodos = $category->getTodosPerUser($user);
            if (!empty($currentTasks) || !empty($currentTodos)) {
                $categories[] = $category;
            }
        }
        return $categories;
    }

    public function getCurrentTodosPerUser(User $user)
    {
        $categoriesTodos = [];
        $allCategories = $this->findAll();

        foreach ($allCategories as $category) {
            $currentTodos = $category->getTodosCurrentPerUser($user);
            if (!empty($currentTodos)) {
                $categoriesTodos[$category->getName()] = $currentTodos;
            }
        }
        return $categoriesTodos;
    }

    public function getCurrentTasksPerUser(User $user)
    {
        $categoriesTasks = [];
        $allCategories = $this->findAll();

        foreach ($allCategories as $category) {
            $currentTasks = $category->getTasksCurrentPerUser($user);
            if (!empty($currentTasks)) {
                $categoriesTasks[$category->getName()] = $currentTasks;
            }
        }
        return $categoriesTasks;
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
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
    public function findOneBySomeField($value): ?Category
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
