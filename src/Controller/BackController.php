<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;

final class BackController extends AbstractController
{
    #[Route('/back', name: 'app_back')]
    public function index(): Response
    {
        return $this->render('back/index.html.twig');
    }

    #[Route('/charts', name: 'app_charts')]
    public function charts(UserRepository $userRepository): Response
    {
        // Get data for the pie chart (for example, gender distribution)
        $genderData = $userRepository->createQueryBuilder('u')
            ->select('u.sexe, COUNT(u.id) as count')
            ->groupBy('u.sexe')
            ->getQuery()
            ->getResult();

        // Prepare the data for the pie chart
        $pieLabels = [];
        $pieData = [];
        foreach ($genderData as $data) {
            $pieLabels[] = $data['sexe'];
            $pieData[] = $data['count'];
        }

        // Get data for the bar chart (for example, users joined by month)
        
        $users = $userRepository->findAll();
        $monthCounts = array_fill(1, 12, 0); // Initialize an array to hold counts for each month

        foreach ($users as $user) {
            if ($user->getDateNaissance()) {
                $month = $user->getDateNaissance()->format('n'); // Extract month (1 = January, 12 = December)
                $monthCounts[$month]++;
            }
        }

        // Prepare the data for the bar chart
        $barLabels = [];
        $barData = [];
        foreach ($monthCounts as $month => $count) {
            $barLabels[] = $this->getMonthName($month);
            $barData[] = $count;
        }

        return $this->render('user/chart.html.twig', [
            'pieLabels' => $pieLabels,
            'pieData' => $pieData,
            'barLabels' => $barLabels,
            'barData' => $barData,
        ]);
    }

    // Helper function to get month name
    private function getMonthName($month)
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May',
            6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October',
            11 => 'November', 12 => 'December'
        ];
        return $months[$month];
    }
}

