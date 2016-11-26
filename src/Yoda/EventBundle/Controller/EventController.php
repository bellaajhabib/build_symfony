<?php

namespace Yoda\EventBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Constraints\DateTime;
use Yoda\EventBundle\Entity\Event;
use Yoda\EventBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Event controller.
 *
 */
class EventController extends Controller
{
    /**
     * @Template()
     * @Route("/", name="event")
     * Lists all Event entities.
     *
     */
    public function indexAction()
    {


        return  array();
    }
    public function _upcomingEventsAction($max = null)
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('EventBundle:Event')
            ->getUpcomingEvents($max)
        ;

        return $this->render('EventBundle:Event:_upcomingEvents.html.twig', array(
            'entities' => $events,
        ));
    }
    /**
     * Creates a new Event entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->enforceUserSecurity();
        $event = new Event();
        $form = $this->createForm('Yoda\EventBundle\Form\EventType', $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $use=$this->getUser();
            $event->setOwner($use);
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_show', array('slug' => $event->getSlug()));
        }

        return $this->render('@Event/event/new.html.twig', array(
            'event' => $event,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Event entity.
     *
     */
    public function showAction($slug)
    {   $this->enforceUserSecurity();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EventBundle:Event')
            ->findOneBy(array('slug' => $slug));
        $deleteForm = $this->createDeleteForm($entity->getId());

        return $this->render('@Event/event/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Event entity.
     *
     */
    public function editAction($id)
    {
        $this->enforceUserSecurity();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EventBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $this->enforceOwnerSecurity($entity);

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EventBundle:Event:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Event entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $this->enforceUserSecurity();
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EventBundle:Event')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Event entity.');
            }

            $this->enforceOwnerSecurity($entity);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('event'));
    }

    /**
     * Creates a form to delete a Event entity.
     *
     * @param Event $event The Event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {   $this->enforceUserSecurity();
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    private function enforceUserSecurity()
    {
        $securityContext =$this->get('security.authorization_checker');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (false === $this->getSecurityContext()->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Need ROLE_ADMIN!');
        }
    }
    /**
     * Creates a form to edit a Event entity.
     *
     * @param Event $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Event $entity)
    {
        $form = $this->createForm(EventType::class, $entity, array(
            'action' => $this->generateUrl('event_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Event entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $this->enforceUserSecurity();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EventBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $this->enforceOwnerSecurity($entity);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('event_edit', array('id' => $id)));
        }

        return $this->render('EventBundle:Event:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function attendAction($id,$format)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $event \Yoda\EventBundle\Entity\Event */
        $event = $em->getRepository('EventBundle:Event')->find($id);

        if(!$event->hasAttendee($this->getUser())){
            $event->getAttendees()->add($this->getUser());
        }


        if (!$event) {
            throw $this->createNotFoundException('No event found for id '.$id);
        }
        $em->persist($event);
        $em->flush();
       if($format =='json'){
           return $this->createAttendingResponse($event, $format);
       }
        $url = $this->generateUrl('event_show', array(
            'slug' => $event->getSlug(),
        ));

        return $this->redirect($url);
    }

    public function unattendAction($id, $format)
    {
     $em=$this->getDoctrine()->getManager();
     /** @var $event \Yoda\EventBundle\Entity\Event  */
     $event=$em->getRepository('EventBundle:Event')->find($id);
     if($event->hasAttendee($this->getUser())){
         $event->getAttendees()
                ->removeElement($this->getUser());
     }
        $em->persist($event);
        $em->flush();
        if ($format == 'json') {
            return $this->createAttendingResponse($event, $format);
        }
        $url = $this->generateUrl('event_show', array(
            'slug' => $event->getSlug(),
        ));

        return $this->redirect($url);
    }
    /**
     * @param Event $event
     * @param string $format
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function createAttendingResponse(Event $event, $format)
    {
        if ($format == 'json') {
            $data = array(
                'attending' => $event->hasAttendee($this->getUser())
            );

            $response = new JsonResponse($data);

            return $response;
        }

        $url = $this->generateUrl('event_show', array(
            'slug' => $event->getSlug()
        ));

        return $this->redirect($url);
    }
}
