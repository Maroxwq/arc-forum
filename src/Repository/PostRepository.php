<?php declare(strict_types=1);

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

    public function findAllWithCommentsCount(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p, u, COUNT(c.id) AS commentsCount')
            ->leftJoin(Comment::class, 'c', 'WITH', 'c.post = p')
            ->innerJoin('p.owner', 'u')
            ->groupBy('p.id');

        return array_map(function($item) {
            return [
                'post' => $item[0],
                'commentsCount' => (int) $item['commentsCount'],
            ];
        }, $qb->getQuery()->getResult());
    }
}
