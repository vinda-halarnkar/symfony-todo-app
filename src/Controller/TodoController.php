<?php

namespace App\Controller;

use App\Entity\Items;
use App\Entity\UserList;
use App\Form\ItemType;
use App\Form\ListType;
use App\Repository\ListsRepository;
use App\Service\TodoService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class TodoController extends AbstractController
{
    public function __construct(private TodoService $todoService) {}

    #[Route('/todo/{listId?}', name: 'app_todo')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function todo(Request $request, ?int $listId = null): Response
    {
        $user = $this->getUser();
        $sort = $request->query->get('sort', 'name');

        $userLists = $this->todoService->getUserLists($user);

        $items = $completedItems = $listName = '';
        if ($listId) {
            $listName = $this->todoService->getListName($listId, $user);
            $items = $this->todoService->getItemsByList($listId, $user, false, $sort);
            $completedItems = $this->todoService->getItemsByList($listId, $user, true, $sort);
        }

        $listForm = $this->createForm(ListType::class);
        $itemForm = $this->createForm(ItemType::class);

        return $this->render('todo.html.twig', [
            'listForm' => $listForm->createView(),
            'itemForm' => $itemForm->createView(),
            'lists' => $userLists,
            'items' => $items,
            'completedItems' => $completedItems,
            'selectedListName' => $listName,
            'selectedListId' => $listId,
            'currentSort' => $sort,
        ]);
    }

    #[Route('/create-list', name: 'create_list', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createList(Request $request): Response
    {
        $userList = new UserList();
        $form = $this->createForm(ListType::class, $userList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todoService->saveList($userList, $this->getUser());
        }

        return $this->redirectToRoute('app_todo');
    }

    #[Route('/save-item/{listId}/{itemId?}', name: 'save_item', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function addItem(int $listId, ?int $itemId = null, Request $request): Response
    {
        $user = $this->getUser();
        $item = $this->todoService->initializeItem($listId, $itemId, $user);
        if($itemId) {
            $item->setName($request->request->get('name'));
            $item->setColor($request->request->get('color'));
            $item->setIsCompleted($request->request->getBoolean('isCompleted'));

            $this->todoService->saveItem($item);
        } else {
           
            $form = $this->createForm(ItemType::class, $item);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->todoService->saveItem($item);
            }
        }
        

        

        return $this->redirectToRoute('app_todo', ['listId' => $listId]);
    }

    #[Route('/delete-list/{listId}', name: 'delete_list')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function deleteList(int $listId): Response
    {
        $this->todoService->deleteList($listId, $this->getUser());
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/delete-item/{itemId}', name: 'delete_item')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function deleteItem(int $itemId): Response
    {
        $listId = $this->todoService->deleteItem($itemId, $this->getUser());
        return $this->redirectToRoute('app_todo', ['listId' => $listId]);
    }

    #[Route('/complete-item/{itemId}', name: 'complete_item')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function completeItem(int $itemId): Response
    {
        $listId = $this->todoService->markItemComplete($itemId, $this->getUser());
        return $this->redirectToRoute('app_todo', ['listId' => $listId]);
    }

    #[Route('/api/get-data', name: 'api_get_data', methods: ['GET'])]
    public function receiveData(ListsRepository $listRepository): JsonResponse
    {
        $lists = $listRepository->findAll();
        $data = array_map(fn($list) => ['id' => $list->getId(), 'name' => $list->getName()], $lists);

        return new JsonResponse(['status' => 'success', 'data' => $data]);
    }
}
