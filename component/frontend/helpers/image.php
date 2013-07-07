<?php

/**
 * @version     frontend/helpers/image.php 2013-07-07 19:56:00Z zanardi
 * @package     GiBi PizzaBox
 * @author      GiBiLogic <info@gibilogic.com>
 * @authorUrl   http://www.gibilogic.com
 * @copyright   (C) 2011-2013 GiBiLogic snc. All rights reserved.
 * @license     GNU/GPL v2 or later
 */
defined('_JEXEC') or die('Restricted access');;

/**
 * ContainersHelperImage
 */
class ContainersHelperImage
{

    public function makeThumbImage($image, $height, $width)
    {
        jimport('joomla.filesystem.file');

        // check if folder exists (or create it if not)
        $thumb_folder = JPATH_SITE . '/images/containers';

        // get full image name
        $ext = JFile::getExt($image);
        $name = JFile::stripExt($image);

        // build resized image path and url
        $resized_image_path = $thumb_folder . "/${name}_${width}x${height}.${ext}";
        $resized_image_url = JRoute::_('/images/containers/' . "${name}_${width}x${height}.${ext}");

        if (!JFile::exists($resized_image_path)) {
            // create thumb if it does not already exist
            $maxsize = false;
            $bgred = 255;
            $bggreen = 255;
            $bgblue = 255;

            //require_once( CLASSPATH .DS. "class.img2thumb.php");
            //$imageResize = new Img2Thumb( $full_image_path, $width, $height,
            //                 $resized_image_path, $maxsize,
            //                $bgred, $bggreen, $bgblue);
        }

        // return url
        return ( $resized_image_url );
    }

}
