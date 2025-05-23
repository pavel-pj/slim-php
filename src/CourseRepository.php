<?php

namespace App;

class CourseRepository
{
    public function __construct()
    {
        session_start();
    }

    public function all()
    {
        return array_values($_SESSION);
    }

    public function find(string $id)
    {
        if (!isset($_SESSION[$id])) {
            throw new \Exception("Wrong course id: {$id}");
        }

        return $_SESSION[$id];
    }

    public function save(array $item)
    {
        if (empty($item['title']) || $item['paid'] === '') {
            $json = json_encode($item);
            throw new \Exception("Wrong data: {$json}");
        }
        $item['id'] = uniqid();
        $_SESSION[$item['id']] = $item;
    }
}
