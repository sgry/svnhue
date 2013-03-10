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



namespace sgry\Hue;

class HueSchedule extends BaseHue
{

  private $a_Schedule;
  private $o_DateTime;

  const SVNHUE_ERROR_DATE_NOT_IN_FUTURE = 'Date must not be in the past';



  /******************************************************************************/
  /*   function __construct()                                                   */
  /******************************************************************************/
  public function __construct($As_Name, $As_Description, \DateTime $Adt_DateTime)
  {
    $this->setSchedule($As_Name, $As_Description, $Adt_DateTime);
  } /* function __construct() */



  /******************************************************************************/
  /*   function setSchedule()                                                   */
  /******************************************************************************/
  public function setSchedule($As_Name, $As_Description, \DateTime $Adt_DateTime)
  {
    $Lb_Return = true;
    $this->a_Errors = null;

    $Ldt_Now = new \DateTime();
    $Ldt_Diff = $Ldt_Now->diff($Adt_DateTime);

    if ($Ldt_Diff->invert == 1)
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_DATE_NOT_IN_FUTURE;
    } /* if */

    if ($Lb_Return === true)
    {
      $this->o_DateTime = $Adt_DateTime;

      $Adt_DateTime->setTimezone(new \DateTimeZone('UTC'));
      $Ls_DateTime = $Adt_DateTime->format('Y-m-d\TH:i:s');

      $this->a_Schedule['name'] = $As_Name;
      $this->a_Schedule['description'] = $As_Description;
      $this->a_Schedule['time'] = $Ls_DateTime;
      $this->a_Schedule['command'] = array();
      $this->a_Schedule['command']['method'] = 'PUT';
      $this->a_Schedule['command']['address'] = '';
      $this->a_Schedule['command']['body'] = array();
    } /* if */

    return($Lb_Return);
  } /* function setSchedule() */



  /******************************************************************************/
  /*   function getName()                                                       */
  /******************************************************************************/
  public function getName()
  {
    if (isset($this->a_Schedule['name']))
    {
      return($this->a_Schedule['name']);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getName() */



  /******************************************************************************/
  /*   function getDescription()                                                */
  /******************************************************************************/
  public function getDescription()
  {
    if (isset($this->a_Schedule['description']))
    {
      return($this->a_Schedule['description']);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getDescription() */



  /******************************************************************************/
  /*   function getDateTime()                                                   */
  /******************************************************************************/
  public function getDateTime()
  {
    if (isset($this->o_DateTime))
    {
      return($this->o_DateTime);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getDateTime() */



  /******************************************************************************/
  /*   function getSchedule()                                                   */
  /******************************************************************************/
  public function getSchedule()
  {
    if (isset($this->a_Schedule))
    {
      return($this->a_Schedule);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getSchedule() */



} /* class \sgry\Hue\HueSchedule */
