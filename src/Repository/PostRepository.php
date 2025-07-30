<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Comment;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllWithCommentCount(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p, u, COUNT(c.id) AS commentsCount')
            ->leftJoin(\App\Entity\Comment::class, 'c', 'WITH', 'c.post = p')
            ->innerJoin('p.user', 'u')
            ->groupBy('p.id');

        return array_map(function($item) {
            return [
                'post' => $item[0],
                'commentsCount' => (int) $item['commentsCount'],
            ];
        }, $qb->getQuery()->getResult());
    }

    /**
     * @param int $id
     * @return array{post: Post, comments: Comment[]}|null
     */
    public function findOneWithCommentsAndAuthors(int $id): ?array
    {
        $post = $this->find($id);
        if (!$post) {
            return null;
        }

        $comments = $this->getEntityManager()
            ->getRepository(Comment::class)
            ->createQueryBuilder('c')
            ->innerJoin('c.user', 'u')
            ->addSelect('u')
            ->where('c.post = :post')
            ->setParameter('post', $post)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();

        return [
            'post' => $post,
            'comments' => $comments,
        ];
    }

    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
