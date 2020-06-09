<?php


namespace App\Home;


use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;


class SearchHome
{
    /**
     * @var integer
     */
    public $page =1 ;
    /**
     * @var Category[]
     */
    private $categories = [];

    /**
     * @var null |  integer
     * * @Assert\Range(
     *      max= 100000,
     *      notInRangeMessage =" This value should be {{ max }} or more.",
     *     )
     *     @Assert\Range(
     *      min= 0,
     *      notInRangeMessage =" This value should be {{ min }} or less.",
     * )
     *
     */
    private  $max;

    /**
     * @var null |  integer
     * * @Assert\Range(
     *      min = 0,
     *      notInRangeMessage =" This value should be {{ min }} or more.",
     * )
     */
    private  $min;

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return int|null
     */
    public function getMax(): ?int
    {
        return $this->max;
    }

    /**
     * @param int|null $max
     */
    public function setMax(?int $max): void
    {
        $this->max = $max;
    }

    /**
     * @return int|null
     */
    public function getMin(): ?int
    {
        return $this->min;
    }

    /**
     * @param int|null $min
     */
    public function setMin(?int $min): void
    {
        $this->min = $min;
    }

}