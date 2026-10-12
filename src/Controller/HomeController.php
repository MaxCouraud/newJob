<?php

namespace App\Controller;

use App\Entity\Visit;
use App\Service\RecruiterCodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/enter', name: 'enter_code', methods: ['POST'])]
    public function enterCode(
        Request $request,
        RecruiterCodeService $codeService,
        EntityManagerInterface $em,
    ): Response {
        $code = strtoupper(trim((string) $request->request->get('code', '')));

        if (!$codeService->isValid($code)) {
            return $this->render('home/index.html.twig', [
                'error' => 'Code invalide. Vérifiez votre email ou contactez-moi directement.',
            ]);
        }

        $profileType = $codeService->getProfileType($code);

        // Track the visit
        $visit = new Visit(
            $code,
            $profileType,
            $request->getClientIp(),
            $request->headers->get('User-Agent'),
            '/'
        );
        $em->persist($visit);
        $em->flush();

        $request->getSession()->set('profile_type', $profileType);
        $request->getSession()->set('recruiter_code', $code);

        return $this->redirectToRoute('portfolio', ['profile' => $profileType]);
    }
}
