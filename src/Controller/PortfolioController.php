<?php

namespace App\Controller;

use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PortfolioController extends AbstractController
{
    private const ALLOWED_PROFILES = ['dev', 'chef-projet', 'maintenance', 'support'];

    #[Route('/portfolio/{profile}', name: 'portfolio')]
    public function show(
        string $profile,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        // Guard: must have a valid session or a valid code in the URL
        $sessionProfile = $request->getSession()->get('profile_type');
        $sessionCode = $request->getSession()->get('recruiter_code');

        if ($sessionProfile !== $profile || !in_array($profile, self::ALLOWED_PROFILES, true)) {
            return $this->redirectToRoute('home');
        }

        // Track page view
        $visit = new Visit(
            $sessionCode ?? 'unknown',
            $profile,
            $request->getClientIp(),
            $request->headers->get('User-Agent'),
            '/portfolio/' . $profile
        );
        $em->persist($visit);
        $em->flush();

        return $this->render('portfolio/show.html.twig', [
            'profile' => $profile,
        ]);
    }
}
