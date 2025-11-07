<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostForm;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\CommentForm;
use App\Entity\User;

#[Route(path: '/post', name: 'app_post_')]
final class PostController extends AbstractController
{
    public function  __construct(private readonly EntityManagerInterface $en) {}

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
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

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request): Response
    {
        return $this->processForm($request, new Post());
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('edit', 'post')]
    public function edit(Request $request, Post $post): Response
    {
        return $this->processForm($request, $post);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('delete', 'post')]
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete_post'.$post->getId(), $request->request->get('_token'))) {
            $this->en->remove($post);
            $this->en->flush();
        }

        return $this->redirectToRoute('app_tag_all');
    }

    private function processForm(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostForm::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null === $post->getId()) {
                /** @var User|null $user */
                $user = $this->getUser();
                $post->setOwner($user);
                $this->en->persist($post);
            }

            $this->en->flush();

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/form.html.twig', ['form' => $form->createView(), 'post' => $post,]);
    }
}
