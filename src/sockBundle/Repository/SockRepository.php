<?php

namespace sockBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * SockRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SockRepository extends \Doctrine\ORM\EntityRepository
{
    const MAX_RESULT = 9;

    /**
     * @param int $page
     * @param int $itemPerPage
     * @return Paginator
     */
    public function getByPage($page, $itemPerPage = self::MAX_RESULT)
    {
        if ($page > 0) {
            $offset = ($page - 1) * $itemPerPage;
        } else {
            $offset = 0;
        }
        $query = $this->createQueryBuilder('s')
            ->setFirstResult($offset)
            ->setMaxResults($itemPerPage);

        return new Paginator($query);
    }
}
