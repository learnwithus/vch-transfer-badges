<?php

$observers = array(
    array(
        'eventname'   => '\core\event\course_completed',
        'callback'    => 'TrackBadge::TrackBadgeLearninghub',
        'includefile' => '/local/badgetracking/classes/TrackBadge.php'
    ),
);

?>
