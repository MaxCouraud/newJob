<?php

namespace App\Controller;

use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private const ADMIN_PASSWORD_SESSION_KEY = 'admin_authenticated';

    #[Route('', name: 'admin_login')]
    public function login(Request $request): Response
    {
        if ($request->getSession()->get(self::ADMIN_PASSWORD_SESSION_KEY)) {
            return $this->redirectToRoute('admin_dashboard');
        }

        if ($request->isMethod('POST')) {
            $password = $request->request->get('password', '');
            if ($password === $_ENV['ADMIN_PASSWORD']) {
                $request->getSession()->set(self::ADMIN_PASSWORD_SESSION_KEY, true);
                return $this->redirectToRoute('admin_dashboard');
            }

            return $this->render('admin/login.html.twig', ['error' => 'Mot de passe incorrect.']);
        }

        return $this->render('admin/login.html.twig');
    }

    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(Request $request, VisitRepository $visitRepository): Response
    {
        if (!$request->getSession()->get(self::ADMIN_PASSWORD_SESSION_KEY)) {
            return $this->redirectToRoute('admin_login');
        }

        return $this->render('admin/dashboard.html.twig', [
            'statsByProfile' => $visitRepository->getStatsByProfile(),
            'statsByCode'    => $visitRepository->getStatsByCode(),
            'recentVisits'   => $visitRepository->findRecent(50),
        ]);
    }

    #[Route('/logout', name: 'admin_logout')]
    public function logout(Request $request): Response
    {
        $request->getSession()->remove(self::ADMIN_PASSWORD_SESSION_KEY);
        return $this->redirectToRoute('admin_login');
    }
}
