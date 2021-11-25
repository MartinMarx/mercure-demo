<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/mercure', name: 'mercure_')]
class MercureController extends AbstractController
{
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;

    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ) {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    #[Route('/publish', name: 'publish', methods: ['POST'])]
    public function publish(Request $request): Response
    {
        // On récupère le corps de la requête
        $data = json_decode($request->getContent());

        // On crée un nouvel object Message
        $message = new Message(
            $data->text,
            $data->username
        );

        // On l'enregistre en db
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $this->json(json_decode($this->serializer->serialize($message, 'json')));
    }

    #[Route('/subscribe', name: 'subscribe')]
    public function subscribe(Request $request): Response
    {
        // Ici on récupère l'identifiant via l'URL, mais dans un cas concret,
        // on récupérera plutôt le nom d'utilisateur de l'utilisateur connecté
        $username = $request->get('username', null);

        if (null === $username) {
            throw new BadRequestHttpException('You must provide a valid username');
        }

        $messages = $this->entityManager->getRepository(Message::class)->findAll();

        return $this->render('mercure/subscribe.html.twig', [
            'username'   => $username,
            'messages'   => $messages,
        ]);
    }
}
