<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Note>
 *
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function add(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Note[] Returns all notes ordered by creation date (newest first)
     */
    public function findAllOrderedByDate(): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Note[] Returns notes matching search query
     */
    public function search(string $query): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.title LIKE :query OR n.content LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Note[] Returns notes ordered by title (A-Z)
     */
    public function findAllOrderedByTitle(): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}