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

class HueState extends BaseHue
{

  private $a_State;
  private $i_Colormode;
  private $a_Colors = array(
    'white'       => array(0.3369,0.3603),
    'red'         => array(0.6466,0.3302),
    'green'       => array(0.4046,0.5091),
    'blue'        => array(0.1844,0.0744),
    'yellow'      => array(0.4424,0.4934),
    'orange'      => array(0.5342,0.424),
    'lightpurple' => array(0.2647,0.1293),
    'pink'        => array(0.359,0.1792)
  );

  const SVNHUE_COLORMODE_NAME = 'name';
  const SVNHUE_COLORMODE_HUE = 'hue';
  const SVNHUE_COLORMODE_CT = 'ct';
  const SVNHUE_COLORMODE_XY = 'xy';
  const SVNHUE_ERROR_INVALID_COLOR = 'Color does not exist';



  /******************************************************************************/
  /*   function __construct()                                                   */
  /******************************************************************************/
  public function __construct()
  {
  } /* function __construct() */



  /******************************************************************************/
  /*   function setColor()                                                      */
  /******************************************************************************/
  public function setColor($Ai_Colormode, $Av_Color)
  {
    $Lb_Return = true;
    $this->a_Errors = null;

    switch($Ai_Colormode)
    {
      case self::SVNHUE_COLORMODE_NAME:
        if (array_key_exists($Av_Color, $this->a_Colors))
        {
          $this->a_State['xy'] = $this->a_Colors[$Av_Color];
          $this->i_Colormode = self::SVNHUE_COLORMODE_XY;
          unset($this->a_State['hue']);
          unset($this->a_State['sat']);
          unset($this->a_State['ct']);
        } /* if */
        else
        {
          $this->a_Errors[] = self::SVNHUE_ERROR_INVALID_COLOR;
          $Lb_Return = false;
        } /* else */
        break;

      case self::SVNHUE_COLORMODE_HUE:
        if ((is_numeric($Av_Color[0]) && !is_float($Av_Color[0])) && (is_numeric($Av_Color[1]) && !is_float($Av_Color[1])))
        {
          if ($Av_Color[0] > 65535)
          {
            $Av_Color[0] = 65535;
          } /* if */
          elseif ($Av_Color[0] < 0)
          {
            $Av_Color[0] = 0;
          } /* elseif */
          if ($Av_Color[1] > 254)
          {
            $Av_Color[1] = 254;
          } /* if */
          elseif ($Av_Color[1] < 0)
          {
            $Av_Color[1] = 0;
          } /* elseif */

          $this->i_Colormode = self::SVNHUE_COLORMODE_HUE;
          $this->a_State['hue'] = $Av_Color[0];
          $this->a_State['sat'] = $Av_Color[1];
          unset($this->a_State['ct']);
          unset($this->a_State['xy']);
        } /* if */
        else
        {
          $this->a_Errors[] = Hue::SVNHUE_ERROR_WRONG_PARAMETER;
          $Lb_Return = false;
        } /* else */
        break;

      case self::SVNHUE_COLORMODE_CT:
        if (is_numeric($Av_Color) && !is_float($Av_Color))
        {
          if ($Av_Color > 500)
          {
            $Av_Color = 500;
          } /* if */
          elseif ($Av_Color < 154)
          {
            $Av_Color = 154;
          } /* elseif */

          $this->i_Colormode = self::SVNHUE_COLORMODE_CT;
          $this->a_State['ct'] = $Av_Color;
          unset($this->a_State['hue']);
          unset($this->a_State['sat']);
          unset($this->a_State['xy']);
        } /* if */
        else
        {
          $this->a_Errors[] = Hue::SVNHUE_ERROR_WRONG_PARAMETER;
          $Lb_Return = false;
        } /* else */
        break;

      case self::SVNHUE_COLORMODE_XY:
        if (is_numeric($Av_Color[0]) && is_numeric($Av_Color[1]))
        {
          if ($Av_Color[0] > 1)
          {
            $Av_Color[0] = 1;
          } /* if */
          elseif ($Av_Color[0] < 0)
          {
            $Av_Color[0] = 0;
          } /* elseif */
          if ($Av_Color[1] > 1)
          {
            $Av_Color[1] = 1;
          } /* if */
          elseif ($Av_Color[1] < 0)
          {
            $Av_Color[1] = 0;
          } /* elseif */

          $this->i_Colormode = self::SVNHUE_COLORMODE_XY;
          $this->a_State['xy'] = array($Av_Color[0], $Av_Color[1]);
          unset($this->a_State['hue']);
          unset($this->a_State['sat']);
          unset($this->a_State['ct']);
        } /* if */
        else
        {
          $this->a_Errors[] = Hue::SVNHUE_ERROR_WRONG_PARAMETER;
          $Lb_Return = false;
        } /* else */
        break;

      default:
        $this->a_Errors[] = Hue::SVNHUE_ERROR_WRONG_PARAMETER;
        $Lb_Return = false;
        break;
    } /* switch */

    return($Lb_Return);

  } /* function setColor() */



