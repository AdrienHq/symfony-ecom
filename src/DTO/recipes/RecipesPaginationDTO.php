<?php

namespace App\DTO\recipes;

use Symfony\Component\Validator\Constraints as Assert;

class RecipesPaginationDTO
{
    public function __construct(
        #[Assert\Positive()]
        public readonly ?int $page = 1
    )
    {
    }

}