<?php
/**
 * Clase que usa el api de http://es.wikiquote.org para obtener la frase de ma semana.
 * */

namespace AlexR1712;

class Forismatic
{
    private $url = 'https://api.forismatic.com/api/1.0/';

    public function getQuote()
    {
        $parameters = ['method'=> 'getQuote', 'lang' => 'en', 'format' => 'json'];
        $query = $this->url.'?'.http_build_query($parameters);
        $result = json_decode(file_get_contents($query), true);

        return $result;
    }
}
