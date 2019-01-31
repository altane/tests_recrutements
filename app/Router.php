<?php

namespace App;

/**
 * Class Router
 * @package Core
 */
class Router
{

    /**
     * Liste des routes
     * @var array
     */
    protected $routes = [];

    /**
     * Paramètres des routes trouvées
     * @var array
     */
    protected $params = [];

    /**
     * Ajoute une route à la liste des routes
     *
     * @param string $route  The route URL
     * @param array  $params Parameters (controller, action, etc.)
     *
     * @return void
     */
    public function add($route, $params = [])
    {

        //Convertir la route en une expression régulière: échapper par des slashes
        $route = preg_replace('/\//', '\\/', $route);

        // Convertie les variables exemple: {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        // Convertie les variables contenant des expressions régulières exemple: {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // Ajouter des délimiteurs de début et de fin et un indicateur insensible à la casse
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;

    }

    /**
     * Récupère la liste des routes
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Recherche la route correspondante dans la liste des routes
     * et assigne les paramètres si une correspondance est trouvée
     *
     * @param string $url L'URL de la route
     *
     * @return boolean true si correspondance, false sinon
     */
    public function match($url)
    {

        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {

                // Get named capture group values
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    /**
     * Récupère les paramètres courant
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Dispatch la route, puis créer le contrôleur et exécuter la méthode d'action
     *
     * @param string $url L'URL de la route
     *
     * @return void
     * @throws \Exception
     */
    public function dispatch($url)
    {

        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            if (!empty($this->params['namespace']) && $this->params['namespace'] == "api") {
                $controller = 'App\Components\Api\Api';
            } else {
                $controller = $this->params['controller'];
                $controller = $this->convertToStudlyCaps($controller);
                $controller .= 'Controller';
                $controller = $this->getNamespace($controller);

            }
            $this->callController($controller);


        } else {
            throw new \Exception('No route matched.', 404);
        }
    }

    /**
     * @param string $controller
     * @throws \Exception
     */
    private function callController(string $controller)
    {
        if (class_exists($controller)) {
            $controller_object = new $controller($this->params);
            $action = $this->params['action'];
            $action = $this->convertToCamelCase($action);
            if (preg_match('/action$/i', $action) == 0) {
                $parameters = $this->params;
                unset($parameters['controller']);
                unset($parameters['action']);
                unset($parameters['namespace']);
                call_user_func_array(array($controller_object, $action), $parameters);

            } else {
                throw new \Exception("La méthode: $action du controller: $controller ne peut pas être directement appelée - Supprimmez le suffix de l'action pour appelé la méthode.");
            }
        } else {
            throw new \Exception("Controller: $controller introuvable.");
        }
    }

    /**
     * Convertie la chaîne de caractères avec des traits d'union en StudlyCaps,
     * Exemple: post-authors => PostAuthors
     *
     * @param string $string La chaîne à convertir
     *
     * @return string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convertie la chaîne de caractères avec des traits d'union en camelCase,
     * e.g. add-new => addNew
     *
     * @param string $string The string to convert
     *
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Retourne l'URL après avoir supprimé tout les paramètres GET
     *
     * Example:
     *
     *   URL                           $_SERVER['QUERY_STRING']  Route
     *   -------------------------------------------------------------------
     *   localhost                     ''                        ''
     *   localhost/?                   ''                        ''
     *   localhost/?page=1             page=1                    ''
     *   localhost/posts?page=1        posts&page=1              posts
     *   localhost/posts/index         posts/index               posts/index
     *   localhost/posts/index?page=1  posts/index&page=1        posts/index
     *
     * @param string $url l'URL complète
     *
     * @return string L'URL dépourvue des paramètres GET
     */
    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }

    /**
     * Récupère l'espace de noms pour la classe du contrôleur.
     * L'espace de noms défini dans les paramètres de route sont ajoutés si ils sont présents.
     *
     * @param string $controller
     * @return string
     * @throws \Exception
     */
    protected function getNamespace(string $controller)
    {
        $application = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $application .= $this->params['namespace'] . '\\';
        }

        $application .= $controller;

        if (!class_exists($application)) {
            throw new \Exception("Controller: $controller n'existe pas.");
        }

        return $application;
    }
}
