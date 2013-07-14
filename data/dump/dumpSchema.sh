# Dumping the schema
pg_dump -p 54321 --format plain --schema-only --no-owner --no-privileges --no-tablespaces --schema "community" "building" > 1-schema.sql

