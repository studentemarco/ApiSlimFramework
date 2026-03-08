-- Aggiornamento viste: aggiunta ID dell'oggetto selezionato
-- Database: ApiSlimFramework

START TRANSACTION;

CREATE OR REPLACE VIEW `1` AS
SELECT DISTINCT
    p.pid AS Pid,
    p.pnome AS Pnome
FROM Pezzi p
JOIN Catalogo c ON c.pid = p.pid;

CREATE OR REPLACE VIEW `2` AS
SELECT
    f.fid AS Fid,
    f.fnome AS Fnome
FROM Fornitori f
WHERE NOT EXISTS (
    SELECT 1
    FROM Pezzi p
    WHERE NOT EXISTS (
        SELECT 1
        FROM Catalogo c
        WHERE c.fid = f.fid
          AND c.pid = p.pid
        LIMIT 1
    )
    LIMIT 1
);

CREATE OR REPLACE VIEW `3` AS
SELECT
    f.fid AS Fid,
    f.fnome AS Fnome
FROM Fornitori f
WHERE NOT EXISTS (
    SELECT 1
    FROM Pezzi p
    WHERE p.colore = 'rosso'
      AND NOT EXISTS (
          SELECT 1
          FROM Catalogo c
          WHERE c.fid = f.fid
            AND c.pid = p.pid
          LIMIT 1
      )
    LIMIT 1
);

CREATE OR REPLACE VIEW `4` AS
SELECT DISTINCT
    p.pid AS Pid,
    p.pnome AS Pnome
FROM Pezzi p
JOIN Catalogo c ON c.pid = p.pid
JOIN Fornitori f ON f.fid = c.fid
WHERE f.fnome = 'Acme'
  AND NOT EXISTS (
      SELECT 1
      FROM Catalogo c2
      WHERE c2.pid = p.pid
        AND c2.fid <> c.fid
      LIMIT 1
  );

CREATE OR REPLACE VIEW `5` AS
SELECT DISTINCT
    c.fid AS Fid
FROM Catalogo c
WHERE c.costo > (
    SELECT AVG(c2.costo)
    FROM Catalogo c2
    WHERE c2.pid = c.pid
);

CREATE OR REPLACE VIEW `6` AS
SELECT
    p.pid AS Pid,
    p.pnome AS Pnome,
    f.fid AS Fid,
    f.fnome AS Fnome
FROM Catalogo c
JOIN Pezzi p ON p.pid = c.pid
JOIN Fornitori f ON f.fid = c.fid
WHERE c.costo = (
    SELECT MAX(c2.costo)
    FROM Catalogo c2
    WHERE c2.pid = c.pid
)
ORDER BY p.pid ASC, f.fnome ASC;

CREATE OR REPLACE VIEW `7` AS
SELECT
    c.fid AS Fid
FROM Catalogo c
JOIN Pezzi p ON p.pid = c.pid
GROUP BY c.fid
HAVING SUM(CASE WHEN p.colore <> 'rosso' THEN 1 ELSE 0 END) = 0;

CREATE OR REPLACE VIEW `8` AS
SELECT
    c.fid AS Fid
FROM Catalogo c
JOIN Pezzi p ON p.pid = c.pid
GROUP BY c.fid
HAVING SUM(p.colore = 'rosso') > 0
   AND SUM(p.colore = 'verde') > 0;

CREATE OR REPLACE VIEW `9` AS
SELECT DISTINCT
    c.fid AS Fid
FROM Catalogo c
JOIN Pezzi p ON p.pid = c.pid
WHERE p.colore IN ('rosso', 'verde');

CREATE OR REPLACE VIEW `10` AS
SELECT
    c.pid AS Pid
FROM Catalogo c
GROUP BY c.pid
HAVING COUNT(DISTINCT c.fid) >= 2;

COMMIT;
