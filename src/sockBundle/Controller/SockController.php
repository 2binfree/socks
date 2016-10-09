<?php

namespace sockBundle\Controller;

use sockBundle\Repository\SockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use sockBundle\Entity\Sock;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sock controller.
 *
 */
class SockController extends Controller
{
    const MAX_PER_PAGE = 9;

    /**
     * Lists all Sock entities.
     * @param integer $page
     * @return Response
     */
    public function indexAction($page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $socks = $em->getRepository('sockBundle:Sock')->getByPage($page, self::MAX_PER_PAGE);

        $total = count($socks);
        $maxPage = (int)($total / SockRepository::MAX_RESULT);
        return $this->render('sock/index.html.twig', array(
            'maxPage'       => $maxPage,
            'socks'         => $socks,
            'page'          => $page
        ));
    }

    /**
     * Creates a new Sock entity.
     *
     */
    public function newAction(Request $request)
    {
        $sock = new Sock();
        $form = $this->createForm('sockBundle\Form\SockType', $sock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $sock->getPicture();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('upload_directory'),
                $fileName
            );
            $sock->setPicture($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($sock);
            $em->flush();

            return $this->redirectToRoute('sock_show', array('id' => $sock->getId()));
        }

        return $this->render('sock/new.html.twig', array(
            'sock' => $sock,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Sock entity.
     *
     */
    public function showAction(Sock $sock)
    {
        $deleteForm = $this->createDeleteForm($sock);

        return $this->render('sock/show.html.twig', array(
            'sock' => $sock,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Sock entity.
     *
     */
    public function editAction(Request $request, Sock $sock)
    {
        $deleteForm = $this->createDeleteForm($sock);
        $editForm = $this->createForm('sockBundle\Form\SockType', $sock);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sock);
            $em->flush();

            return $this->redirectToRoute('sock_edit', array('id' => $sock->getId()));
        }

        return $this->render('sock/edit.html.twig', array(
            'sock' => $sock,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Sock entity.
     *
     */
    public function deleteAction(Request $request, Sock $sock)
    {
        $form = $this->createDeleteForm($sock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sock);
            $em->flush();
        }

        return $this->redirectToRoute('sock_index');
    }

    /**
     * Creates a form to delete a Sock entity.
     *
     * @param Sock $sock The Sock entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Sock $sock)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sock_delete', array('id' => $sock->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
