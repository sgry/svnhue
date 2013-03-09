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



// ToDo:
// recurring schedule?
// multiple schedules logic
// effects?



namespace SVN\HUE;

class Hue extends BaseHue
{

  private $s_HueHost;
  private $s_AuthKey;
  private $s_BaseUrl;

  private $a_Lights;
  private $a_Groups;
  private $o_State;
  private $o_Schedule;

  const SVNHUE_ERROR_WRONG_PARAMETER = 'Wrong parameter submitted';
  const SVNHUE_ERROR_NO_STATE_SET = 'No state set';
  const SVNHUE_ERROR_NO_TARGET_SET = 'No target set';
  const SVNHUE_ERROR_TOO_FEW_TARGETS = 'Too few targets set';
  const SVNHUE_ERROR_TOO_MANY_TARGETS = 'Too many targets set';
  const SVNHUE_ERROR_INVALID_TARGET = 'Lamp or group does not exist';
  const SVNHUE_ERROR_EMPTY_SCHEDULE = 'Submitted schedule object is empty';
  const SVNHUE_ERROR_EMPTY_STATE = 'Submitted state object is empty';



  /******************************************************************************/
  /*   function __construct()                                                   */
  /******************************************************************************/
  public function __construct($As_HueHost, $As_AuthKey, \SVN\HUE\HueState $Ao_State = null, \SVN\HUE\HueSchedule $Ao_Schedule = null)
  {
    $this->s_HueHost = $As_HueHost;
    $this->s_AuthKey = $As_AuthKey;
    $this->s_BaseUrl = $As_HueHost.'/api/'.$As_AuthKey;

    if ($Ao_State != null)
    {
      $this->o_State = $Ao_State;
    } /* if */
    if ($Ao_Schedule != null)
    {
      $this->o_Schedule = $Ao_Schedule;
    } /* if */
  } /* function __construct() */



  /******************************************************************************/
  /*   function setLights()                                                     */
  /******************************************************************************/
  public function setLights(array $Aa_Lights)
  {
    $Lb_Return = true;
    $this->a_Errors = null;
    unset($this->a_Groups);

    if (count($Aa_Lights) > 0)
    {
      foreach($Aa_Lights as $Li_Light)
      {
        if (!is_numeric($Li_Light) || is_float($Li_Light))
        {
          $this->a_Errors[] = self::SVNHUE_ERROR_WRONG_PARAMETER;
          $Lb_Return = false;
          break;
        } /* if */
      } /* foreach */
    } /* if */
    else
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_WRONG_PARAMETER;
      $Lb_Return = false;
    } /* else */
    if ($Lb_Return === true)
    {
      $this->a_Lights = $Aa_Lights;
    } /* if */

