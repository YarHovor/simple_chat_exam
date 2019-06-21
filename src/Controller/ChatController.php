<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Form\ChatFormType;
use App\Repository\ChatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    /**
     * @Route("/", name="chat")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, ChatRepository $chatRepository)
    {

        $add_message = new Chat();

        $form=$this->createForm(ChatFormType::class, $add_message);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($add_message);
            $entityManager->flush();

            return $this->redirectToRoute('chat');
        }


        $chats = $this->getDoctrine()->getManager()->getRepository(Chat::class)->findBy([], ['created' => 'DESC'], 10);

            return $this->render('chat/index.html.twig', [
            'form' => $form->createView(),
                'chats' => $chats,
        ]);

    }
}
