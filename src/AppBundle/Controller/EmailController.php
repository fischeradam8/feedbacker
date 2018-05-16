<?php

namespace AppBundle\Controller;

use AppBundle\Form\FeedbackType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{
    /**
     * @Route("/email", name="email")
     */
    public function emailAction(Request $request)
    {
        $title = rawurldecode($request->get('title'));
        $form = $this->createForm(FeedbackType::class, null, [
            'title' => $title,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $feedback = $form->get('feedback')->getViewData();
            $title = $form->get('title')->getViewData();
            $message = (new \Swift_Message('Teszt-email'))
                ->setFrom('feedbacker@placeholder.info')
                ->setTo($request->getSession()->get('email'))
                ->setSubject($title)
                ->setBody($feedback);
            $this->get('mailer')->send($message);
        }
        else {
            $email = rawurldecode($request->get('email'));
            $request->getSession()->set('email', $email);
        }
        return $this->render('default/email.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}