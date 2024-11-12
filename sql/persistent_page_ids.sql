
CREATE TABLE IF NOT EXISTS /*_*/persistent_page_ids (
	page_id        INTEGER         NOT NULL,
	persistent_id  VARBINARY(255)  NOT NULL,
	PRIMARY KEY(page_id),
	UNIQUE(persistent_id)
) /*$wgDBTableOptions*/;
