<?php
$observers = array(
	array(
        'eventname' => '\core\event\role_assigned',
        'includefile' => '/blocks/customhomepage/systemuseractivity.php',
        'callback' => 'userenroll',
        'internal' => true),
	array(
        'eventname' => '\core\event\role_unassigned',
        'includefile' => '/blocks/customhomepage/systemuseractivity.php',
        'callback' => 'userunenroll',
        'internal' => true),
        array(
        'eventname' => '\core\event\enrol_instance_created',
        'includefile' => '/blocks/customhomepage/systemuseractivity.php',
        'callback' => 'coursecreateenrolledmanger',
        'internal' => true)

);

?>

