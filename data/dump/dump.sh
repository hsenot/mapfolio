rm /opt/opengeo/suite/webapps/solarsize/data/dump/community.zip
pg_dump -p 54321 -n community solar > /opt/opengeo/suite/webapps/solarsize/data/dump/community.sql
zip /opt/opengeo/suite/webapps/solarsize/data/dump/community.zip /opt/opengeo/suite/webapps/solarsize/data/dump/community.sql
rm /opt/opengeo/suite/webapps/solarsize/data/dump/community.sql
