<?php 
namespace Controller;

header("Access-Control-Allow-Origin: *");  // Minden domain számára engedélyezve
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");  // Mely HTTP metódusok engedélyezettek
header("Access-Control-Allow-Headers: Content-Type, Authorization");  // Mely fejlécadatok engedélyezettek
header("Access-Control-Allow-Credentials: true");  // Ha szükséges, akkor engedélyezhetjük a hitelesítést is


require_once __DIR__ . '\..\..\vendor\autoload.php';

use Controller\Config\Req;
use Controller\Config\Res;

// Ellenőrizzük, hogy a Reg osztály megfelelően importálva van
use Controller\Reg;  // Ha Reg osztály létezik, ezt importálni kell

use \PDO;

$res = new Res();
switch (Req::getReqFun()) {
    case 'check-db':
        check_db();
        break;
    case 'get-db-structure':
            getDbStructure();
        
    default:
        call_Controller_method();
        break;
}

function getDbStructure() {
    $host = Req::getReqBody()["db_host"];
    $user = Req::getReqBody()["db_user"];
    $pass = Req::getReqBody()["db_pass"];
    $dbname = Req::getReqBody()["db_name"];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "
            SELECT 
                TABLE_NAME, COLUMN_NAME, COLUMN_TYPE, COLUMN_KEY, EXTRA, IS_NULLABLE, COLUMN_DEFAULT
            FROM 
                INFORMATION_SCHEMA.COLUMNS
            WHERE 
                TABLE_SCHEMA = :dbname
            ORDER BY 
                TABLE_NAME, ORDINAL_POSITION
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['dbname' => $dbname]);
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tables = [];
        foreach ($columns as $column) {
            $tableName = $column['TABLE_NAME'];
            if (!isset($tables[$tableName])) {
                $tables[$tableName] = [
                    'name' => $tableName,
                    'columns' => []
                ];
            }

            // Oszlop típus és méret szétválasztása
            preg_match('/^(\w+)(?:\((\d+)\))?/', $column['COLUMN_TYPE'], $matches);
            $type = $matches[1] ?? $column['COLUMN_TYPE'];
            $size = isset($matches[2]) ? (int)$matches[2] : null;

            // Attribútumok beállítása
            $attributes = [];
            if ($column['COLUMN_KEY'] === 'PRI') $attributes[] = 'primary';
            if ($column['EXTRA'] === 'auto_increment') $attributes[] = 'ai';
            if ($column['IS_NULLABLE'] === 'YES') {
                $isnull = true;
            } else {
                $isnull = false;
            }

            // Oszlop hozzáadása a táblához
            $tables[$tableName]['columns'][] = [
                'name' => $column['COLUMN_NAME'],
                'type' => $type,
                'size' => $size,
                'isnull' => $isnull,
                'default' => $column['COLUMN_DEFAULT'],
                'attributes' => $attributes
            ];
        }

        echo json_encode(['err' => false, 'data' => array_values($tables)]);
    } catch (PDOException $e) {
        echo json_encode(['err' => true, 'data' => "Nem sikerült csatlakozni az adatbázishoz: " . $e->getMessage()]);
    }
}



function check_db(){

    
    // A Reg osztály helyett most Req osztályt használunk, mivel a Req osztály adja vissza a kérés adatokat
    $host = Req::getReqBody()["db_host"];  
    $user = Req::getReqBody()["db_user"];   
    $pass = Req::getReqBody()["db_pass"];    
    $dbname = Req::getReqBody()["db_name"];   

    // Kapcsolódás tesztelése
    try {
        // PDO kapcsolódás
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        // Beállítjuk a hibakezelést
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ha minden rendben van, akkor sikeres kapcsolat
        echo json_encode([
            "err" => false,
            "data" => "Sikeresen csatlakozva az adatbázishoz!"
        ]);
    } catch (PDOException $e) {
        // Ha hiba van a kapcsolódásban, akkor hibaüzenet
        echo json_encode([
            "err" => true,
            "data" => "Nem sikerült csatlakozni az adatbázishoz: " . $e->getMessage()
        ]);
    }
}



function call_Controller_method() {
    // Alapértelmezett vezérlő hívás
}


?>
