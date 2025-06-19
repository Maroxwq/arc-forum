<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    public string $message;

    #[Route('/page/{id}')]
    public function index(int $id): Response
    {
        $response = new Response();
        // $response->setStatusCode(422);
        if ($response->getStatusCode() === 200) {
            $this->message = 'This is a normal page, working fine. Id = ' . $id;
        } else {
            $this->message = 'This is bullshit, something went wrong.';
        }

        return $this->render('test.html.twig', [
            'message' => $this->message,
        ]);
    }
}