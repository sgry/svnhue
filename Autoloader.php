<?php
/**ï
 * SVNHUE
 *
 * Control your Philips Hue lights with PHP
 *
 * PHP version 5.3
 *
 * LICENSE: meh
 *
 * @author     Sven Gryspeerdt <hansmopf@gmail.com>
 * @link       http://gryspeerdt.ch/hue/
 * @version    0.1
 *
 * Thanks to the nice people at http://www.everyhue.com/
 */

Class Autoloader
{

  public static function load($As_Class)
  {
    $Li_ClassnamePos = strrpos($As_Class, '\\') + 1;
    $Ls_Classname = substr($As_Class, $Li_ClassnamePos);
    require('class.'.$Ls_Classname.'.php');
  }

}

?>