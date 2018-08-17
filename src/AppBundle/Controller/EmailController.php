<?php

namespace AppBundle\Controller;

use AppBundle\Form\FeedbackType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
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
            try {
                $message = (new \Swift_Message('Teszt-email'))
                    ->setFrom('feedbacker@placeholder.info')
                    ->setTo($request->getSession()->get('email'))
                    ->setSubject($title)
                    ->setCc('kovacs.kitti@virgo.hu') //TODO kiszedni retro után
                    ->setBody($feedback);
            } catch (\Swift_RfcComplianceException $e) {
                $this->addFlash('error', 'Váratlan hiba történt!');
                return $this->renderEmail($form);
            }
            if ($this->get('mailer')->send($message) === 0) {
                $this->addFlash('error', 'Váratlan hiba történt!');
                return $this->renderEmail($form);
            }
            else {
                return $this->render('default/success.html.twig', []);
            }
        }
        else {
            $email = rawurldecode($request->get('email'));
            $request->getSession()->set('email', $email);
        }
        return $this->renderEmail($form);
    }

    private function renderEmail(FormInterface $form)
    {
        return $this->render('default/email.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}