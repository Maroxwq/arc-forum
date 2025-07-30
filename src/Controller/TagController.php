<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/post', name: 'app_tag_all', methods: ['GET'])]
    public function all(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'items' => $postRepository->findAllWithCommentCount(),
        ]);
    }
}
