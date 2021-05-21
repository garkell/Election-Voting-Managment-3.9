CREATE TABLE IF NOT EXISTS #__gavoting_positions (
id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
ordering INT(11) DEFAULT '0',
state TINYINT(1)  DEFAULT '0',
checked_out INT(11)  DEFAULT '0',
checked_out_time DATETIME NULL ,
created_by INT(11)  DEFAULT '0',
modified_by INT(11)  DEFAULT '0',
created_date DATETIME NULL DEFAULT NULL,
modified_date DATETIME NULL DEFAULT NULL ,
pos_name VARCHAR(255)  NULL DEFAULT NULL ,
cat_id INT(11)  NOT NULL DEFAULT '0' ,
elect_date DATETIME NULL DEFAULT NULL ,
elected VARCHAR(255)  NULL ,
comment TEXT NULL ,
PRIMARY KEY (id)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT into #__gavoting_positions (state, created_date, pos_name) VALUES
(1, '2020-08-15 00:00:00', 'President'),
(1, '2020-08-15 00:00:00', 'Vice President'),
(1, '2020-08-15 00:00:00', 'Secretary'),
(1, '2020-08-15 00:00:00', 'Treasurer'),
(1, '2020-08-15 00:00:00', 'Membership Officer'),
(0, '2020-08-15 00:00:00', 'Public Officer'),
(1, '2020-08-15 00:00:00', 'Property Officer'),
(0, '2020-08-15 00:00:00', 'Welfare Officer'),
(1, '2020-08-15 00:00:00', 'Editor'),
(1, '2020-08-15 00:00:00', 'Training Co-ordinator'),
(1, '2020-08-15 00:00:00', 'Trip Co-ordinator'),
(1, '2020-08-15 00:00:00', 'Social Co-ordinator'),
(1, '2020-08-15 00:00:00', 'Merchandise Co-ordinator'),
(1, '2020-08-15 00:00:00', 'National Delegate'),
(1, '2020-08-15 00:00:00', 'Regional Representative'),
(1, '2020-08-15 00:00:00', 'Web Administrator'),
(1, '2020-08-15 00:00:00', 'Sergeant at Arms'),
(1, '2020-08-15 00:00:00', 'Tea Person');

CREATE TABLE IF NOT EXISTS #__gavoting_nominations (
id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
ordering INT(11)  DEFAULT '0',
state TINYINT(1)  DEFAULT '0',
checked_out INT(11)  DEFAULT '0',
checked_out_time DATETIME NULL DEFAULT NULL ,
created_by INT(11)  DEFAULT '0',
modified_by INT(11)  DEFAULT '0',
created_date DATETIME NULL DEFAULT NULL ,
modified_date DATETIME NULL DEFAULT NULL ,
position_id INT(11)  NULL ,
nomination INT(11)  NULL ,
nom_name VARCHAR(255)  NULL ,
nom_date DATETIME NULL DEFAULT NULL ,
nom_id INT(11)  NULL ,
sec_id INT(11)  NULL ,
agreed TINYINT(1)  NOT NULL DEFAULT '0',
agreed_date DATETIME NULL DEFAULT NULL ,
votes INT(11)  NOT NULL DEFAULT '0',
comment TEXT NULL ,
PRIMARY KEY (id)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS #__gavoting_voters (
id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
ordering INT(11)  DEFAULT '0',
state TINYINT(1)  DEFAULT '0',
checked_out INT(11)  DEFAULT '0',
checked_out_time DATETIME NULL DEFAULT NULL ,
created_by INT(11)  DEFAULT '0',
modified_by INT(11)  DEFAULT '0',
created_date DATETIME NULL DEFAULT NULL ,
modified_date DATETIME NULL DEFAULT NULL ,
user_id INT(11)  NOT NULL DEFAULT '0' ,
proxy_vote TINYINT(1)  NOT NULL DEFAULT '0' ,
cat_id INT(11)  NOT NULL DEFAULT '0' ,
comment TEXT NULL ,
PRIMARY KEY (id)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

