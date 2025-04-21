<?php

namespace App;

use App\PostGenerator;
use Illuminate\Support\Collection;

class PostRepository
{
    private $posts;

    public function __construct()
    {
        $this->posts = PostGenerator::generate(100);
    }

    public function all()
    {
        return $this->posts;
    }

    public function find(string $id)
    {
        return collect($this->posts)->firstWhere('id', $id);
    }
}
