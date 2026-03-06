<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use App\Database;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// CORS Middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Handle OPTIONS requests for CORS preflight
$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});

function jsonResponse($response, $data) {
    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
}

function errorResponse($response, $message, $status = 500) {
    $payload = ['error' => $message];
    $response->getBody()->write(json_encode($payload, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
}

function queryJsonResponse($response, $sql) {
    try {
        $db = Database::connect();
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return jsonResponse($response, $result);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    } catch (Exception $e) {
        return errorResponse($response, 'Server error: ' . $e->getMessage(), 501);
    }
}

function queryJsonResponseWithParams($response, $sql, $params) {
    try {
        $db = Database::connect();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return jsonResponse($response, $result);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    } catch (Exception $e) {
        return errorResponse($response, 'Server error: ' . $e->getMessage(), 501);
    }
}

$app->get('/', function ($request, $response) {
    return jsonResponse($response, [
        'message' => '',
        'endpoints' => [
            [
                'method' => 'GET',
                'path' => '/0',
                'description' => 'Lista delle tabelle'
            ],
            [
                'method' => 'GET',
                'path' => '/1',
                'description' => 'Pezzi con almeno un fornitore'
            ],
            [
                'method' => 'GET',
                'path' => '/2',
                'description' => 'Fornitori che forniscono ogni pezzo'
            ],
            [
                'method' => 'GET',
                'path' => '/3',
                'description' => 'Fornitori che forniscono tutti i pezzi rossi'
            ],
            [
                'method' => 'GET',
                'path' => '/4',
                'description' => 'Pezzi forniti solo da Acme'
            ],
            [
                'method' => 'GET',
                'path' => '/5',
                'description' => 'Fornitori che ricaricano sopra media'
            ],
            [
                'method' => 'GET',
                'path' => '/6',
                'description' => 'Fornitore con ricarico massimo per ogni pezzo'
            ],
            [
                'method' => 'GET',
                'path' => '/7',
                'description' => 'Fornitori che forniscono solo pezzi rossi'
            ],
            [
                'method' => 'GET',
                'path' => '/8',
                'description' => 'Fornitori che forniscono rosso e verde'
            ],
            [
                'method' => 'GET',
                'path' => '/9',
                'description' => 'Fornitori che forniscono rosso o verde'
            ],
            [
                'method' => 'GET',
                'path' => '/10',
                'description' => 'Pezzi forniti da almeno 2 fornitori'
            ],
            [
                'method' => 'GET',
                'path' => '/pezzo/{pid}',
                'description' => 'Dettagli di un pezzo specifico'
            ],
            [
                'method' => 'GET',
                'path' => '/fornitore/{fid}',
                'description' => 'Dettagli di un fornitore specifico'
            ],
            [
                'method' => 'GET',
                'path' => '/dashboard',
                'description' => 'Dashboard per testare gli endpoint'
            ]
        ]
    ]);
});

$app->get('/0', function ($request, $response) {
    return queryJsonResponse($response, "SHOW TABLES");
});

$app->get('/1', function ($request, $response) {
    return queryJsonResponse($response, "SELECT * FROM `1`");
});

$app->get('/2', function ($request, $response) {
    return queryJsonResponse($response, "SELECT * FROM `2`");
});

$app->get('/3', function ($request, $response) {
    return queryJsonResponse($response, "SELECT * FROM `3`");
});

$app->get('/4', function ($request, $response) {
    return queryJsonResponse($response, "SELECT * FROM `4`");
});

$app->get('/5', function ($request, $response) {
    return queryJsonResponse($response, "SELECT * FROM `5`");
});

$app->get('/6', function ($request, $response) {
    return queryJsonResponse($response, "SELECT * FROM `6`");
});

$app->get('/7', function ($request, $response) {
    return queryJsonResponse($response, "SELECT * FROM `7`");
});

$app->get('/8', function ($request, $response) {
    return queryJsonResponse($response, "SELECT * FROM `8`");
});

$app->get('/9', function ($request, $response) {
    return queryJsonResponse($response, "SELECT * FROM `9`");
});

$app->get('/10', function ($request, $response) {
    return queryJsonResponse($response, "SELECT * FROM `10`");
});

$app->get('/pezzo/{pid}', function ($request, $response, $args) {
    return queryJsonResponseWithParams($response, "SELECT * FROM `Pezzi` WHERE pid = ?", [$args['pid']]);
});

$app->get('/fornitore/{fid}', function ($request, $response, $args) {
    return queryJsonResponseWithParams($response, "SELECT * FROM `Fornitori` WHERE fid = ?", [$args['fid']]);
});

// Dashboard - Testare gli endpoint
$app->get('/dashboard', function ($request, $response) {
    $dashboardFile = __DIR__ . '/../frontend/index.php';
    if (file_exists($dashboardFile)) {
        return $response->withHeader('Content-Type', 'text/html')
            ->withBody(new \Slim\Psr7\Stream(fopen($dashboardFile, 'r')));
    }
    return errorResponse($response, 'Dashboard not found', 404);
});

// Se l'endpoint non esiste, restituisci un errore 404 in JSON
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    return errorResponse($response, 'Endpoint not found', 404);
});

$app->run();
?>