<?php

namespace App\Controller;

use App\Services\MovieService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MoviesController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(MovieService $movieService, HttpClientInterface $client): Response
    {
        $genderList = $movieService->getMoviesGenres();
        $movies = $movieService->getMovies();
        if (isset($movies[0])) {
            $movieVideo = $movieService->getMoviesVideo($movies[0]['id']);
        }
        return $this->render('home/index.html.twig', [
            'genres' => $genderList,
            'movies' => $movies,
            'movieKey' => $movieVideo,
            'controller_name' => 'MoviesController',
        ]);
    }

    #[Route('ajax/movies/{slug}', name: 'movies', defaults: ['slug' => ""])]
    public function movies(MovieService $movieService, string $slug): Response
    {
        $moviesWithGenre = $movieService->getMoviesWithGenres($slug);
        if (isset($moviesWithGenre[0])) {
            $movieVideo = $movieService->getMoviesVideo($moviesWithGenre[0]['id']);
        }
        return $this->render('home/movies.html.twig', [
            'movies' => $moviesWithGenre,
            'movieKey' => $movieVideo,
            'controller_name' => 'MoviesController',
        ]);
    }

    #[Route('ajax/movies/details/{id}', name: 'movie-details', defaults: ['id' => ""])]
    public function movieDetails(MovieService $movieService, $id): Response
    {
        $movie = $movieService->getMovieDetails($id);
        dump($movie);
        return $this->render('home/details.html.twig', [
            'movie' => $movie,
            'controller_name' => 'MoviesController',
        ]);
    }

    #[Route('ajax/movies/search/{str}', name: 'movie-search', defaults: ['str' => ""])]
    public function movieSearch(MovieService $movieService, $str): Response
    {
        $moviesSearch = $movieService->searchMovies($str);
        return $this->render('home/movies-search.html.twig', [
            'moviesSearch' => $moviesSearch,
            'controller_name' => 'MoviesController',
        ]);
    }
}
