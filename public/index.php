<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use App\Database;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// CORS Middleware
$app->add(function ($request, $handler) use ($app) {
    if (strtoupper($request->getMethod()) === 'OPTIONS') {
        $response = $app->getResponseFactory()->createResponse(204);
    } else {
        $response = $handler->handle($request);
    }

    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, X-Username, X-Password')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
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
    } catch (Throwable $e) {
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
    } catch (Throwable $e) {
        return errorResponse($response, 'Server error: ' . $e->getMessage(), 501);
    }
}

function getPagination($request): array {
    $q = $request->getQueryParams();
    $page = max(1, (int)($q['page'] ?? 1));
    $perPage = max(1, min(50, (int)($q['per_page'] ?? 10)));
    $offset = ($page - 1) * $perPage;
    return [$page, $perPage, $offset];
}

function paginatedQuery($db, string $sqlCount, string $sqlData, array $params, int $page, int $perPage, int $offset): array {
    $stmtCount = $db->prepare($sqlCount);
    $stmtCount->execute($params);
    $total = (int)$stmtCount->fetchColumn();

    $stmtData = $db->prepare($sqlData . " LIMIT ? OFFSET ?");
    $i = 1;
    foreach ($params as $p) {
        $stmtData->bindValue($i++, $p);
    }
    $stmtData->bindValue($i++, $perPage, PDO::PARAM_INT);
    $stmtData->bindValue($i++, $offset, PDO::PARAM_INT);
    $stmtData->execute();
    $rows = $stmtData->fetchAll(PDO::FETCH_ASSOC);

    return [
        'data' => $rows,
        'meta' => [
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => (int)ceil($total / max(1, $perPage))
        ]
    ];
}

function authUser($request) {
    $username = $request->getHeaderLine('X-Username');
    $password = $request->getHeaderLine('X-Password');
    if (!$username || !$password) return null;

    $db = Database::connect();
    $stmt = $db->prepare("SELECT * FROM Utenti WHERE username=? AND attivo=1");
    $stmt->execute([$username]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$u) return null;
    if (!password_verify($password, $u['password_hash'])) return null;
    return $u;
}

function isAdmin(array $user): bool {
    return ($user['ruolo'] ?? '') === 'admin';
}

function canManageCatalog(array $user, int $fid): bool {
    if (isAdmin($user)) {
        return true;
    }

    return ($user['ruolo'] ?? '') === 'fornitore' && (int)($user['fid'] ?? 0) === $fid;
}

function requireAuthUser($request, $response) {
    $user = authUser($request);
    if (!$user) {
        return [null, errorResponse($response, 'Unauthorized', 401)];
    }

    return [$user, null];
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

$app->get('/catalogo/{fid}/{pid}', function ($request, $response, $args) {
    return queryJsonResponseWithParams(
        $response,
        "SELECT * FROM `Catalogo` WHERE fid = ? AND pid = ?",
        [(int)$args['fid'], (int)$args['pid']]
    );
});

$app->get('/auth/me', function ($request, $response) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }

    return jsonResponse($response, [
        'uid' => (int)$user['uid'],
        'username' => $user['username'],
        'ruolo' => $user['ruolo'],
        'fid' => $user['fid'] !== null ? (int)$user['fid'] : null
    ]);
});

$app->get('/fornitori', function ($request, $response) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }

    [$page, $perPage, $offset] = getPagination($request);

    try {
        $db = Database::connect();
        $result = paginatedQuery(
            $db,
            "SELECT COUNT(*) FROM Fornitori",
            "SELECT fid, fnome, indirizzo FROM Fornitori ORDER BY fid",
            [],
            $page,
            $perPage,
            $offset
        );
        return jsonResponse($response, $result);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
});

