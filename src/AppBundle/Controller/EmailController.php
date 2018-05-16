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
        $iv = $request->get('iv');
        $title = openssl_decrypt($request->get('title'), 'aes-256-cbc', 'abcde', 0, $iv);
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
            $email = openssl_decrypt($request->get('email'), 'aes-256-cbc', 'abcde', 0, $iv);
            $request->getSession()->set('email', $email);
            return $this->render('default/email.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

}