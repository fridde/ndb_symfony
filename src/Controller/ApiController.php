<?php


namespace App\Controller;


use App\Entity\Group;
use App\Entity\Message;
use App\Entity\Note;
use App\Entity\School;
use App\Entity\User;
use App\Entity\Visit;
use App\Repository\Filterable;
use App\Utils\EntitySerializer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    private Request $request;
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->request = Request::createFromGlobals();
        $this->em = $entityManager;
    }

    #[Route(
        '/api/user/{user}',
        methods: ['GET']
    )]
    public function getUserData(User $user): JsonResponse
    {
        return $this->customJson($user);
    }

    #[Route(
        '/api/user/{user}',
        methods: ['POST']
    )]
    public function updateUserData(User $user): JsonResponse
    {
        $this->updateEntityData($user);
        return $this->customJson(['success' => true]);
    }

    #[Route(
        '/api/users',
        methods: ['GET']
    )]
    public function getUsers(): JsonResponse
    {
        return $this->customJson($this->getFiltered(User::class));
    }

    #[Route(
        '/api/users',
        methods: ['POST']
    )]
    public function updateUsers(): JsonResponse
    {
        $this->updateMultipleEntities(User::class);
        return $this->customJson(['success' => true]); // TODO: Implement update
    }


    #[Route(
        '/api/visit/{visit}',
        methods: ['GET']
    )]
    public function getVisitData(Visit $visit): JsonResponse
    {
        return $this->customJson($visit);
    }

    #[Route(
        '/api/visit/{visit}',
        methods: ['POST']
    )]
    public function updateVisit(Visit $visit): JsonResponse
    {
        $this->updateEntityData($visit);
        return $this->customJson(['success' => true]);
    }

    #[Route(
        '/api/visits',
        methods: ['GET']
    )]
    public function getVisits(): JsonResponse
    {
        return $this->customJson($this->getFiltered(Visit::class));
    }

    #[Route(
        '/api/visits',
        methods: ['POST']
    )]
    public function updateVisits(): JsonResponse
    {
        $this->updateMultipleEntities(Visit::class);
        return $this->customJson(['success' => true]);
    }

    #[Route(
        '/api/note/{note}',
        methods: ['GET']
    )]
    public function getNote(Note $note): JsonResponse
    {
        return $this->customJson($note);
    }

    #[Route(
        '/api/note/{note}',
        methods: ['POST']
    )]
    public function updateNote(Note $note): JsonResponse
    {
        $this->updateEntityData($note);
        return $this->customJson(['success' => true]);
    }

    #[Route(
        '/api/notes',
        methods: ['GET']
    )]
    public function getNotes(): JsonResponse
    {
        return $this->customJson($this->getFiltered(Note::class));
    }

    #[Route(
        '/api/notes',
        methods: ['POST']
    )]
    public function updateNotes(): JsonResponse
    {
        $this->updateMultipleEntities(Note::class);
        return $this->customJson(['success' => true]);
    }

    #[Route(
        '/api/group/{group}',
        methods: ['GET']
    )]
    public function getGroup(Group $group): JsonResponse
    {
        return $this->customJson($group);
    }

    #[Route(
        '/api/group/{group}',
        methods: ['POST']
    )]
    public function updateGroup(Group $group): JsonResponse
    {
        $this->updateEntityData($group);
        return $this->customJson(['success' => true]);
    }

    #[Route(
        '/api/groups',
        methods: ['GET']
    )]
    public function getGroups(): JsonResponse
    {
        return $this->customJson($this->getFiltered(Group::class));
    }

    #[Route(
        '/api/groups',
        methods: ['POST']
    )]
    public function updateGroups(): JsonResponse
    {
        $this->updateMultipleEntities(Group::class);
        return $this->customJson(['success' => true]);
    }

    #[Route(
        '/api/school/{school}',
        methods: ['GET']
    )]
    public function getSchool(School $school): JsonResponse
    {
        return $this->customJson($school);
    }

    #[Route(
        '/api/school/{school}',
        methods: ['POST']
    )]
    public function updateSchool(School $school): JsonResponse
    {
        $this->updateEntityData($school);
        return $this->customJson(['success' => true]);
    }

    #[Route(
        '/api/schools',
        methods: ['GET']
    )]
    public function getSchools(): JsonResponse
    {
        return $this->customJson($this->getFiltered(School::class));
    }

    #[Route(
        '/api/schools',
        methods: ['POST']
    )]
    public function updateSchools(): JsonResponse
    {
        $this->updateMultipleEntities(School::class);
        return $this->customJson(['success' => true]);
    }

    #[Route(
        '/api/message/{message}',
        methods: ['GET']
    )]
    public function getMessage(Message $message): JsonResponse
    {
        return $this->customJson($message);
    }

    #[Route(
        '/api/message/{message}',
        methods: ['POST']
    )]
    public function updateMessage(Message $message): JsonResponse
    {
        $this->updateEntityData($message);
        return $this->customJson(['success' => true]);
    }

    #[Route(
        '/api/messages',
        methods: ['GET']
    )]
    public function getMessages(): JsonResponse
    {
        return $this->customJson($this->getFiltered(Message::class));
    }

    #[Route(
        '/api/messages',
        methods: ['POST']
    )]
    public function updateMessages(): JsonResponse
    {
        $this->updateMultipleEntities(Message::class);
        return $this->customJson(['success' => true]);
    }

    private function updateMultipleEntities(string $class_name, array $entities = null): void
    {
        $entities ??= $this->request->get('updates', []);
        $repo = $this->em->getRepository($class_name);

        foreach ($entities as $id => $entity_data) {
            $entity = $repo->find($id);
            $this->updateSingleEntity($entity, $entity_data);
        }
        $this->em->flush();
    }


    private function updateEntityData($e, array $data = null): void
    {
        $data ??= $this->request->get('updates', []);
        $this->updateSingleEntity($e, $data);
        $this->em->flush();
    }

    private function updateSingleEntity($e, array $data = []): void
    {
        foreach ($data as $attribute => $new_value) {
            $set_method = 'set' . ucfirst($attribute);
            $e->$set_method($new_value);
        }
        $this->em->persist($e);
    }

    private function getFiltered(string $fqcn): array
    {
        $filter = $this->request->query->all();
        /** @var Filterable $repo */
        $repo = $this->em->getRepository($fqcn);

        $criteria = $repo->getFilterCriteria($filter);
        /** @var EntityRepository $repo */

        return $repo->matching($criteria)->toArray();
    }

    private function customJson($data): JsonResponse
    {
        return new JsonResponse(json_encode($data), 200, [], true);
    }


}