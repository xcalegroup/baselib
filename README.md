# Baselib
A library for handling the most mundane things like exception handling and logging aswell as the most often needed functions

# Requirements
You need to define the following contants in a file named baselibconstants.php placed in the root folder of your project. The Baselib will include the file like this
```
require_once(__DIR__ . "/baselibconstants.php");
```

**Baselib requirements for logging**
```
define("__BASELIB__NAME", "Application name");
define("__BASELIB__DEBUG", true);
define("__BASELIB__LOGGING", true);
define("__BASELIB__STACKTRACE", true);
define("__BASELIB__REMOTE__LOGGING", true);
define("__BASELIB__REMOTE__ENDPOINT", "Enpoint URL for your remote logging, if enabled");
define("__BASELIB__REMOTE__KEY", "Key for remote logging, if you choose to implement it");
```

**Baselib requirements for handling database**

This could be defined in another constants.php file, but is rquired to auto create tables if missing as well as adding data to the database
```
define("__BASELIB__DB_TYPE", "mysql");
define("__BASELIB__DB_HOST", "localhost");
define("__BASELIB__DB_USER", "USER");
define("__BASELIB__DB_PASS", "PASS");
define("__BASELIB__DB_NAME", "NAME");
```

If you allready have your database defined then use those defined constants in your baselibconstants file like this
```
require_once(__DIR__ . "[YOUR DEFINES FILE]");

define("__BASELIB__DB_TYPE", DB_TYPE);
define("__BASELIB__DB_HOST", DB_HOST);
define("__BASELIB__DB_USER", DB_USER);
define("__BASELIB__DB_PASS", DB_PASS);
define("__BASELIB__DB_NAME", DB_NAME);
```