$app->post('/fornitori', function ($request, $response) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }
    if (!isAdmin($user)) {
        return errorResponse($response, 'Forbidden', 403);
    }

    $body = $request->getParsedBody() ?? [];
    $fnome = trim((string)($body['fnome'] ?? ''));
    $indirizzo = trim((string)($body['indirizzo'] ?? ''));
    if ($fnome === '' || $indirizzo === '') {
        return errorResponse($response, 'fnome e indirizzo obbligatori', 400);
    }

    try {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO Fornitori (fnome, indirizzo) VALUES (?, ?)");
        $stmt->execute([$fnome, $indirizzo]);
        return jsonResponse($response, ['ok' => true, 'fid' => (int)$db->lastInsertId()]);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
});

$app->put('/fornitori/{fid}', function ($request, $response, $args) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }
    if (!isAdmin($user)) {
        return errorResponse($response, 'Forbidden', 403);
    }

    $fid = (int)$args['fid'];
    $body = $request->getParsedBody() ?? [];
    $fnome = trim((string)($body['fnome'] ?? ''));
    $indirizzo = trim((string)($body['indirizzo'] ?? ''));
    if ($fnome === '' || $indirizzo === '') {
        return errorResponse($response, 'fnome e indirizzo obbligatori', 400);
    }

    try {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE Fornitori SET fnome = ?, indirizzo = ? WHERE fid = ?");
        $stmt->execute([$fnome, $indirizzo, $fid]);
        return jsonResponse($response, ['ok' => true, 'updated' => $stmt->rowCount()]);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
});

$app->delete('/fornitori/{fid}', function ($request, $response, $args) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }
    if (!isAdmin($user)) {
        return errorResponse($response, 'Forbidden', 403);
    }

    $fid = (int)$args['fid'];
    try {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM Fornitori WHERE fid = ?");
        $stmt->execute([$fid]);
        return jsonResponse($response, ['ok' => true, 'deleted' => $stmt->rowCount()]);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
});

$app->get('/pezzi', function ($request, $response) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }

    [$page, $perPage, $offset] = getPagination($request);

    try {
        $db = Database::connect();
        $result = paginatedQuery(
            $db,
            "SELECT COUNT(*) FROM Pezzi",
            "SELECT pid, pnome, colore FROM Pezzi ORDER BY pid",
            [],
            $page,
            $perPage,
            $offset
        );
        return jsonResponse($response, $result);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
});

$app->post('/pezzi', function ($request, $response) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }
    if (!isAdmin($user)) {
        return errorResponse($response, 'Forbidden', 403);
    }

    $body = $request->getParsedBody() ?? [];
    $pnome = trim((string)($body['pnome'] ?? ''));
    $colore = trim((string)($body['colore'] ?? ''));
    if ($pnome === '' || $colore === '') {
        return errorResponse($response, 'pnome e colore obbligatori', 400);
    }

    try {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO Pezzi (pnome, colore) VALUES (?, ?)");
        $stmt->execute([$pnome, $colore]);
        return jsonResponse($response, ['ok' => true, 'pid' => (int)$db->lastInsertId()]);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
});

$app->put('/pezzi/{pid}', function ($request, $response, $args) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }
    if (!isAdmin($user)) {
        return errorResponse($response, 'Forbidden', 403);
    }

    $pid = (int)$args['pid'];
    $body = $request->getParsedBody() ?? [];
    $pnome = trim((string)($body['pnome'] ?? ''));
    $colore = trim((string)($body['colore'] ?? ''));
    if ($pnome === '' || $colore === '') {
        return errorResponse($response, 'pnome e colore obbligatori', 400);
    }

    try {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE Pezzi SET pnome = ?, colore = ? WHERE pid = ?");
        $stmt->execute([$pnome, $colore, $pid]);
        return jsonResponse($response, ['ok' => true, 'updated' => $stmt->rowCount()]);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
});

$app->delete('/pezzi/{pid}', function ($request, $response, $args) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }
    if (!isAdmin($user)) {
        return errorResponse($response, 'Forbidden', 403);
    }

    $pid = (int)$args['pid'];
    try {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM Pezzi WHERE pid = ?");
        $stmt->execute([$pid]);
        return jsonResponse($response, ['ok' => true, 'deleted' => $stmt->rowCount()]);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
});

