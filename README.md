# Baselib
A library for handling the most mundane things like exception handling and logging aswell as the most often needed functions

# requirements
You need to define the following contants in your project

**Baselib requirements for logging**
```
define("__APPLICATTION__NAME", "Application name");
define("__APPLICATTION__DEBUG", true);
define("__APPLICATTION__LOGGING", true);
define("__APPLICATTION__STACKTRACE", true);
define("__APPLICATTION__REMOTE__LOGGING", true);
define("__APPLICATTION__REMOTE__ENDPOINT", "Enpoint URL for your remote logging, if enabled");
define("__APPLICATTION__REMOTE__KEY", "Key for remote logging, if you choose to implement it");
```

**baselib requirements for handling database**

This could be defined in another constants.php file, but is rquired to auto create tables if missing as well as adding data to the database
```
define("DB_TYPE", "mysql");
define("DB_HOST", "localhost");
define("DB_USER", "USER");
define("DB_PASS", "PASS");
define("DB_NAME", "NAME");
```
