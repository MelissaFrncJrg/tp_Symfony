<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

final class RecipeController extends AbstractController
{
    #[Route('/recipes', name: 'app_recipe_index')]
    public function index(RecipeRepository $recipeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $recipeRepository->createQueryBuilder('r')->getQuery();

        $recipes = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recipes/{id}', name:'app_recipe_show')]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/recipe/new', name: 'app_recipe_new')]
    public function new(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();

            /** @var Recipe $recipe */
            $recipe = $form->getData();

            $recipe->setCreatedAt(new \DateTimeImmutable());
            $recipe->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('app_recipe_index');
        }
            return $this->render('recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recipe/{id}/edit', name: 'app_recipe_edit')]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            if ($recipe->getUser() !== $this->getUser()) {
                throw $this->createAccessDeniedException(
                    $translator->trans('error.recipe.not_owner')
                );
            }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setUpdatedAt(new \DateTimeImmutable());

            $em->flush();

            return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('recipe/edit.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
    }

}
