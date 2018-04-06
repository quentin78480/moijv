<?php

namespace App\DataTransformers;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;


class TagTransformer implements DataTransformerInterface
{
    /**
     *
     * @var TagRepository 
     */
    private $tagRepo;
            
    public function __construct(TagRepository $tagRepo) {
        $this->tagRepo = $tagRepo;
    }
   
    public function reverseTransform($TagString) {
        $tagArray = array_unique(explode(',', $TagString));
        $tagCollection = new ArrayCollection();
        foreach ($tagArray as $tagName) {
           $tagCollection->add($this->tagRepo->getCorrespondingTag($tagName));
        }
        return $tagCollection;
    }

    public function transform($TagCollection) {
        // array_map(function, array)
        $tagArray = $TagCollection->toArray();
        $nameArray = array_map(function($tag){return $tag->getName();}, $tagArray);
        return implode(',', $nameArray);
    }

}
