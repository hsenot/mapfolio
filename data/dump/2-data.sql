-- TODO finer-tuned comparison (this is really rough)

-- deleting only OSM building data
delete from community.building where id>0; 

-- loading building data from OSM
insert into community.building (id,name,the_geom,status) select osm_id,name,the_geom,0 from osm_buildings;

