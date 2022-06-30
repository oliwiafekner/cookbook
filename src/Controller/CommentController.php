<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\Type\CommentType;
use App\Service\CommentServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     *
     * @param CommentServiceInterface $commentService
     * @param TranslatorInterface $translator
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
        '/{id}',
        name: 'comment_index',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function index(Request $request, Recipe $recipe): Response
    {
        $pagination = $this->commentService->getPaginatedList(
            $request->query->getInt('page', 1),
            $recipe
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
    #[Route('/{id}/create', name: 'comment_create', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function create(Request $request, Recipe $recipe): Response
    {
        $comment = new Comment();
        $comment->setRecipe($recipe);
        $form = $this->createForm(
            CommentType::class,
            $comment,
            ['action' => $this->generateUrl('comment_create', ['id' => $recipe->getId()])]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('comment_index', ['id' => $recipe->getId()]);
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

                return $this->redirectToRoute('comment_index', ['id' => $comment->getId()]);
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

            return $this->redirectToRoute('comment_index', ['id' => $comment->getId()]);
        }
    }
}
