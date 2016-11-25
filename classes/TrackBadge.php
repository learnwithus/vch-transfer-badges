<?php

defined('MOODLE_INTERNAL') || die();

class TrackBadge {
    public static function TrackBadgeLearninghub($event) {
        global $DB;
        $dbhost='yourhost';
        $dbname='yourdbname';
        $dbuser='yourdbuser';
        $dbpass='yourpassword';
        
        $eventdata = $event->get_data();
        $user = $DB->get_record('user', array('id'=>$eventdata['other']['relateduserid']));
        $course = $DB->get_record('course', array('id'=>$eventdata['contextinstanceid']));

        $courseid = $course->id;
        $userid = $user->id;

        $badges = $DB->get_records('badge', array('courseid'=>$courseid));
        $issued = $DB->get_records_sql("SELECT * FROM {badge_issued} WHERE userid = ? AND badgeid IN (SELECT id FROM {badge} WHERE courseid= ?)", 
            array($userid, $courseid));

        $context = context_course::instance($courseid);

        $conn = mysqli_connect($dbhost, $dbuser, $dbpass)
            or die("Unable to connect to MySQL server");

        $selected = mysqli_select_db($conn, $dbname)
            or die("Could not select LearningHub db");

        foreach ($badges as $badge) {
            $imageurl = moodle_url::make_pluginfile_url($context->id, 'badges', 'badgeimage', $badge->id, '/', 'f1', false);
            if (!file_exists('../../learninghub/images/' . $context->id . '/badges/badgeimage/' . $badge->id)) {
                mkdir('../../learninghub/images/' . $context->id . '/badges/badgeimage/' . $badge->id, 0755, true);
            }
            
            copy($imageurl, '../../learninghub/images/' . $context->id . '/badges/badgeimage/' . $badge->id . '/f1.png');
            $imageurl = 'https://vchlearn.ca/learninghub/images/' . $context->id . '/badges/badgeimage/' . $badge->id . '/f1.png';

            $q = "
                INSERT IGNORE INTO tblBadges(BadgeID, Name, Description, CourseID, URL)
                VALUES ($badge->id, '$badge->name', '$badge->description', $course->idnumber, '$imageurl')
                ";
            mysqli_query($conn, $q);
        }

        foreach ($issued as $issue) {
            $q = "
                INSERT IGNORE INTO tblUserBadges(BadgeID, UserID, DateIssued)
                VALUES ($issue->badgeid, $issue->userid, $issue->dateissued)
                ";
            mysqli_query($conn, $q);
        }
    }
}
