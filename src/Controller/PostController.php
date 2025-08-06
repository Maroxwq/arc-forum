<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostForm;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\CommentForm;

#[Route(path: '/post', name: 'app_post_')]
final class PostController extends AbstractController
{
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostForm::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setOwner($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_tag_all');
        }

        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Post $post, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAllWithAuthorsByPostId($post->getId());
        $commentForm = $this->createForm(CommentForm::class);

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'commentForm' => $commentForm->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('edit', 'post')]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostForm::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('delete', 'post')]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_post'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tag_all');
    }
}
