# Dumping the schema
pg_dump -p 54321 --format plain --no-owner --no-privileges --no-tablespaces --schema "community" "building" > 3-schema-and-data.sql

rm full-dump.zip

zip full-dump.zip "3-schema-and-data.sql"

rm "3-schema-and-data.sql"

