<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $latestRecipes = $recipeRepository->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('home/index.html.twig', [
            'recipes' => $latestRecipes,
        ]);
    }
}
