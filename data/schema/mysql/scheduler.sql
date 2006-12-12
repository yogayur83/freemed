# $Id$
#
# Authors:
#      Jeff Buchbinder <jeff@freemedsoftware.org>
#
# FreeMED Electronic Medical Record and Practice Management System
# Copyright (C) 1999-2006 FreeMED Software Foundation
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

SOURCE data/schema/mysql/patient.sql

CREATE TABLE IF NOT EXISTS `scheduler` (
	caldateof		DATE,
	calcreated		TIMESTAMP (16),
	calmodified		TIMESTAMP (16),
	caltype			ENUM( 'temp', 'pat', 'block' ) NOT NULL DEFAULT 'pat',
	calhour			INT UNSIGNED,
	calminute		INT UNSIGNED,
	calduration		INT UNSIGNED,
	calfacility		INT UNSIGNED,
	calroom			INT UNSIGNED,
	calphysician		INT UNSIGNED,
	calpatient		INT UNSIGNED,
	calcptcode		INT UNSIGNED,
	calstatus		ENUM ( 'scheduled', 'confirmed', 'attended', 'cancelled', 'noshow', 'tenative' ) NOT NULL DEFAULT 'scheduled',
	calprenote		VARCHAR (250),
	calpostnote		TEXT,
	calmark			INT UNSIGNED NOT NULL DEFAULT 0,
	calgroupid		INT UNSIGNED NOT NULL DEFAULT 0,
	calrecurnote		VARCHAR (100),
	calrecurid		INT UNSIGNED NOT NULL DEFAULT 0,
	calappttemplate		INT UNSIGNED NOT NULL DEFAULT 0,
	id			SERIAL,

	# Define keys

	KEY			( caldateof, calhour, calminute ),
	FOREIGN KEY		( calpatient ) REFERENCES patient ( id ) ON DELETE CASCADE
) ENGINE=InnoDB;

#	Version 0.6.3
ALTER IGNORE TABLE scheduler ADD COLUMN calgroupid INT UNSIGNED NOT NULL DEFAULT 0 AFTER calmark;
ALTER IGNORE TABLE scheduler ADD COLUMN calrecurnote VARCHAR (100) AFTER calgroupid;
ALTER IGNORE TABLE scheduler ADD COLUMN calrecurid INT UNSIGNED NOT NULL DEFAULT 0 AFTER calrecurnote;
ALTER IGNORE TABLE scheduler CHANGE COLUMN caltype caltype ENUM ( 'temp', 'pat', 'block' );
ALTER IGNORE TABLE scheduler CHANGE COLUMN calstatus caltype ENUM ( 'scheduled', 'confirmed', 'attended', 'cancelled', 'noshow', 'tenative' ) NOT NULL DEFAULT 'scheduled';

#	Version 0.6.3.1
ALTER IGNORE TABLE scheduler CHANGE COLUMN calprenote calprenote VARCHAR (250);

#	Version 0.6.5
ALTER IGNORE TABLE scheduler ADD COLUMN calcreated TIMESTAMP (16) AFTER caldateof;
ALTER IGNORE TABLE scheduler ADD COLUMN calmodified TIMESTAMP (16) AFTER calcreated;

#	Version 0.6.6
ALTER IGNORE TABLE scheduler ADD COLUMN calappttemplate INT UNSIGNED NOT NULL DEFAULT 0 AFTER calrecurid;

