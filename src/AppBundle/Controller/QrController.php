<?php


namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;

class QrController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function generate(Request $request)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = rawurlencode($form->get('email')->getData());
            $title = rawurlencode($form->get('title')->getData());

            $options = [
                'code' => $this->generateUrl('email', [
                    'email' => $email,
                    'title' => $title,
                ], UrlGenerator::ABSOLUTE_URL),
                'type' => 'qrcode',
                'format' => 'svg',
                'color' => $form->get('color')->getData(),
                'height' => (int) $form->get('size')->getData(),
                'width' => (int) $form->get('size')->getData(),
            ];
            $barcode = $this->get('skies_barcode.generator')->generate($options);
            $response = new Response($barcode);
            $response->headers->set('Content-Type', 'image/svg+xml');
            return $response;

        }
        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}