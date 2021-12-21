<?php
namespace XcaleGroup;
/**
 * Databaseclass written specifically for the baselib, hense the name.
 * This is only used to handle internal requests and should not be used in your project if you allready have a similar class
 */
class BaselibDatabase extends PDO {

    public function __construct() {

        try {
            parent::__construct(__BASELIB__DB_TYPE . ':host=' . __BASELIB__DB_HOST . ';dbname=' . __BASELIB__DB_NAME . ';charset=utf8', __BASELIB__DB_USER, __BASELIB__DB_PASS);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $custom_errormsg = 'Error connecting to database - <u>check your database connection properties in the constants.php file!</u>';
            echo "<br>\n <div style ='color:red'><strong>" . $custom_errormsg . "</strong></div><br>\n<br>\n ". $e->getMessage();
            echo "<br>\nPHP Version : ".phpversion()."<br>\n";
        }
    }

}
