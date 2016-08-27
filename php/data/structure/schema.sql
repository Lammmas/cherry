/* Since for testing we're using SQLite, I see no point in using char types; Otherwise I'd also set encoding to UTF8 or whatever is needed */

CREATE TABLE users (
	id INTEGER PRIMARY KEY,
  	email VARCHAR(150) NOT NULL,
  	name VARCHAR(50) NOT NULL,
  	age TINYINT(3) NOT NULL, /* Because I doubt we'll have customers older than 127 years */
  	gender VARCHAR(10) NOT NULL /* Because to some, 'mayonnaise' is a gender */
);

CREATE TABLE wallets (
	id INTEGER PRIMARY KEY,
	user_id INTEGER NOT NULL,
	bonus_id INTEGER NULL,
	balance INTEGER NOT NULL DEFAULT 0,
	original INTEGER NOT NULL,
	currency VARCHAR(3) NOT NULL DEFAULT 'USD',
	active TINYINT(1) NOT NULL DEFAULT 1, /* Because SQLite stores BOOLEAN as a 1/0 INT anyways */
	bonus TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE bonuses (
	id INTEGER PRIMARY KEY,
	trigger VARCHAR(25) NOT NULL,
	value INTEGER NOT NULL,
	type VARCHAR(10) NOT NULL,
	multiplier DOUBLE NOT NULL,
	active TINYINT(1) NOT NULL DEFAULT 1
);