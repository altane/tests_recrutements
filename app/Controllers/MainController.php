<?php

namespace App\Controllers;

use App\App;
use App\Components\Auth\Auth;
use \Twig_Loader_Filesystem;
use \Twig_Environment;

class MainController
{
    /** @var Twig_Environment */
    protected $twig;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->loader = new \Twig_Loader_Filesystem(ROOT . '/app/Views');
        $this->twig   = new \Twig_Environment($this->loader);
        $this->auth   = new Auth(App::getInstance()->getDatabase());
        $this->twig->addGlobal('session', $_SESSION);
    }

    /**
     * Méthode de chargement de model
     * @param $model
     */
    protected function loadModel($model)
    {
        $this->$model = App::getInstance()->getModel($model);
    }

    /**
     * Requête vers l'API
     * @param $endpoint
     * @param array $datas
     * @return mixed
     */
    public function apiClient($endpoint, $datas = [])
    {
        $api = "{$_SERVER["REQUEST_SCHEME"]}://{$_SERVER['HTTP_HOST']}/api/{$endpoint}";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if (!empty($datas)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($datas));
        }

        $return = curl_exec($curl);
        curl_close($curl);

        return json_decode($return);
    }
}