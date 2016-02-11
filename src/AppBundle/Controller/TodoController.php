<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Todo;
use AppBundle\Form\TodoType;

/**
 * Todo controller.
 *
 * @Route("/todo")
 */
class TodoController extends Controller
{
    /**
     * Lists all Todo entities.
     *
     * @Route("/", name="todo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $todos = $em->getRepository('AppBundle:Todo')->findAll();

        foreach ($todos as $todo)
        {
            $response[] = (array)$todo;
        }

        return new JsonResponse($response);
    }

    /**
     * Creates a new Todo entity.
     *
     * @Route("/new", name="todo_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $todo = new Todo();
        $form = $this->createForm('AppBundle\Form\TodoType', $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_show', array('id' => $todo->id));
        }

        return new JsonResponse([
            'message' => 'failed to create new todo... hint - must be POST, and should have at least an description'
        ]);
    }

    /**
     * Creates a new Todo entity.
     *
     * @Route("/new", name="todo_new_get")
     * @Method({"GET"})
     */
    public function newGetAction(Request $request)
    {
        $todo       = new Todo();
        $todo->date = new \DateTime();
        $form       = $this->createForm('AppBundle\Form\TodoType', $todo);
        $form->handleRequest($request);

        return $this->render('todo/new.html.twig', array(
            'todo' => $todo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Todo entity.
     *
     * @Route("/{id}", name="todo_show")
     * @Method("GET")
     */
    public function showAction(Todo $todo)
    {
        if (empty($todo)) {
            return $this->redirectToRoute('todo_index');
        }
        return new JsonResponse((array)$todo);
    }

    /**
     * Displays a form to edit an existing Todo entity.
     *
     * @Route("/{id}/edit", name="todo_edit_get")
     * @Method({"GET"})
     */
    public function editGetAction(Request $request, Todo $todo)
    {
        $todo->date = new \DateTime();
        $editForm   = $this->createForm('AppBundle\Form\TodoType', $todo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_edit', array('id' => $todo->id));
        }

        return $this->render('todo/edit.html.twig', array(
            'todo'      => $todo,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Todo entity.
     *
     * @Route("/{id}/edit", name="todo_edit")
     * @Method({"POST"})
     */
    public function editAction(Request $request, Todo $todo)
    {
        $editForm = $this->createForm('AppBundle\Form\TodoType', $todo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_show', array('id' => $todo->id));
        }

        return new JsonResponse(['message' => 'Failed to edit. Hint: method must be POST.']);
    }

    /**
     * Deletes a Todo entity.
     *
     * @Route("/{id}", name="todo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Todo $todo)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($todo);
        $em->flush();

        return $this->redirectToRoute('todo_index');
    }
}
