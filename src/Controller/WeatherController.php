<?php

namespace App\Controller;

use App\Entity\Location;
use App\Service\WeatherUtil;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
#[Route('/weather/{city}/{country}', name: 'app_weather')]
public function city(
    string $city,
    string $country,
    LocationRepository $locationRepository,
    WeatherUtil $util
): Response {
    $location = $locationRepository->findByCityAndCountry($city, $country);

    if (!$location) {
        throw $this->createNotFoundException('Location not found');
    }

    $measurements = $util->getWeatherForCountryAndCity($country, $city);

    return $this->render('weather/city.html.twig', [
        'location' => $location,
        'measurements' => $measurements,
    ]);
}
}
