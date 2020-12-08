CREATE EXTENSION "uuid-ossp" WITH SCHEMA "api";

SET search_path TO api;

CREATE TABLE source (
    id uuid NOT NULL default uuid_generate_v1mc(),
    name text NOT NULL,
    full_name varchar NOT NULL,
    active boolean NOT NULL DEFAULT TRUE,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT source_pkey PRIMARY KEY (id)
);

CREATE UNIQUE INDEX source_name_unique ON source (name);

CREATE TABLE settings (
    id uuid NOT NULL default uuid_generate_v1mc(),
    source_id uuid NOT NULL,
    key character varying(255) NOT NULL,
    data jsonb NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT settings_pkey PRIMARY KEY (id),
    CONSTRAINT settings_source_id_foreign FOREIGN KEY (source_id)
      REFERENCES source (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
);

CREATE UNIQUE INDEX settings__source_key_unique ON settings (source_id, key);

CREATE TABLE batch (
    id uuid NOT NULL default uuid_generate_v1mc(),
    source_id uuid NOT NULL,
    batch_id int NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT batch_pkey PRIMARY KEY (id)
);

CREATE TABLE product (
    id uuid NOT NULL default uuid_generate_v1mc(),
    name varchar NOT NULL,
    additional_data jsonb NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT product_pkey PRIMARY KEY (id)
);

CREATE UNIQUE INDEX product_name_unique ON product (name);

CREATE TABLE offer (
    id uuid NOT NULL default uuid_generate_v1mc(),
    source_id uuid NOT NULL,
    batch_id uuid NOT NULL,
    product_id uuid NOT NULL,
    offer_id int NOT NULL,
    cash_back float NOT NULL,
    image_url varchar,
    additional_data jsonb NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT entry_pkey PRIMARY KEY (id),
    CONSTRAINT entry_batch_id_foreign FOREIGN KEY (batch_id)
      REFERENCES batch (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
    CONSTRAINT entry_product_id_foreign FOREIGN KEY (product_id)
      REFERENCES product (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
);