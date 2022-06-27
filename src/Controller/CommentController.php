<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\Type\CommentType;
use App\Repository\CommentRepository;
use App\Service\CommentServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CommentController.
 */
#[Route('/comment')]
class CommentController extends AbstractController
{
    /**
     * Comment service.
     */
    private CommentServiceInterface $commentService;

    /**
     * Constructor.
     */
    public function __construct(CommentServiceInterface $commentService, TranslatorInterface $translator)
    {
        $this->commentService = $commentService;
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
        name: 'comment_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $filters = $this->getFilters($request);
        $pagination = $this->commentService->getPaginatedList(
            $request->query->getInt('page', 1),
            $filters,
        );

        return $this->render('comment/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'comment_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $comment = new Comment();
        $comment->setAuthor($user);
        $form = $this->createForm(
            CommentType::class,
            $comment,
            ['action' => $this->generateUrl('comment_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('comment/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'comment_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(
                FormType::class,
                $comment,
                [
                    'method' => 'DELETE',
                    'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId()]),
                ]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->commentService->delete($comment);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.deleted_successfully')
                );

                return $this->redirectToRoute('comment_index');
            }

            return $this->render(
                'comment/delete.html.twig',
                [
                    'form' => $form->createView(),
                    'comment' => $comment,
                ]
            );
        } else {
            $this->addFlash(
                'danger',
                $this->translator->trans('message.access_denied')
            );

            return $this->redirectToRoute('comment_index');
        }
    }

    /**
     * Get filters from request.
     *
     * @param Request $request HTTP request
     *
     * @return array<string, int> Array of filters
     *
     * @psalm-return array{recipe_id: int}
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['recipe_id'] = $request->query->getInt('filters_recipe_id');

        return $filters;
    }
}
