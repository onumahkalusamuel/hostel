<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{

    public function __construct(Session $session)
    {

        $this->session = $session;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('flashes', [$this, 'getFlashes']),
            new TwigFunction('get', [$this, 'getParams']),
        ];
    }

    public function getFlashes($type = null)
    {
        if ($type == null) return $this->session->getFlashBag();
        else return $this->session->getFlashBag()->get($type);
    }
    
    public function getParams($key = null){
    	if ($key == null) return $_GET;
        else return $_GET[$key];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('date', [$this, 'formatDate']),
            new TwigFilter('price', [$this, 'formatPrice']),

        ];
    }

    public function formatDate($date, $pattern = "jS M, Y")
    {
        $datey = strtotime($date);
        return date($pattern, $datey);
    }

    public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price;

        return $price;
    }
}
