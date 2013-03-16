<?php defined('_JEXEC') or die('Restricted access');
/**
 * @version		$Id: helpers/helper.php 2012-01-12 13:57:00Z zanardi $
 * @Package		Containers
 * @copyright	Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		GNU/GPLv2
 */

// Component Helper
jimport('joomla.application.component.helper');

class ContainersHelperImage {

	function makeThumbImage( $image, $height, $width )
	{
		jimport( 'joomla.filesystem.file' );

		// check if folder exists (or create it if not)
		$thumb_folder = JPATH_SITE.DS.'images'.DS.'containers';
	
		// get full image name
		$ext = JFile::getExt( $image );
		$name = JFile::stripExt( $image );
		
		// build resized image path and url
		$resized_image_path = $thumb_folder.DS."${name}_${width}x${height}.${ext}";
		$resized_image_url = JRoute::_( '/images/containers/' . "${name}_${width}x${height}.${ext}" );

		if ( ! JFile::exists( $resized_image_path ) ) {
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
