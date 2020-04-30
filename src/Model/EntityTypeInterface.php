<?php
declare(strict_types=1);

namespace Cupcake\Model;

use Cake\Datasource\EntityInterface;

/**
 * Interface EntityTypeInterface
 *
 * The EntityTypeHandlerInterface expects an EntityTypeInterface instance when loading an EntityTypeHandler
 *
 * @package Cupcake\Model
 */
interface EntityTypeInterface
{
    /**
     * @param \Cake\Datasource\EntityInterface $entity
     * @return mixed
     */
    public function __construct(EntityInterface $entity);
}
