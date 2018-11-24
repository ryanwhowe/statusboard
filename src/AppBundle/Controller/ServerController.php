<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Server;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Server controller.
 *
 * @Route("admin/server")
 */
class ServerController extends Controller
{
    /**
     * Lists all server entities.
     *
     * @Route("/", name="admin_server_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $servers = $em->getRepository('AppBundle:Server')->findAll();

        return $this->render('server/index.html.twig', array(
            'servers' => $servers,
        ));
    }

    /**
     * Creates a new server entity.
     *
     * @Route("/new", name="admin_server_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $server = new Server();
        $form = $this->createForm('AppBundle\Form\ServerType', $server);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($server);
            $em->flush();

            return $this->redirectToRoute('admin_server_show', array('id' => $server->getId()));
        }

        return $this->render('server/new.html.twig', array(
            'server' => $server,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a server entity.
     *
     * @Route("/{id}", name="admin_server_show")
     * @Method("GET")
     */
    public function showAction(Server $server)
    {
        $deleteForm = $this->createDeleteForm($server);

        return $this->render('server/show.html.twig', array(
            'server' => $server,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing server entity.
     *
     * @Route("/{id}/edit", name="admin_server_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Server $server)
    {
        $deleteForm = $this->createDeleteForm($server);
        $editForm = $this->createForm('AppBundle\Form\ServerType', $server);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_server_edit', array('id' => $server->getId()));
        }

        return $this->render('server/edit.html.twig', array(
            'server' => $server,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a server entity.
     *
     * @Route("/{id}", name="admin_server_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Server $server)
    {
        $form = $this->createDeleteForm($server);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($server);
            $em->flush();
        }

        return $this->redirectToRoute('admin_server_index');
    }

    /**
     * Creates a form to delete a server entity.
     *
     * @param Server $server The server entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Server $server)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_server_delete', array('id' => $server->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
