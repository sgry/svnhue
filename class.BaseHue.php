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



namespace SVN\HUE;

abstract class BaseHue
{

  protected $a_Errors;



  /******************************************************************************/
  /*   function getErrors()                                                     */
  /******************************************************************************/
  public final function getErrors()
  {
    if (isset($this->a_Errors))
    {
      return($this->a_Errors);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getErrors() */



} /* class \SVN\HUE\BaseHue */

?>