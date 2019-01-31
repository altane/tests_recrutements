<?php

namespace App\Models;

use App\Database;

abstract class AbstractModel
{
    /** @var string  */
    protected $model;
    /** @var Database  */
    protected $database;

    /**
     * Model constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;

        if (is_null($this->model)) {
            $tab = explode('\\', get_class($this));
            $class = end($tab);
            $this->model = strtolower(str_replace('Model', '', $class)) . 's';
        }
    }

    /**
     * Récupère toutes les valeur d'un model
     *
     * @return array|bool|false|mixed|\PDOStatement
     */
    public function getAll()
    {
        return $this->query('SELECT * FROM ' . $this->model);
    }

    /**
     * Recherche un model par son ID
     *
     * @param $id
     * @return array|bool|false|mixed|\PDOStatement
     */
    public function findById($id)
    {
        return $this->query("SELECT * FROM {$this->model} WHERE id = ?", [$id], true);
    }

    /**
     * Lance une requête préparé
     *
     * @param $statement
     * @param null $attributes
     * @param bool $one
     * @return array|bool|false|mixed|\PDOStatement
     */
    public function query(string $statement, $attributes = null, $one = false)
    {
        if ($attributes) {
            return $this->database->prepare(
                $statement,
                $attributes,
                null,
                $one
            );
        } else {
            return $this->database->query(
                $statement,
                null,
                $one
            );
        }
    }

    /**
     * Méthode de création d'un model
     *
     * @param $fields
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function create(array $fields)
    {
        $fields = $this->cleanInputs($fields);
        $sqlParts = [];
        $attributes = [];
        foreach ($fields as $k => $v) {
            $sqlParts[] = "{$k} = :{$k}";
            $attributes[$k] = $v;
        }
        $sqlPart = implode(', ', $sqlParts);
        return $this->query("INSERT INTO {$this->model} SET $sqlPart",
            $attributes, true);
    }

    /**
     * Méthode de mise à jour d'un model
     *
     * @param $id
     * @param $fields
     * @return array|bool|false|mixed|\PDOStatement
     */
    public function update($id, $fields)
    {
        $fields = $this->cleanInputs($fields);
        $sqlParts = [];
        $attributes = ["id" => $id];
        foreach ($fields as $k => $v) {
            $sqlParts[] = "{$k} = :{$k}";
            $attributes[$k] = $v;
        }
        $sqlPart = implode(', ', $sqlParts);
        return $this->query("UPDATE {$this->model} SET $sqlPart WHERE id = :id",
            $attributes, true);
    }

    /**
     * Supprime un enregistrement
     *
     * @param $id
     * @return array|bool|false|mixed|\PDOStatement
     */
    public function delete($id)
    {
        return $this->query("DELETE FROM {$this->model} WHERE id = ?", [$id],
            true);
    }

    /**
     * Nettoie les valeurs des inputs
     * Supprime les espace inutiles
     * Supprime les balises HTML et PHP
     *
     * @param $data
     *
     * @return array|string
     */
    private function cleanInputs($data)
    {
        $cleanInputs = [];
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $cleanInputs[$k] = $this->cleanInputs($v);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $data = trim(stripslashes($data));
            }
            $data = strip_tags($data);
            $cleanInputs = trim($data);
        }

        return $cleanInputs;
    }
}