<?php
namespace CMS\BackendBundle\Helper;

class TwigStrRepeatExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'str_repeat' => new \Twig_Filter_Method($this, 'strRepeatFilter'),
        );
    }

    public function strRepeatFilter($str, $repeat)
    {
        return str_repeat($str, $repeat);
    }

    public function getName()
    {
        return 'str_repeat_extension';
    }
}