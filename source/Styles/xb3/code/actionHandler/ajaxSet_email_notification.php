<?php
/*
 If not stated otherwise in this file or this component's Licenses.txt file the
 following copyright and licenses apply:
 Copyright 2016 RDK Management
 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at
 http://www.apache.org/licenses/LICENSE-2.0
 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
*/
?>
<?php include('../includes/actionHandlerUtility.php') ?>
<?php 
session_start();
if (!isset($_SESSION["loginuser"]) || $_SESSION['loginuser'] != 'mso') {
	echo '<script type="text/javascript">alert("Please Login First!"); location.href="../index.php";</script>';
	exit(0);
}
$jsConfig = $_POST['configInfo'];
//$jsConfig = '{"recipient_mail":"string1", "firewall_breach":"true", "parental_breach":"true", "alerts_warnings":"false", "send_logs":"true", "smtp_address":"string2", "comcast_address":"string3", "comcast_username":"string4", "comcast_password":"string5"}';
$arConfig = json_decode($jsConfig, true);
//print_r($arConfig);
$validation = true;
if($validation) $validation = (preg_match("/^[ -~]+?@[ -~]+?\.[ -~]+?$/",$arConfig['recipient_mail'])==1);
if($validation) $validation = is_allowed_string($arConfig['recipient_mail']);
if($validation) $validation = (preg_match("/^[ -~]+?@[ -~]+?\.[ -~]+?$/",$arConfig['comcast_address'])==1);
if($validation) $validation = is_allowed_string($arConfig['comcast_address']);
if($validation) $validation = validIPAddr($arConfig['smtp_address']);
if($validation) $validation = printableCharacters($arConfig['comcast_username']);
if($validation) $validation = is_allowed_string($arConfig['comcast_username']);
if($validation) $validation = printableCharacters($arConfig['comcast_password']);
if($validation) $validation = is_allowed_string($arConfig['comcast_password']);
if($validation){
	setStr("Device.X_CISCO_COM_Security.EmailSendTo", $arConfig['recipient_mail'], false);
	setStr("Device.X_CISCO_COM_Security.EmailFirewallBreach", $arConfig['firewall_breach'], false);
	setStr("Device.X_CISCO_COM_Security.EmailParentalControlBreach", $arConfig['parental_breach'], false);
	setStr("Device.X_CISCO_COM_Security.EmailAlertsOrWarnings", $arConfig['alerts_warnings'], false);
	setStr("Device.X_CISCO_COM_Security.EmailSendLogs", $arConfig['send_logs'], false);
	setStr("Device.X_CISCO_COM_Security.EmailServer", $arConfig['smtp_address'], false);
	setStr("Device.X_CISCO_COM_Security.EmailFromAddress", $arConfig['comcast_address'], false);
	setStr("Device.X_CISCO_COM_Security.EmailUserName", $arConfig['comcast_username'], false);
	setStr("Device.X_CISCO_COM_Security.EmailPassword", $arConfig['comcast_password'], true);
}
echo $jsConfig;
?>