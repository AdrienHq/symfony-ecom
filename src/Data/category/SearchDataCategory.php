<?php

namespace App\Data\category;

use App\Entity\Category;
use App\Entity\Course;

class SearchDataCategory
{
    public int $page = 1;
    public string $q = '';
    /**
     * @var Course[]
     */
    public $course;
    public ?int $maxDuration;
    public ?int $minDuration;
    public bool $vegetarian;

    public function __construct()
    {
        $this->q = '';
        $this->course = null;
        $this->maxDuration = null;
        $this->minDuration = null;
        $this->vegetarian = false;
    }
}