
<?php 
defined('MOODLE_INTERNAL') || die();

/**
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_local_badgetracking_upgrade($oldversion) {

    // Fetch documents from documents directory and put them into the new documents filearea.
    if ($oldversion < 2016020310) {
        // Remember upgrade savepoint
        upgrade_plugin_savepoint(true, 2016020310, 'local', 'badgetracking');
    }
    return true;
}
