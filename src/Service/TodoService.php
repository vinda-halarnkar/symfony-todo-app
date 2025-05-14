<?php

namespace App\Service;

use App\Entity\Items;
use App\Entity\UserList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TodoService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function getUserLists(UserInterface $user): array
    {
        return $this->em->getRepository(UserList::class)->findBy(['user' => $user]);
    }

    public function getListName(int $listId, UserInterface $user): string
    {
        $list = $this->em->getRepository(UserList::class)->find($listId);
        if (!$list || $list->getUser() !== $user) {
            throw new AccessDeniedHttpException();
        }
        return $list->getName();
    }

    public function getItemsByList(int $listId, UserInterface $user, bool $completed, string $sort): array
    {
        $sortField = $sort === 'date' ? 'i.createdAt' : 'i.name';

        return $this->em->getRepository(Items::class)->createQueryBuilder('i')
            ->join('i.userList', 'ul')
            ->where('ul.user = :user')
            ->andWhere('ul.id = :listId')
            ->andWhere('i.isCompleted = :completed')
            ->setParameter('user', $user)
            ->setParameter('listId', $listId)
            ->setParameter('completed', $completed)
            ->orderBy($sortField, 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function saveList(UserList $list, UserInterface $user): void
    {
        $list->setUser($user);
        $this->em->persist($list);
        $this->em->flush();
    }

    public function initializeItem(int $listId, ?int $itemId, UserInterface $user): Items
    {
        $userList = $this->em->getRepository(UserList::class)->find($listId);

        if (!$userList || $userList->getUser() !== $user) {
            throw new AccessDeniedHttpException('You do not own this list.');
        }

        if ($itemId) {
            $item = $this->em->getRepository(Items::class)->find($itemId);
            if (!$item || $item->getUserList()->getUser() !== $user) {
                throw new AccessDeniedHttpException('You do not own this item.');
            }
        } else {
            $item = new Items();
            $item->setUserList($userList);
        }

        return $item;
    }

    public function saveItem(Items $item): void
    {
        $this->em->persist($item);
        $this->em->flush();
    }

    public function deleteList(int $listId, UserInterface $user): void
    {
        $list = $this->em->getRepository(UserList::class)->find($listId);
        if (!$list || $list->getUser() !== $user) {
            throw new AccessDeniedHttpException('You cannot delete this list.');
        }

        $this->em->remove($list);
        $this->em->flush();
    }

    public function deleteItem(int $itemId, UserInterface $user): int
    {
        $item = $this->em->getRepository(Items::class)->find($itemId);
        if (!$item || $item->getUserList()->getUser() !== $user) {
            throw new AccessDeniedHttpException('You cannot delete this item.');
        }

        $listId = $item->getUserList()->getId();
        $this->em->remove($item);
        $this->em->flush();

        return $listId;
    }

    public function markItemComplete(int $itemId, UserInterface $user): int
    {
        $item = $this->em->getRepository(Items::class)->find($itemId);

        if (!$item) {
            throw new NotFoundHttpException('Item not found.');
        }

        $userList = $item->getUserList();

        if (!$userList || $userList->getUser() !== $user) {
            throw new AccessDeniedHttpException('You do not have permission to update this item.');
        }

        $item->setIsCompleted(true);
        $this->em->flush();

        return $userList->getId();
    }
}
