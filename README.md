svnhue
======

PHP OOP Classes for Philips Hue


Example usage:
```php

/*******************************************************************************
* How to send commands to the bridge: 
*******************************************************************************/

// Create Hue Object
$Ls_Host = '192.168.1.23';
$Ls_AuthKey = '194253fd922301d88d816cd6731ae28b';
$Lo_Hue = new \SVN\HUE\Hue($Ls_Host, $Ls_AuthKey);

// Create HueState object
$Lo_HueState = new \SVN\HUE\HueState();

// Set Color
// Only 1 colormode is possible per HueState object
$Lo_HueState->setColor('name', 'white');
$Lo_HueState->setColor('hue', array(200, 127)); // Array: hue, sat
$Lo_HueState->setColor('ct', 400); // Int: ct
$Lo_HueState->setColor('xy', array(0.143, 0.451)); // Array: x, y

// Set On/Off state
$Lo_HueState->setOn(true);

// Set Brightness
$Lo_HueState->setBrightness(200);

// Set Alert
$Lo_HueState->setAlert('select'); // select or lselect

// Set Transitiontime
$Lo_HueState->setTransitiontime(20);

// Inject HueState into Hue object
$Lo_Hue->setState($Lo_HueState);

// Create HueSchedule object
$Ldt_DateTime = new \DateTime();
$Ldt_DateTime->add(new DateInterval('P1D')); // Add 1 Day
$Lo_HueSchedule = new \SVN\HUE\SHX\HueSchedule('Name', 'Description', $Ldt_DateTime);

// Inject HueSchedule into Hue object
$Lo_Hue->setSchedule($Lo_HueSchedule);

// Set target lights or groups
// Only 1 targetmode is possible per Hue object, either lights or groups
$Lo_Hue->setLights(array(1,3,4)); // Lights 1, 3 and 4
$Lo_Hue->setGroups(array(1,2)); // Groups 1 and 2

// Send it to the bridge!
$Lo_Hue->submitToBridge();



/*******************************************************************************
* How to read from the bridge: 
*******************************************************************************/

// Create Hue Object
$Ls_Host = '192.168.1.23';
$Ls_AuthKey = '194253fd922301d88d816cd6731ae28b';
$Lo_Hue = new \SVN\HUE\Hue($Ls_Host, $Ls_AuthKey);

// Set the light or the group you want to read out
// Only 1 targetmode is possible per Hue object, either lights or groups
// Only 1 light or group is possible at a time
$Lo_Hue->setLights(array(1)); // Light 1
$Lo_Hue->setGroups(array(1)); // Group 1

// Read
$Lo_Hue->readStateFromBridge();

// Get HueState object
$Lo_HueState = $Lo_Hue->getState();



/*******************************************************************************
* How to create or delete groups:
*******************************************************************************/

// Create Hue Object
$Ls_Host = '192.168.1.23';
$Ls_AuthKey = '194253fd922301d88d816cd6731ae28b';
$Lo_Hue = new \SVN\HUE\Hue($Ls_Host, $Ls_AuthKey);

// Set the lights you want to add to the new group
$Lo_Hue->setLights(array(1,2));

// Create the group
// You may have to reboot your bridge for the groups to become active
$Lo_Hue->createGroup('GroupName');


// Set the groups you want to delete
$Lo_Hue->setGroups(array(1,2));

// Delete the group
// You may have to reboot your bridge for the groups to become active
$Lo_Hue->deleteGroups();



/*******************************************************************************
* Misc:
*******************************************************************************/
/*
  All functions return bool true on success.
  You can check if something went wrong with the method getErrors()

*/

```