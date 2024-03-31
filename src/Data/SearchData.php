<?php

namespace App\Data;

use App\Entity\Category;
use App\Entity\Course;

class SearchData
{
    public int $page = 1;
    public string $keyword = '';
    /**
     * @var Category[]
     */
    public $category;
    /**
     * @var Course[]
     */
    public $course;
    public ?int $maxDuration;
    public ?int $minDuration;
    public bool $vegetarian;


}