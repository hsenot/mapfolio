# Obtaining OSM building data and loading it in the public schema
curl http://app.carbongis.com.au/bip/data/PGDUMP/buildings.sql.zip -o buildings.sql.zip
unzip buildings.sql.zip
psql -p 54321 -f buildings.sql building

# Clean up
rm buildings.sql
rm buildings.sql.zip

# Drop schema if exists
psql -p 54321 -f "0-dropschema.sql" building

# Create schema community and objects
psql -p 54321 -f "1-schema.sql" building

# Load data from the OSM building table into the 'community' building table
psql -p 54321 -f "2-data.sql" building

