<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        WeatherUtil $weatherUtil,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter] bool $twig = false
    ): Response
    {
        $measurements = $weatherUtil->getWeatherForCountryAndCity($country, $city);
        if (!in_array($format, ['json', 'csv'], true)) {
            return $this->json([
                'error' => 'Nieprawidlowy format'
            ], 400);
        }
        if ($twig) {

            // TWIG - format CSV
            if ($format === 'csv') {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ], new Response('', 200, [
                    'Content-Type' => 'text/csv; charset=UTF-8',
                ]));
            }
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ], new Response('', 200, [
                'Content-Type' => 'application/json; charset=UTF-8',
            ]));
        }
        if ($format === 'csv') {

            $csv = "city,country,date,celsius,fahrenheit\n";

            foreach ($measurements as $measurement) {
                $csv .= sprintf(
                    "%s,%s,%s,%s,%s\n",
                    $city,
                    $country,
                    $measurement->getDate()->format('Y-m-d'),
                    $measurement->getCelsius(),
                    $measurement->getFahrenheit()
                );

            }

            return new Response($csv, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }
        return $this->json([
            'city' => $city,
            'country' => $country,

            'measurements' => array_map(fn(Measurement $m) => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getCelsius(),
                'fahrenheit' => $m->getFahrenheit(),
            ], $measurements),
        ]);
    }
}
