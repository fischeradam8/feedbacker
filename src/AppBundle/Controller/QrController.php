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
        $key = 'abcde';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = openssl_encrypt($form->get('email')->getData(), 'aes-256-cbc', $key, 0, $iv);
            $title = openssl_encrypt($form->get('title')->getData(), 'aes-256-cbc', $key, 0, $iv);

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