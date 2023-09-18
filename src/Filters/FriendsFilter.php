<?php

namespace App\Filters;

use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;

final class FriendsFilter extends AbstractFilter

{
  protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
  {
            // otherwise filter is applied to order and page as well
            if (
              !$this->isPropertyEnabled($property, $resourceClass) ||
              !$this->isPropertyMapped($property, $resourceClass)
          ) {
              return;
          }

    if ($property === 'id') {
      $alias = $queryBuilder->getRootAliases()[0];

      $queryBuilder
        ->leftJoin("$alias.friendshipRequester", 'requester')
        ->leftJoin("$alias.friendshipAccepter", 'accepter')
        ->andWhere(
          $queryBuilder->expr()->orX(
            $queryBuilder->expr()->eq('requester.id', ":id"),
            $queryBuilder->expr()->eq('accepter.id', ":id")
          )
        )
        ->setParameter('id', $value);
    } elseif ($property === 'isAccepted') {
      $alias = $queryBuilder->getRootAliases()[0];

      $queryBuilder
        ->andWhere("$alias.isAccepted = :isAccepted")
        ->setParameter('isAccepted', $value);
    }
  }

  public function getDescription(string $resourceClass): array
  {
    return [
      'id' => [
        'property' => 'id',
        'type' => 'string',
        'required' => false,
        'description' => 'Filter by user ID in friendshipRequester or friendshipAccepter.',
      ],
      'isAccepted' => [
        'property' => 'isAccepted',
        'type' => 'boolean',
        'required' => false,
        'description' => 'Filter by isAccepted boolean value.',
      ],
    ];
  }
}
