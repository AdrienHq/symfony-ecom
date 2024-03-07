<?php

namespace App\DTO\course;

class CourseWithCountDTO
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly int    $count,
    )
    {
    }

}