  /******************************************************************************/
  /*   function getColor()                                                      */
  /******************************************************************************/
  public function getColor()
  {
    $La_Color = null;

    switch($this->i_Colormode)
    {
      case self::SVNHUE_COLORMODE_HUE:
        $La_Color['hue'] = $this->a_State['hue'];
        $La_Color['sat'] = $this->a_State['sat'];
        break;

      case self::SVNHUE_COLORMODE_CT:
        $La_Color['ct'] = $this->a_State['ct'];
        break;

      case self::SVNHUE_COLORMODE_XY:
        $La_Color['xy'] = $this->a_State['xy'];
        break;
    } /* switch */

    return($La_Color);

  } /* function getColor() */



  /******************************************************************************/
  /*   function setOn()                                                         */
  /******************************************************************************/
  public function setOn($Ab_State)
  {
    $Lb_Return = true;
    $this->a_Errors = null;

    if (is_bool($Ab_State))
    {
      $this->a_State['on'] = $Ab_State;
    } /* if */
    else
    {
      $this->a_Errors[] = Hue::SVNHUE_ERROR_WRONG_PARAMETER;
      $Lb_Return = false;
    } /* else */

    return($Lb_Return);
  } /* function setOn() */



  /******************************************************************************/
  /*   function getOn()                                                         */
  /******************************************************************************/
  public function getOn()
  {
    if (isset($this->a_State['on']))
    {
      return($this->a_State['on']);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getOn() */



  /******************************************************************************/
  /*   function setBrightness()                                                 */
  /******************************************************************************/
  public function setBrightness($Ai_Brightness)
  {
    $Lb_Return = true;
    $this->a_Errors = null;

    if (is_numeric($Ai_Brightness) && !is_float($Ai_Brightness))
    {
      if ($Ai_Brightness > 254)
      {
        $Ai_Brightness = 254;
      } /* if */
      if ($Ai_Brightness < 1)
      {
        $Ai_Brightness = 1;
      } /* if */
      $this->a_State['bri'] = $Ai_Brightness;
    } /* if */
    else
    {
      $this->a_Errors[] = Hue::SVNHUE_ERROR_WRONG_PARAMETER;
      $Lb_Return = false;
    } /* else */

    return($Lb_Return);
  } /* function setBrightness() */



  /******************************************************************************/
  /*   function getBrightness()                                                 */
  /******************************************************************************/
  public function getBrightness()
  {
    if (isset($this->a_State['bri']))
    {
      return($this->a_State['bri']);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getBrightness() */



  /******************************************************************************/
  /*   function setTransitiontime()                                             */
  /******************************************************************************/
  public function setTransitiontime($Ai_Transitiontime)
  {
    $Lb_Return = true;
    $this->a_Errors = null;

    if (is_numeric($Ai_Transitiontime) && !is_float($Ai_Transitiontime))
    {
      if ($Ai_Transitiontime < 0)
      {
        $Ai_Transitiontime = 0;
      } /* if */
      $this->a_State['transitiontime'] = $Ai_Transitiontime;
    } /* if */
    else
    {
      $this->a_Errors[] = Hue::SVNHUE_ERROR_WRONG_PARAMETER;
      $Lb_Return = false;
    } /* else */

    return($Lb_Return);
  } /* function setTransitiontime() */



  /******************************************************************************/
  /*   function getTransitiontime()                                             */
  /******************************************************************************/
  public function getTransitiontime()
  {
    if (isset($this->a_State['bri']))
    {
      return($this->a_State['bri']);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getTransitiontime() */



  /******************************************************************************/
  /*   function setAlert()                                                      */
  /******************************************************************************/
  public function setAlert($As_Alert)
  {
    $Lb_Return = true;
    $this->a_Errors = null;

    if ($As_Alert == 'select' || $As_Alert == 'lselect')
    {
      $this->a_State['alert'] = $As_Alert;
    } /* if */
    else
    {
      $this->a_Errors[] = Hue::SVNHUE_ERROR_WRONG_PARAMETER;
      $Lb_Return = false;
    } /* else */

    return($Lb_Return);
  } /* function setAlert() */



  /******************************************************************************/
  /*   function getAlert()                                                      */
  /******************************************************************************/
  public function getAlert()
  {
    if (isset($this->a_State['alert']))
    {
      return($this->a_State['alert']);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getAlert() */



  /******************************************************************************/
  /*   function getState()                                                      */
  /******************************************************************************/
  public function getState()
  {
    if (isset($this->a_State))
    {
      return($this->a_State);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getState() */



} /* class \SVN\HUE\HueState */

?>