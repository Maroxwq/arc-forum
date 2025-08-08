<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\CommentForm;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(name: 'app_post_comment_')]
class CommentController extends AbstractController
{
    #[Route('/post/{id}/comment', name: 'new', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post);
            /** @var User $user */
            $user = $this->getUser();
            $comment->setOwner($user);

            $entityManager->persist($comment);
            $entityManager->flush();
        } else {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }

    #[Route('/comment/{id}/edit', name: 'edit', methods: ['GET','POST'])]
    #[IsGranted('edit', 'comment')]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_show', ['id' => $comment->getPost()->getId()]);
        }

        return $this->render('comment/edit.html.twig', ['form' => $form->createView(),  'comment' => $comment,]);
    }

    #[Route('/comment/{id}', name: 'delete', methods: ['POST'])]
    #[IsGranted('delete', 'comment')]
    public function delete(Comment $comment, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $postId = $comment->getPost()->getId();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_post_show', ['id' => $postId]);
    }
}
