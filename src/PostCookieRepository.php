<?php

namespace App;

class PostCookieRepository
{
    public function __construct()
    {
    }

    public function all($request)
    {
        return  json_decode($request->getCookieParam('posts', json_encode([])), true);
    }


    public function save(array $item , $request )
    {
        if (empty($item['name']) || empty($item['body'])) {
            $json = json_encode($item);
            throw new \Exception("Wrong data: {$json}");
        }
        if (!isset($item['id'])) {
            $item['id'] = uniqid();
        }

         $posts = json_decode($request->getCookieParam('posts', json_encode([])), true);
         $posts[] = $item;
         $encodedPost = json_encode($posts);
        
        return $encodedPost;
    }
}
