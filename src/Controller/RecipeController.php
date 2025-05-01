<?php

namespace App\Controller;

use App\Entity\Origin;
use App\Entity\Recipe;
use App\Entity\Tag;
use App\Form\OriginType;
use App\Form\RecipeType;
use App\Form\TagType;
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
        $query = $recipeRepository->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();

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
    public function new(Request $request, EntityManagerInterface $em, Security $security, TranslatorInterface $translator): Response
    {
        $origin = new Origin();
        $originForm = $this->createForm(OriginType::class, $origin);
        $originForm->handleRequest($request);

        $tag = new Tag();
        $tag->setUser($this->getUser());
        $tagForm = $this->createForm(TagType::class, $tag);
        $tagForm->handleRequest($request);

        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($originForm->isSubmitted() && $originForm->isValid()) {
            $country = $origin->getCountry();

            if ($this->isDuplicateEntityLabel($country, 'country', Origin::class, $em)) {
                $this->addFlash('error', $translator->trans('origin.duplicate.error'));
            } else {
                $origin->setIsPublic(true);
                $em->persist($origin);
                $em->flush();
                $this->addFlash('success', $translator->trans('origin.success'));
            }

            return $this->redirectToRoute('app_recipe_new', ['_fragment' => 'recipe-form']);
        }

        if ($tagForm->isSubmitted() && $tagForm->isValid()) {
            $rawLabel = $tag->getLabel();

            if ($this->isDuplicateEntityLabel($rawLabel, 'label', Tag::class, $em, ['user' => $this->getUser()])) {
                $this->addFlash('error', $translator->trans('tag.duplicate.error'));
            } else {
                $tag->setLabel(trim($rawLabel));
                $tag->setIsPublic(true);
                $em->persist($tag);
                $em->flush();
                $this->addFlash('success', $translator->trans('tag.success'));
            }

            return $this->redirectToRoute('app_recipe_new', ['_fragment' => 'recipe-form']);
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            $recipe = $form->getData();
            $recipe->setUser($user);
            $recipe->setCreatedAt(new \DateTimeImmutable());
            $recipe->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('app_recipe_index');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form->createView(),
            'originForm' => $originForm->createView(),
            'tagForm' => $tagForm->createView(),
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

    
    #[Route('/recipe/{id}/delete', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Recipe $recipe, Request $request, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($recipe->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException(
                $translator->trans('error.recipe.not_owner')
            );
        }

        if ($this->isCsrfTokenValid('delete_recipe_' . $recipe->getId(), $request->request->get('_token'))) {
            $em->remove($recipe);
            $em->flush();

            $this->addFlash('success', $translator->trans('recipe.deleted'));
        }

        return $this->redirectToRoute('app_recipe_index');
    }

    private function normalize(string $string): string
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', mb_strtolower(trim($string)));
    }

    private function isDuplicateEntityLabel(
        string $input,
        string $field,
        string $entityClass,
        EntityManagerInterface $em,
        array $extraCriteria = []
    ): bool {
        $normalized = $this->normalize($input);
        $entities = $em->getRepository($entityClass)->findBy($extraCriteria);

        foreach ($entities as $entity) {
            $getter = 'get' . ucfirst($field);
            if (method_exists($entity, $getter)) {
                $value = $entity->$getter();
                if ($this->normalize($value) === $normalized) {
                    return true;
                }
            }
        }

        return false;
    }
}
