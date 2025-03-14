<?php  

namespace Controller;

require_once __DIR__ . '/../../vendor/autoload.php'; // Javított elérési útvonal

use Service\Testservice;
use Hurycan\HTTP\Res;

class TestController {
    public static $res; // Privát statikus változó, hogy ne lehessen kívülről módosítani

    public static function init() {
        self::$res = new Res(); // `self::` helyett `self::$`
    }

    public static function test() {
        if (!self::$res) { // Ellenőrizzük, hogy az init() lefutott-e
            self::init();
        }

        $serviceFunction = Testservice::testServiceFunction();
        self::$res->setBody($serviceFunction);
        self::$res->setStatus_code(200);
        self::$res->send(); 
    }
}

?>