    return($Lb_Return);

  } /* function setLights() */



  /******************************************************************************/
  /*   function getLights()                                                     */
  /******************************************************************************/
  public function getLights()
  {
    if (isset($this->a_Lights))
    {
      return($this->a_Lights);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getLights() */



  /******************************************************************************/
  /*   function setGroups()                                                     */
  /******************************************************************************/
  public function setGroups(array $Aa_Groups)
  {
    $Lb_Return = true;
    $this->a_Errors = null;
    unset($this->a_Lights);

    if (count($Aa_Groups) > 0)
    {
      foreach($Aa_Groups as $Li_Group)
      {
        if (!is_numeric($Li_Group) || is_float($Li_Group))
        {
          $this->a_Errors[] = self::SVNHUE_ERROR_WRONG_PARAMETER;
          $Lb_Return = false;
          break;
        } /* if */
      } /* foreach */
    } /* if */
    else
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_WRONG_PARAMETER;
      $Lb_Return = false;
    } /* else */
    if ($Lb_Return === true)
    {
      $this->a_Groups = $Aa_Groups;
    } /* if */

    return($Lb_Return);

  } /* function setGroups() */



  /******************************************************************************/
  /*   function getGroups()                                                     */
  /******************************************************************************/
  public function getGroups()
  {
    if (isset($this->a_Groups))
    {
      return($this->a_Groups);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getGroups() */



  /******************************************************************************/
  /*   function setSchedule()                                                   */
  /******************************************************************************/
  public function setSchedule(\SVN\HUE\HueSchedule $Ao_HueSchedule)
  {
    $Lb_Return = true;
    $this->a_Errors = null;

    $La_Schedule = $Ao_HueSchedule->getSchedule();
    if (isset($La_Schedule))
    {
      $this->o_Schedule = $Ao_HueSchedule;
    } /* if */
    else
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_EMPTY_SCHEDULE;
      $Lb_Return = false;
    } /* else */

    return($Lb_Return);
  } /* function setSchedule() */



  /******************************************************************************/
  /*   function getSchedule()                                                   */
  /******************************************************************************/
  public function getSchedule()
  {
    if (isset($this->o_Schedule))
    {
      return($this->o_Schedule);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getSchedule() */



  /******************************************************************************/
  /*   function setState()                                                      */
  /******************************************************************************/
  public function setState(\SVN\HUE\HueState $Ao_HueState)
  {
    $Lb_Return = true;
    $this->a_Errors = null;

    $La_State = $Ao_HueState->getState();
    if (isset($La_State))
    {
      $this->o_State = $Ao_HueState;
    } /* if */
    else
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_EMPTY_STATE;
      $Lb_Return = false;
    } /* else */

    return($Lb_Return);
  } /* function setState() */



  /******************************************************************************/
  /*   function getState()                                                      */
  /******************************************************************************/
  public function getState()
  {
    if (isset($this->o_State))
    {
      return($this->o_State);
    } /* if */
    else
    {
      return(null);
    } /* else */
  } /* function getState() */



  /******************************************************************************/
  /*   function submitToBridge()                                                */
  /******************************************************************************/
  public function submitToBridge()
  {
    $Lb_Return = true;
    $this->a_Errors = null;
    $Ls_Method = 'PUT';

    if (isset($this->a_Lights))
    {
      $Ls_Url1 = '/lights/';
      $Ls_Url2 = '/state';
      $La_Targets = $this->a_Lights;
    } /* if */
    elseif (isset($this->a_Groups))
    {
      $Ls_Url1 = '/groups/';
      $Ls_Url2 = '/action';
      $La_Targets = $this->a_Groups;
    } /* elseif */
    else
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_NO_TARGET_SET;
      $Lb_Return = false;
      $Ls_Url1 = null;
      $Ls_Url2 = null;
      $La_Targets = null;
    } /* else */

    if (!isset($this->o_State) || !$this->o_State->getState())
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_NO_STATE_SET;
      $Lb_Return = false;
    } /* if */

    if ($Lb_Return === true)
    {
      foreach($La_Targets as $Li_Target)
      {
        if (isset($this->o_Schedule) && $this->o_Schedule)
        {
          $Ls_Url = $this->s_BaseUrl.'/schedules';
          $La_Schedule = $this->o_Schedule->getSchedule();
          $La_Schedule['command']['address'] = '/api/'.$this->s_AuthKey.$Ls_Url1.$Li_Target.$Ls_Url2;
          $La_Schedule['command']['body'] = $this->o_State->getState();
          //PHP 5.4: $La_Action = json_encode($La_Schedule, JSON_UNESCAPED_SLASHES);
          $La_Action = str_replace('\\/', '/', json_encode($La_Schedule));
          $Ls_Method = 'POST';
        } /* if */
        else
        {
          $Ls_Url = $this->s_BaseUrl.$Ls_Url1.$Li_Target.$Ls_Url2;
          $La_Action = json_encode($this->o_State->getState());
        } /* else */

        $Lo_Curl = curl_init($Ls_Url);
        curl_setopt($Lo_Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Lo_Curl, CURLOPT_POSTFIELDS, $La_Action);
        curl_setopt($Lo_Curl, CURLOPT_CUSTOMREQUEST, $Ls_Method);
        $Ls_Response = curl_exec($Lo_Curl);

        $La_Response = json_decode($Ls_Response);
        foreach($La_Response as $Lo_Response)
        {
          if (isset($Lo_Response->error))
          {
            $this->a_Errors[] = $Lo_Response->error;
            $Lb_Return = false;
          } /* if */
        } /* foreach */
      } /* foreach */
    } /* if */

    return($Lb_Return);

  } /* function submitToBridge() */



  /******************************************************************************/
  /*   function readStateFromBridge()                                           */
  /******************************************************************************/
  public function readStateFromBridge()
  {
    $Lb_Return = true;
    $this->a_Errors = null;
    $Ls_Method = 'GET';

    if (isset($this->a_Lights))
    {
      if (count($this->a_Lights) > 1)
      {
        $this->a_Errors[] = self::SVNHUE_ERROR_TOO_MANY_TARGETS;
        $Lb_Return = false;
      }
      $Ls_Mode = 'lights';
      $Ls_Mode2 = 'state';
      $Li_Target = $this->a_Lights[0];
    } /* if */
    elseif (isset($this->a_Groups))
    {
      if (count($this->a_Groups) > 1)
      {
        $this->a_Errors[] = self::SVNHUE_ERROR_TOO_MANY_TARGETS;
        $Lb_Return = false;
      } /* if */
      $Ls_Mode = 'groups';
      $Ls_Mode2 = 'action';
      $Li_Target = $this->a_Groups[0];
    } /* elseif */
    else
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_NO_TARGET_SET;
      $Lb_Return = false;
      $Ls_Mode = null;
      $Ls_Mode2 = null;
      $Li_Target = null;
    } /* else */

    if ($Lb_Return === true)
    {
      $Ls_Url = $this->s_BaseUrl;
      $Lo_Curl = curl_init($Ls_Url);
      curl_setopt($Lo_Curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($Lo_Curl, CURLOPT_CUSTOMREQUEST, $Ls_Method);
      $Ls_Response = curl_exec($Lo_Curl);

      $Lo_Response = json_decode($Ls_Response);

      if (isset($Lo_Response->$Ls_Mode->$Li_Target))
      {
        $Lo_State = new HueState();
        $Lo_State->setOn($Lo_Response->$Ls_Mode->$Li_Target->$Ls_Mode2->on);
        $Lo_State->setBrightness($Lo_Response->$Ls_Mode->$Li_Target->$Ls_Mode2->bri);
        switch($Lo_Response->$Ls_Mode->$Li_Target->$Ls_Mode2->colormode)
        {
          case 'hue':
            $Lo_State->setColor('hue', array($Lo_Response->$Ls_Mode->$Li_Target->$Ls_Mode2->hue, $Lo_Response->$Ls_Mode->$Li_Target->$Ls_Mode2->sat));
            break;

          case 'ct':
            $Lo_State->setColor('ct', $Lo_Response->$Ls_Mode->$Li_Target->$Ls_Mode2->ct);
            break;

          case 'xy':
            $Lo_State->setColor('xy', $Lo_Response->$Ls_Mode->$Li_Target->$Ls_Mode2->xy);
            break;
        } /* switch */

        if (isset($Lo_Response->$Ls_Mode->$Li_Target->$Ls_Mode2->alert))
        {
          $Lo_State->setAlert($Lo_Response->$Ls_Mode->$Li_Target->$Ls_Mode2->alert);
        } /* if */

        $this->setState($Lo_State);
      } /* if */

      else
      {
        $this->a_Errors[] = self::SVNHUE_ERROR_INVALID_TARGET;
        $Lb_Return = false;
      } /* else */
    } /* if */

    return($Lb_Return);

  } /* function readStateFromBridge() */



  /******************************************************************************/
  /*   function createGroup()                                                   */
  /******************************************************************************/
  public function createGroup($As_Name)
  {
    $Lb_Return = true;
    $this->a_Errors = null;
    $Ls_Method = 'POST';

    if (isset($this->a_Lights))
    {
      if (count($this->a_Lights) <= 1)
      {
        $this->a_Errors[] = self::SVNHUE_ERROR_TOO_FEW_TARGETS;
        $Lb_Return = false;
      } /* if */
    } /* if */
    else
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_NO_TARGET_SET;
      $Lb_Return = false;
    } /* else */

    if (!$As_Name)
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_WRONG_PARAMETER;
      $Lb_Return = false;
    } /* if */

    if ($Lb_Return === true)
    {
      $Ls_Url = $this->s_BaseUrl.'/groups';
      $La_Lights = array();

      foreach($this->a_Lights as $Li_Light)
      {
        $La_Lights[] = (string) $Li_Light;
      } /* foreach */

      $La_Action = array('name' => $As_Name, 'lights' => $La_Lights);
      $La_Action = json_encode($La_Action);

      $Lo_Curl = curl_init($Ls_Url);
      curl_setopt($Lo_Curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($Lo_Curl, CURLOPT_POSTFIELDS, $La_Action);
      curl_setopt($Lo_Curl, CURLOPT_CUSTOMREQUEST, $Ls_Method);
      $Ls_Response = curl_exec($Lo_Curl);

      $La_Response = json_decode($Ls_Response);
      foreach($La_Response as $Lo_Response)
      {
        if (isset($Lo_Response->error))
        {
          $this->a_Errors[] = $Lo_Response->error;
          $Lb_Return = false;
        } /* if */
      } /* foreach */
    } /* if */

    return($Lb_Return);

  } /* function createGroup() */



  /******************************************************************************/
  /*   function deleteGroups()                                                  */
  /******************************************************************************/
  public function deleteGroups()
  {
    $Lb_Return = true;
    $this->a_Errors = null;
    $Ls_Method = 'DELETE';

    if (!isset($this->a_Groups))
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_NO_TARGET_SET;
      $Lb_Return = false;
    } /* if */

    if ($Lb_Return === true)
    {
      foreach($this->a_Groups as $Li_Group)
      {
        $Ls_Url = $this->s_BaseUrl.'/groups/'.$Li_Group;
        $Lo_Curl = curl_init($Ls_Url);
        curl_setopt($Lo_Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Lo_Curl, CURLOPT_CUSTOMREQUEST, $Ls_Method);
        $Ls_Response = curl_exec($Lo_Curl);

        $La_Response = json_decode($Ls_Response);
        foreach($La_Response as $Lo_Response)
        {
          if (isset($Lo_Response->error))
          {
            $this->a_Errors[] = $Lo_Response->error;
            $Lb_Return = false;
          } /* if */
        } /* foreach */
      } /* foreach */
    } /* if */

    return($Lb_Return);

  } /* function deleteGroups() */



  /******************************************************************************/
  /*   function deleteSchedule()                                                */
  /******************************************************************************/
  public function deleteSchedule($Ai_Schedule)
  {
    $Lb_Return = true;
    $this->a_Errors = null;
    $Ls_Method = 'DELETE';

    if (!is_numeric($Ai_Schedule) || is_float($Ai_Schedule))
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_WRONG_PARAMETER;
      $Lb_Return = false;
    } /* if */

    if ($Lb_Return === true)
    {
      $Ls_Url = $this->s_BaseUrl.'/schedules/'.$Ai_Schedule;
      $Lo_Curl = curl_init($Ls_Url);
      curl_setopt($Lo_Curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($Lo_Curl, CURLOPT_CUSTOMREQUEST, $Ls_Method);
      $Ls_Response = curl_exec($Lo_Curl);

      $La_Response = json_decode($Ls_Response);
      foreach($La_Response as $Lo_Response)
      {
        if (isset($Lo_Response->error))
        {
          $this->a_Errors[] = $Lo_Response->error;
          $Lb_Return = false;
        } /* if */
      } /* foreach */
    } /* if */

    return($Lb_Return);

  } /* function deleteSchedule() */



  /******************************************************************************/
  /*   function registerApp()                                                   */
  /******************************************************************************/
  public function registerApp($As_Name)
  {
    $Lb_Return = true;
    $this->a_Errors = null;
    $Ls_Method = 'POST';

    if (!$As_Name)
    {
      $this->a_Errors[] = self::SVNHUE_ERROR_WRONG_PARAMETER;
      $Lb_Return = false;
    } /* if */

    if ($Lb_Return === true)
    {
      $Ls_Url = $this->s_HueHost.'/api';

      $La_Action = array('username' => $this->s_AuthKey, 'devicetype' => $As_Name);
      $La_Action = json_encode($La_Action);

      $Lo_Curl = curl_init($Ls_Url);
      curl_setopt($Lo_Curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($Lo_Curl, CURLOPT_POSTFIELDS, $La_Action);
      curl_setopt($Lo_Curl, CURLOPT_CUSTOMREQUEST, $Ls_Method);
      $Ls_Response = curl_exec($Lo_Curl);

      $La_Response = json_decode($Ls_Response);
      foreach($La_Response as $Lo_Response)
      {
        if (isset($Lo_Response->error))
        {
          $this->a_Errors[] = $Lo_Response->error;
          $Lb_Return = false;
        } /* if */
      } /* foreach */
    } /* if */

    return($Lb_Return);

  } /* function registerApp() */



} /* class \SVN\HUE\Hue */

?>