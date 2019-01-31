<?php

namespace App\Controllers;

interface ControllerInterface
{
    /**
     * Methode pour page d'accueil
     */
    public function index();

    /**
     * Methode pour page de creation
     */
    public function add();

    /**
     * Methode pour page de modification
     * @param int $id
     * @return mixed
     */
    public function edit(int $id);

    /**
     * Methode pour page de suppression
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data = []): array;
}