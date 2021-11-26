<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mercure', name: 'mercure_')]
class MercureController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/subscribe', name: 'subscribe')]
    public function subscribe(Request $request): Response
    {
        // Ici on récupère l'identifiant via l'URL, mais dans un cas concret,
        // on récupérera plutôt le nom d'utilisateur de l'utilisateur connecté
        $sender = $request->get('sender', null);

        if (null === $sender) {
            throw new BadRequestHttpException('You must provide a valid username');
        }

        $messages = $this->entityManager->getRepository(Message::class)->findAll();

        return $this->render('mercure/subscribe.html.twig', [
            'sender'     => $sender,
            'messages'   => $messages,
        ]);
    }
}
