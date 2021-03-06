<?php

namespace App\TCPController\Mock;


use App\Logger;
use BadMethodCallException;
use DateTimeInterface;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Cache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\PessimisticLockException;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\FilterCollection;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Persistence\ObjectRepository;

class EntityManager implements EntityManagerInterface
{
    public function persist($object) {
        Logger::log('DBMock | PERSIST', var_export($object, true));
    }

    public function flush() {
    }

    public function getCache()
    {
        // TODO: Implement getCache() method.
    }

    public function getConnection()
    {
        // TODO: Implement getConnection() method.
    }

    public function getExpressionBuilder()
    {
        // TODO: Implement getExpressionBuilder() method.
    }

    public function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    public function transactional($func)
    {
        // TODO: Implement transactional() method.
    }

    public function commit()
    {
        // TODO: Implement commit() method.
    }

    public function rollback()
    {
        // TODO: Implement rollback() method.
    }

    public function createQuery($dql = '')
    {
        // TODO: Implement createQuery() method.
    }

    public function createNamedQuery($name)
    {
        // TODO: Implement createNamedQuery() method.
    }

    public function createNativeQuery($sql, ResultSetMapping $rsm)
    {
        // TODO: Implement createNativeQuery() method.
    }

    public function createNamedNativeQuery($name)
    {
        // TODO: Implement createNamedNativeQuery() method.
    }

    public function createQueryBuilder()
    {
        // TODO: Implement createQueryBuilder() method.
    }

    public function getReference($entityName, $id)
    {
        // TODO: Implement getReference() method.
    }

    public function getPartialReference($entityName, $identifier)
    {
        // TODO: Implement getPartialReference() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    public function copy($entity, $deep = false)
    {
        // TODO: Implement copy() method.
    }

    public function lock($entity, $lockMode, $lockVersion = null)
    {
        // TODO: Implement lock() method.
    }

    public function getEventManager()
    {
        // TODO: Implement getEventManager() method.
    }

    public function getConfiguration()
    {
        // TODO: Implement getConfiguration() method.
    }

    public function isOpen()
    {
        // TODO: Implement isOpen() method.
    }

    public function getUnitOfWork()
    {
        // TODO: Implement getUnitOfWork() method.
    }

    public function getHydrator($hydrationMode)
    {
        // TODO: Implement getHydrator() method.
    }

    public function newHydrator($hydrationMode)
    {
        // TODO: Implement newHydrator() method.
    }

    public function getProxyFactory()
    {
        // TODO: Implement getProxyFactory() method.
    }

    public function getFilters()
    {
        // TODO: Implement getFilters() method.
    }

    public function isFiltersStateClean()
    {
        // TODO: Implement isFiltersStateClean() method.
    }

    public function hasFilters()
    {
        // TODO: Implement hasFilters() method.
    }

    public function find($className, $id)
    {
        // TODO: Implement find() method.
    }

    public function remove($object)
    {
        // TODO: Implement remove() method.
    }

    public function merge($object)
    {
        // TODO: Implement merge() method.
    }

    public function clear($objectName = null)
    {
        // TODO: Implement clear() method.
    }

    public function detach($object)
    {
        // TODO: Implement detach() method.
    }

    public function refresh($object)
    {
        // TODO: Implement refresh() method.
    }

    public function getRepository($className)
    {
        return $this;
    }

    public function getClassMetadata($className)
    {
        // TODO: Implement getClassMetadata() method.
    }

    public function getMetadataFactory()
    {
        // TODO: Implement getMetadataFactory() method.
    }

    public function initializeObject($obj)
    {
        // TODO: Implement initializeObject() method.
    }

    public function contains($object)
    {
        // TODO: Implement contains() method.
    }

    public function findBy(array $params)
    {
        return [];
    }
}