/* Since for testing we're using SQLite, I see no point in using char types; Otherwise I'd also set encoding to UTF8 or whatever is needed */

CREATE TABLE users (
	id INTEGER PRIMARY KEY,
  	email VARCHAR(150) NOT NULL,
  	password VARCHAR(60) NOT NULL, /* Since we use bcrypt and it's always 60 characters wide */
  	name VARCHAR(50) NOT NULL,
  	age TINYINT(3) NOT NULL, /* Because I doubt we'll have customers older than 127 years; Also, maybe have birthday in stead of age number? */
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

/* At the moment type can be percentage, bonus or real; In actuality that data should be a separate column, because you could get real money from percentage bonus */
/* If there would be a direct connection bonus <-> user, then bonus could be automatically depleted on usage, or when usage limit has been reached */ 
/* ex. 5 usages per account or once per account for up to 10 accounts */
CREATE TABLE bonuses (
	id INTEGER PRIMARY KEY,
	trigger VARCHAR(25) NOT NULL,
	value INTEGER NOT NULL,
	type VARCHAR(10) NOT NULL,
	currency VARCHAR(3) NOT NULL DEFAULT 'USD',
	multiplier DOUBLE NOT NULL,
	active TINYINT(1) NOT NULL DEFAULT 1 
);