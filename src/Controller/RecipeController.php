<?php
/**
 * Recipe controller.
 */

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\User;
use App\Form\Type\RecipeType;
use App\Service\RecipeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RecipeController.
 */
#[Route('/recipe')]
class RecipeController extends AbstractController
{
    /**
     * Recipe service.
     */
    private RecipeServiceInterface $recipeService;

    /**
     * Constructor.
     *
     * @param RecipeServiceInterface $recipeService Recipe service
     * @param TranslatorInterface    $translator    Translator
     */
    public function __construct(RecipeServiceInterface $recipeService, TranslatorInterface $translator)
    {
        $this->recipeService = $recipeService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'recipe_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $filters = $this->getFilters($request);
        $pagination = $this->recipeService->getPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );

        return $this->render('recipe/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Recipe $recipe Recipe
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'recipe_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', ['recipe' => $recipe]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'recipe_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            /** @var User $user */
            $user = $this->getUser();

            $recipe = new Recipe();
            $recipe->setAuthor($user);
            $form = $this->createForm(
                RecipeType::class,
                $recipe,
                ['action' => $this->generateUrl('recipe_create')]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->recipeService->save($recipe);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.created_successfully')
                );

                return $this->redirectToRoute('recipe_index');
            }

            return $this->render('recipe/create.html.twig', ['form' => $form->createView()]);
        } else {
            $this->addFlash(
                'danger',
                $this->translator->trans('message.access_denied')
            );

            return $this->redirectToRoute('recipe_index');
        }
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe  Recipe entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'recipe_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Recipe $recipe): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(
                RecipeType::class,
                $recipe,
                [
                    'method' => 'PUT',
                    'action' => $this->generateUrl('recipe_edit', ['id' => $recipe->getId()]),
                ]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->recipeService->save($recipe);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.edited_successfully')
                );

                return $this->redirectToRoute('recipe_index');
            }

            return $this->render(
                'recipe/edit.html.twig',
                [
                    'form' => $form->createView(),
                    'recipe' => $recipe,
                ]
            );
        } else {
            $this->addFlash(
                'danger',
                $this->translator->trans('message.access_denied')
            );

            return $this->redirectToRoute('recipe_index');
        }
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe  Recipe entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'recipe_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(
                FormType::class,
                $recipe,
                [
                    'method' => 'DELETE',
                    'action' => $this->generateUrl('recipe_delete', ['id' => $recipe->getId()]),
                ]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->recipeService->delete($recipe);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.deleted_successfully')
                );

                return $this->redirectToRoute('recipe_index');
            }

            return $this->render(
                'recipe/delete.html.twig',
                [
                    'form' => $form->createView(),
                    'recipe' => $recipe,
                ]
            );
        } else {
            $this->addFlash(
                'danger',
                $this->translator->trans('message.access_denied')
            );

            return $this->redirectToRoute('recipe_index');
        }
    }

    /**
     * Get filters from request.
     *
     * @param Request $request HTTP request
     *
     * @return array<string, int> Array of filters
     *
     * @psalm-return array{category_id: int}
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');

        return $filters;
    }
}
