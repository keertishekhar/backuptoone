<?php
/*
	mobble
	------
	Author: Scott Evans
	Version: 1.5
	Author URI: http://scott.ee
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html

	Copyright (c) 2013 Scott Evans <http://scott.ee>

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	THIS SOFTWARE AND DOCUMENTATION IS PROVIDED "AS IS," AND COPYRIGHT
	HOLDERS MAKE NO REPRESENTATIONS OR WARRANTIES, EXPRESS OR IMPLIED,
	INCLUDING BUT NOT LIMITED TO, WARRANTIES OF MERCHANTABILITY OR
	FITNESS FOR ANY PARTICULAR PURPOSE OR THAT THE USE OF THE SOFTWARE
	OR DOCUMENTATION WILL NOT INFRINGE ANY THIRD PARTY PATENTS,
	COPYRIGHTS, TRADEMARKS OR OTHER RIGHTS.COPYRIGHT HOLDERS WILL NOT
	BE LIABLE FOR ANY DIRECT, INDIRECT, SPECIAL OR CONSEQUENTIAL
	DAMAGES ARISING OUT OF ANY USE OF THE SOFTWARE OR DOCUMENTATION.

	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://gnu.org/licenses/>.

*/

if ( ! class_exists( 'Mobile_Detect' ) ){
	require_once ( TIELABS_TEMPLATE_PATH . '/framework/vendor/Mobile_Detect/Mobile_Detect.php');
}

function tie_int_Mobile_Detect(){
	$mobble_detect = new Mobile_Detect();
	$mobble_detect->setDetectionType( 'extended' );
	return $mobble_detect;
}

/***************************************************************
* Function tie_is_iphone
* Detect the iPhone
***************************************************************/

function tie_is_iphone(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->isIphone();
}

/***************************************************************
* Function tie_is_ipad
* Detect the iPad
***************************************************************/

function tie_is_ipad(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->isIpad();
}

/***************************************************************
* Function tie_is_ipod
* Detect the iPod, most likely the iPod touch
***************************************************************/

function tie_is_ipod(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->is( 'iPod' );
}

/***************************************************************
* Function tie_is_android
* Detect an android device.
***************************************************************/

function tie_is_android(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->isAndroidOS();
}

/***************************************************************
* Function tie_is_blackberry
* Detect a blackberry device
***************************************************************/

function tie_is_blackberry(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->isBlackBerry();
}

/***************************************************************
* Function tie_is_opera_mobile
* Detect both Opera Mini and hopefully Opera Mobile as well
***************************************************************/

function tie_is_opera_mobile(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->isOpera();
}


/***************************************************************
* Function tie_is_webos
* Detect a webOS device such as Pre and Pixi
***************************************************************/

function tie_is_webos(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->is( 'webOS' );
}

/***************************************************************
* Function tie_is_symbian
* Detect a symbian device, most likely a nokia smartphone
***************************************************************/

function tie_is_symbian(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->is( 'Symbian' );
}

/***************************************************************
* Function tie_is_windows_mobile
* Detect a windows smartphone
***************************************************************/

function tie_is_windows_mobile(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->is( 'WindowsMobileOS' ) || $mobble_detect->is( 'WindowsPhoneOS' );
}

/***************************************************************
* Function tie_is_motorola
* Detect a Motorola phone
***************************************************************/

function tie_is_motorola(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->is( 'Motorola' );
}

/***************************************************************
* Function tie_is_samsung
* Detect a Samsung phone
***************************************************************/

function tie_is_samsung(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->is( 'Samsung' );
}

/***************************************************************
* Function tie_is_samsung_tablet
* Detect the Galaxy tab
***************************************************************/

function tie_is_samsung_tablet(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->is( 'SamsungTablet' );
}

/***************************************************************
* Function tie_is_kindle
* Detect an Amazon kindle
***************************************************************/

function tie_is_kindle(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->is( 'Kindle' );
}

/***************************************************************
* Function tie_is_sony_ericsson
* Detect a Sony Ericsson
***************************************************************/

function tie_is_sony_ericsson(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->is( 'Sony' );
}


/***************************************************************
* Function tie_is_smartphone
* Grade of phone A = Smartphone - currently testing this
***************************************************************/

function tie_is_smartphone(){
	$mobble_detect = tie_int_Mobile_Detect();
	$grade = $mobble_detect->mobileGrade();
	if ( $grade == 'A' || $grade == 'B' ){
		return true;
	} else {
		return false;
	}
}

/***************************************************************
* Function tie_is_handheld
* Wrapper function for detecting ANY handheld device
***************************************************************/

function tie_is_handheld(){
	return tie_is_mobile() || tie_is_iphone() || tie_is_ipad() || tie_is_ipod() || tie_is_android() || tie_is_blackberry() || tie_is_opera_mobile() || tie_is_webos() || tie_is_symbian() || tie_is_windows_mobile() || tie_is_motorola() || tie_is_samsung() || tie_is_samsung_tablet() || tie_is_sony_ericsson();
}

/***************************************************************
* Function tie_is_mobile
* For detecting ANY mobile phone device
***************************************************************/

function tie_is_mobile(){
	$mobble_detect = tie_int_Mobile_Detect();
	if ( tie_is_tablet() ) return false;
	return $mobble_detect->isMobile();
}

/***************************************************************
* Function tie_is_ios
* For detecting ANY iOS/Apple device
***************************************************************/

function tie_is_ios(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->isiOS();
}

/***************************************************************
* Function tie_is_tablet
* For detecting tablet devices (needs work)
***************************************************************/

function tie_is_tablet(){
	$mobble_detect = tie_int_Mobile_Detect();
	return $mobble_detect->isTablet();
}
