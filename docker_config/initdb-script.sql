-- Crea la collation
-- CREATE COLLATION ndcoll (provider = icu, locale = 'und', deterministic = false);

-- Crea la base de datos
-- CREATE DATABASE fairy_tickets COLLATE ndcoll;
CREATE DATABASE fairy_tickets;

-- Conecta a la base de datos
\c fairy_tickets;

-- Instala la extensión unaccent
CREATE EXTENSION IF NOT EXISTS unaccent;