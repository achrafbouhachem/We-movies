<?php

namespace App\Services;

use SebastianBergmann\Environment\Console;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MovieService
{
    const API_URL = 'https://api.themoviedb.org/3/';
    const MOVIE_POSTER_PATH = "http://image.tmdb.org/t/p/";
    const VIDEO_URL = "https://www.youtube.com/embed/";

    private $api_key;

    private $httpClient;

    public function __construct(string $api_key, HttpClientInterface $httpClient)
    {
        $this->api_key = $api_key;
        $this->httpClient = $httpClient;
        dump($this->api_key);
    }

    function getMoviesGenres()
    {
        $response = $this->httpClient->request(
            'GET',
            self::API_URL . 'genre/movie/list?api_key=' . $this->api_key . '&language=fr-FR'
        );
        return $response->toArray()['genres'];
    }

    function getMovies()
    {
        $response = $this->httpClient->request(
            'GET',
            self::API_URL . 'movie/top_rated?api_key=' . $this->api_key . '&language=fr-FR&page=1'
        );
        return array_map([$this, 'moviePoster'],$response->toArray()['results']);
    }

    function getMoviesWithGenres($ids = "")
    {
        $response = $this->httpClient->request(
            'GET',
            self::API_URL . 'discover/movie?api_key=' . $this->api_key . '&sort_by=popularity.desc&include_adult=false&include_video=false&page=1&with_genres='.$ids.'&language=fr-FR'
        );
        return array_map([$this, 'moviePoster'],$response->toArray()['results']);
    }

    function getMoviesVideo($movieID)
    {
        $response = $this->httpClient->request(
            'GET',
            self::API_URL . '/movie/'.$movieID.'/videos?language=fr-FR&api_key=' . $this->api_key 
        );
        return $response->toArray()['results'] ? self::VIDEO_URL.$response->toArray()['results'][0]["key"] : null ;
    }

    function getMovieDetails($movieID)
    {
        $response = $this->httpClient->request(
            'GET',
            self::API_URL . '/movie/'.$movieID.'?language=fr-FR&api_key=' . $this->api_key 
        );
        return $this->moviePoster($response->toArray());
    }

    function searchMovies(string $str)
    {
        $response = $this->httpClient->request(
            'GET',
            self::API_URL . '/search/movie?language=fr-FR&api_key=' . $this->api_key .'&query=' . $str
        );
        return array_slice($response->toArray()['results'],0,8);
    }

    function moviePoster(Array $arr)
    {
        $arr["poster_path"] = self::MOVIE_POSTER_PATH. 'w200' .$arr["poster_path"];
        return $arr;
    }
}
