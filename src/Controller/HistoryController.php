<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\History;
use Symfony\Component\HttpFoundation\Request;

class HistoryController extends AbstractController
{
    /**
     * @Route("/history/add", name="add-history", methods={"POST"})
     */
    public function addHistory(Request $request): Response
    {
        $user = $this->getUser();
        $bookRepository = $this->getDoctrine()->getRepository(Book::class);
        $book = $bookRepository->find($request->get('book'));
        $repository = $this->getDoctrine()->getRepository(History::class);

        $history = $repository->findBy(['user' => $user, 'book' => $book]);

        if ($history) {
            return new Response('book already exist');
        }

        $history = new History();

        $history->setUser($user);
        $history->setBook($book);

        $em = $this->getDoctrine()->getManager();
        $em->persist($history);
        $em->flush();

        return new Response('history added');
    }

    /**
     * @Route("/history", name="history")
     */
    public function history(): Response
    {
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(History::class);

        if (!$user || in_array('admin', $user->getRoles())) {
            return $this->redirectToRoute('app_home');
        }

        $histories = $repository->findBy(['user' => $user]);

        return $this->render('history.html.twig', [
            'user' => $user,
            'histories' => $histories,
        ]);
    }
}
