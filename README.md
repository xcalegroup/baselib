# Baselib
A library for handling the most mundane things like exception handling and logging aswell as the most often needed functions

```
composer require xcalegroup/baselib
```

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
define("__BASELIB__TRACE", true);
define("__BASELIB__REMOTE__DATA", true);
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
define("__BASELIB__CREATE_DB", false); // if true it will create the tables needed, but only if the table does not exists.
```

If you allready have your database defined then use those defined constants in your baselibconstants file like this
```
require_once(__DIR__ . "[YOUR DEFINES FILE]");

define("__BASELIB__DB_TYPE", DB_TYPE);
define("__BASELIB__DB_HOST", DB_HOST);
define("__BASELIB__DB_USER", DB_USER);
define("__BASELIB__DB_PASS", DB_PASS);
define("__BASELIB__DB_NAME", DB_NAME);
define("__BASELIB__CREATE_DB", false);
```

# Usage
```
class YouClass extends BaselibClass
{
    public function Method1()
    {
        $this->trace->add(__FUNCTION__);
        ...
    }

    public function Method2()
    {
        $this->trace->add(__FUNCTION__);
        try{
            ...
        }
        catch(Exception $e){
            $this->log(Severity::ERROR,$e);
        }
        finally{

        }
    }
}
```

The baselibClass provides numerous ways of logging

**collections**

Collections are stored upon object destruction. Either by using unset or by letting PHP handle carbage collection.
- trace
- logs

**methods**

Methods log instantly on each call
- log
- message

**Remote Endpoint**

The remote endpoint will be called using POST. Handle variables like the below, where key is used as a security measure. Use it as part of an API validation process. Using remote logging might be usefull for small projects or if you have multiple CRON jobs and wish to have logging easy at hand.

```
$_POST['key']!='') {
    $key=$_POST['key'];
    $app=$_POST['app'];
    $severity=$_POST['severity'];
    $errorcode=$_POST['errorcode'];
    $error=$_POST['error'];
    $stack_trace=$_POST['stack_trace'];
    $user=$_POST['user'];
}
```

**FileReader class - For reading Huge file**

This class has been tested on files containing more than 2.5 million lines and sizes above 72GB data.
Its does not put pressure on your server and can run multiple instances on different files

```
$filereader = new FileReader('file.extension', true);
$filereader->read('handleLineContent');

function handleLineContent($content){
    //Handle the content read. This is called each time a line is read
}
