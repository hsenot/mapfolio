--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: community; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA community;


SET search_path = community, pg_catalog;

SET default_with_oids = false;

--
-- Name: building; Type: TABLE; Schema: community; Owner: -
--

CREATE TABLE building (
    id integer NOT NULL,
    name text,
    the_geom public.geometry(Geometry,4326),
    status smallint DEFAULT 0
);


--
-- Name: building_id_seq; Type: SEQUENCE; Schema: community; Owner: -
--

CREATE SEQUENCE building_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: building_id_seq; Type: SEQUENCE OWNED BY; Schema: community; Owner: -
--

ALTER SEQUENCE building_id_seq OWNED BY building.id;


--
-- Name: tag; Type: TABLE; Schema: community; Owner: -
--

CREATE TABLE tag (
    id integer NOT NULL,
    label character varying,
    icon character varying
);


--
-- Name: tag_building; Type: TABLE; Schema: community; Owner: -
--

CREATE TABLE tag_building (
    id integer NOT NULL,
    tag_id integer,
    building_id integer
);


--
-- Name: tag_building_id_seq; Type: SEQUENCE; Schema: community; Owner: -
--

CREATE SEQUENCE tag_building_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tag_building_id_seq; Type: SEQUENCE OWNED BY; Schema: community; Owner: -
--

ALTER SEQUENCE tag_building_id_seq OWNED BY tag_building.id;


--
-- Name: tag_id_seq; Type: SEQUENCE; Schema: community; Owner: -
--

CREATE SEQUENCE tag_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tag_id_seq; Type: SEQUENCE OWNED BY; Schema: community; Owner: -
--

ALTER SEQUENCE tag_id_seq OWNED BY tag.id;


--
-- Name: id; Type: DEFAULT; Schema: community; Owner: -
--

ALTER TABLE ONLY building ALTER COLUMN id SET DEFAULT nextval('building_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: community; Owner: -
--

ALTER TABLE ONLY tag ALTER COLUMN id SET DEFAULT nextval('tag_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: community; Owner: -
--

ALTER TABLE ONLY tag_building ALTER COLUMN id SET DEFAULT nextval('tag_building_id_seq'::regclass);


--
-- Name: building_pk; Type: CONSTRAINT; Schema: community; Owner: -
--

ALTER TABLE ONLY building
    ADD CONSTRAINT building_pk PRIMARY KEY (id);


--
-- Name: tag_building_pk; Type: CONSTRAINT; Schema: community; Owner: -
--

ALTER TABLE ONLY tag_building
    ADD CONSTRAINT tag_building_pk PRIMARY KEY (id);


--
-- Name: tag_pk; Type: CONSTRAINT; Schema: community; Owner: -
--

ALTER TABLE ONLY tag
    ADD CONSTRAINT tag_pk PRIMARY KEY (id);


--
-- Name: building_geom_idx; Type: INDEX; Schema: community; Owner: -
--

CREATE INDEX building_geom_idx ON building USING gist (the_geom);


--
-- PostgreSQL database dump complete
--