$app->get('/catalogo', function ($request, $response) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }

    [$page, $perPage, $offset] = getPagination($request);
    $query = $request->getQueryParams();
    $fid = isset($query['fid']) ? (int)$query['fid'] : null;

    if (!isAdmin($user)) {
        $fid = (int)$user['fid'];
    }

    try {
        $db = Database::connect();

        if ($fid !== null) {
            $result = paginatedQuery(
                $db,
                "SELECT COUNT(*) FROM Catalogo WHERE fid = ?",
                "SELECT fid, pid, costo FROM Catalogo WHERE fid = ? ORDER BY pid",
                [$fid],
                $page,
                $perPage,
                $offset
            );
        } else {
            $result = paginatedQuery(
                $db,
                "SELECT COUNT(*) FROM Catalogo",
                "SELECT fid, pid, costo FROM Catalogo ORDER BY fid, pid",
                [],
                $page,
                $perPage,
                $offset
            );
        }

        return jsonResponse($response, $result);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
});

$app->post('/catalogo', function ($request, $response) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }

    $body = $request->getParsedBody() ?? [];
    $fid = (int)($body['fid'] ?? 0);
    $pid = (int)($body['pid'] ?? 0);
    $costo = (int)($body['costo'] ?? 0);

    if ($fid <= 0 || $pid <= 0 || $costo <= 0) {
        return errorResponse($response, 'fid, pid e costo obbligatori', 400);
    }
    if (!canManageCatalog($user, $fid)) {
        return errorResponse($response, 'Forbidden', 403);
    }

    try {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO Catalogo (fid, pid, costo) VALUES (?, ?, ?)");
        $stmt->execute([$fid, $pid, $costo]);
        return jsonResponse($response, ['ok' => true]);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
});

$app->put('/catalogo/{fid}/{pid}', function ($request, $response, $args) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }

    $fid = (int)$args['fid'];
    $pid = (int)$args['pid'];
    if (!canManageCatalog($user, $fid)) {
        return errorResponse($response, 'Forbidden', 403);
    }

    $body = $request->getParsedBody() ?? [];
    $costo = (int)($body['costo'] ?? 0);
    if ($costo <= 0) {
        return errorResponse($response, 'costo obbligatorio', 400);
    }

    try {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE Catalogo SET costo = ? WHERE fid = ? AND pid = ?");
        $stmt->execute([$costo, $fid, $pid]);
        return jsonResponse($response, ['ok' => true, 'updated' => $stmt->rowCount()]);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
});

$app->delete('/catalogo/{fid}/{pid}', function ($request, $response, $args) {
    [$user, $error] = requireAuthUser($request, $response);
    if ($error) {
        return $error;
    }

    $fid = (int)$args['fid'];
    $pid = (int)$args['pid'];
    if (!canManageCatalog($user, $fid)) {
        return errorResponse($response, 'Forbidden', 403);
    }

    try {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM Catalogo WHERE fid = ? AND pid = ?");
        $stmt->execute([$fid, $pid]);
        return jsonResponse($response, ['ok' => true, 'deleted' => $stmt->rowCount()]);
    } catch (PDOException $e) {
        return errorResponse($response, 'Database error: ' . $e->getMessage(), 501);
    }
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

$app->get('/admin', function ($request, $response) {
    $dashboardFile = __DIR__ . '/../frontend/admin.php';
    if (file_exists($dashboardFile)) {
        return $response->withHeader('Content-Type', 'text/html')
            ->withBody(new \Slim\Psr7\Stream(fopen($dashboardFile, 'r')));
    }
    return errorResponse($response, 'Admin dashboard not found', 404);
});

$app->get('/fornitore', function ($request, $response) {
    $dashboardFile = __DIR__ . '/../frontend/catalogoFornitore.php';
    if (file_exists($dashboardFile)) {
        return $response->withHeader('Content-Type', 'text/html')
            ->withBody(new \Slim\Psr7\Stream(fopen($dashboardFile, 'r')));
    }
    return errorResponse($response, 'Fornitore dashboard not found', 404);
});

// Se l'endpoint non esiste, restituisci un errore 404 in JSON
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    return errorResponse($response, 'Endpoint not found', 404);
});

$app->run();
?>