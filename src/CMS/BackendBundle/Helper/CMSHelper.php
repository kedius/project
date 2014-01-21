<?php

namespace CMS\BackendBundle\Helper;


use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class CMSHelper
 * @package CMS\BackendBundle\Helper
 */
class CMSHelper {

    protected $_container;

    public function __construct(Container $container) {
        $this->_container = $container;
    }

    /**
     * @param $file \Symfony\Component\HttpFoundation\File\UploadedFileInterface object UploadedFiles
     * @param null $pathAd path to upload dir
     * @param bool $originalname save the original file name {true|false}
     * @return array
     */
    public function uploadFile($file, $pathAd = null, $originalname = false) {
        $path = 'uploads/';
        if ($pathAd != null) {
            $path .= $pathAd;
        }
        if ($originalname == true) {
            $fileName = $file->getClientOriginalName();
        } else {
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
        }
        $fileType = $file->getMimeType();

        $file->move($path, $fileName);

        return array(
            'name' => $fileName,
            'path' => $path,
            'type' => $fileType,
        );
    }

    /**
     * Set validate notice to FlashBug
     *
     * @param $form \Symfony\Component\Form\FormInterface takes an object Form
     * @return bool
     */
    public function invalidNotice($form)
    {
        $validator = $this->_container->get('validator');
        $errors = $validator->validate($form->getData());
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $this->_container->get('session')->getFlashBag()->add('notice', $error->getMessage());
            }
        }
        return true;
    }

    /**
     * Convert russian characters to english
     *
     * @param $str string
     * @return string
     */
    public function translitIt($str) {
        if (preg_match('/[^A-Za-z0-9_\-]/', $str)) {
            $tr = array(
                "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
                "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
                "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
                "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
                "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
                "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
                "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
                "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
                "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
                "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
                "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
                "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
                "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
                " "=> "_", "."=> "", "/"=> "_"
            );
            $urlstr = strtr($str,$tr);
            return preg_replace('/[^A-Za-z0-9_\-]/', '', $urlstr);
        }
        return $str;
    }
}