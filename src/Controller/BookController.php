<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{
    /**
     * @Route("/admin/book", name="admin-book")
     */
    public function adminHome(): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array('admin', $user->getRoles())) {
            throw $this->createNotFoundException('Page not found');
        }

        $repository = $this->getDoctrine()->getRepository(Book::class);

        $books = $repository->findAll();

        return $this->render('admin/book.html.twig', [
            'user' => $user,
            'books' => $books,
        ]);
    }

    /**
     * @Route("/admin/book/add", name="add-book")
     */
    public function addBook(
        Request $request,
        FileUploader $fileUploader
    ): Response {
        $user = $this->getUser();
        if (!$user || !in_array('admin', $user->getRoles())) {
            throw $this->createNotFoundException('Page not found');
        }

        $categoryRepository = $this->getDoctrine()->getRepository(
            Category::class
        );
        $categories = $categoryRepository->findAll();

        if ($request->isMethod('POST')) {
            $book = new Book();
            $category =
                $categories[
                    array_search(
                        $request->get('category') - 0,
                        array_column($categories, 'id')
                    )
                ];

            $book->setTitle($request->get('title'));
            $book->setAuthor($request->get('author'));
            $book->setCategory($category);
            $book->setDescription($request->get('description'));
            $url = $request->files->get('url');
            $cover = $request->files->get('cover');
            $urlFileName = $fileUploader->upload($url);
            $coverFileName = $fileUploader->upload($cover);
            $book->setUrl($urlFileName);
            $book->setCover($coverFileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('admin-book');
        }

        return $this->render('admin/addBook.html.twig', [
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/book/{id}", name="update-book")
     */
    public function updateBook(
        int $id,
        Request $request,
        FileUploader $fileUploader,
        Filesystem $filesystem
    ): Response {
        $user = $this->getUser();
        if (!$user || !in_array('admin', $user->getRoles())) {
            throw $this->createNotFoundException('Page not found');
        }
        $em = $this->getDoctrine()->getManager();
        $bookRepository = $this->getDoctrine()->getRepository(Book::class);
        $book = $bookRepository->find($id);
        $categoryRepository = $this->getDoctrine()->getRepository(
            Category::class
        );
        $categories = $categoryRepository->findAll();

        if (!$book) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }

        if ($request->isMethod('POST')) {
            $category =
                $categories[
                    array_search(
                        $request->get('category') - 0,
                        array_column($categories, 'id')
                    )
                ];

            $book->setTitle($request->get('title'));
            $book->setAuthor($request->get('author'));
            $book->setCategory($category);
            $book->setDescription($request->get('description'));
            $url = $request->files->get('url');
            $cover = $request->files->get('cover');

            if ($url) {
                $urlFileName = $fileUploader->upload($url);
                $filesystem->remove([
                    $fileUploader->getTargetDirectory() . '/' . $book->getUrl(),
                ]);
                $book->setUrl($urlFileName);
            }

            if ($cover) {
                $coverFileName = $fileUploader->upload($cover);
                $filesystem->remove([
                    $fileUploader->getTargetDirectory() .
                    '/' .
                    $book->getCover(),
                ]);
                $book->setCover($coverFileName);
            }

            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('admin-book');
        }

        if ($request->isMethod('DELETE')) {
            $filesystem->remove([
                $fileUploader->getTargetDirectory() . '/' . $book->getCover(),
            ]);
            $filesystem->remove([
                $fileUploader->getTargetDirectory() . '/' . $book->getUrl(),
            ]);
            $em->remove($book);
            $em->flush();
            return $this->redirectToRoute('admin-book');
        }

        return $this->render('admin/updateBook.html.twig', [
            'user' => $user,
            'categories' => $categories,
            'book' => $book,
        ]);
    }
}
