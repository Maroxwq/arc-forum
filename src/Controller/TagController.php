<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TagController extends AbstractController
{
    #[Route('/', name: 'app_tag_all', methods: ['GET'])]
    public function all(PostRepository $postRepository): Response
    {
        return $this->render('tag/index.html.twig', [
            'items' => $postRepository->findAllWithCommentsCount(),
        ]);
    }
}
