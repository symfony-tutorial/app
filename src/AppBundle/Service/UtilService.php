<?php

namespace AppBundle\Service;

use AppBundle\Exception\ConcurrentAccessException;
use AppBundle\Exception\LockException;
use Memcached;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Monolog\Logger;

class UtilService
{

    const ID = 'app.util';

    /**
     *
     * @var RegistryInterface
     */
    private $registry;

    /**
     *
     * @var Logger
     */
    private $logger;

    /**
     *
     * @var Memcached
     */
    private $memcached;

    function __construct(RegistryInterface $registry, Logger $logger, Memcached $memcached) {
        $this->registry = $registry;
        $this->logger = $logger;
        $this->memcached = $memcached;
    }

    public function getLock($lock, $timeout = 10) {
        $this->logger->addInfo(sprintf('acquiring named lock "%s"', $lock));
        $connection = $this->registry->getConnection();
        $result = $connection->executeQuery(
                "SELECT GET_LOCK(':lock', :timeout)", array('lock' => $lock, 'timeout' => $timeout)
        );
        if (!$result) {
            throw new LockException(sprintf('Cannot get named lock %s', $lock));
        }
        $this->logger->addInfo(sprintf('acquired named lock "%s"', $lock));
    }

    public function releaseLock($lock) {
        $connection = $this->registry->getConnection();
        $connection->executeQuery(
                "SELECT RELEASE_LOCK(':lock')", array('lock' => $lock)
        );
        $this->logger->addInfo(sprintf('released named lock "%s"', $lock));
    }

    public function enterConcurrentSection($resource, $limit, $timeLimit) {
        $key = sprintf('limit_concurrents.%s', $resource);
        $this->memcached->add($key, 0, $timeLimit);
        if ($this->memcached->increment($key) > $limit) {
            $this->memcached->decrement($key);
            throw new ConcurrentAccessException(
            sprintf('Only %s processes can access %s per %s seconds', $limit, $resource, $timeLimit)
            );
        }
    }

    public function exitConcurrentSection($resource) {
        $this->memcached->decrement(sprintf('limit_concurrents.%s', $resource));
    }

}
