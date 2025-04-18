<?php

namespace App;


class Validator implements ValidatorInterface
{
    public function validate($course )
    {

        $errors = [];

        if (empty($course['title'])) {

                $errors['title'] = 'Could not be blank';
        }
         if ($course['paid'] !== 'false' && $course['paid'] !== 'true') {
             $errors['paid'] = 'Could not be blank';
         }

        return $errors;


    }
}
