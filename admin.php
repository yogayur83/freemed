<?php
 // $Id$
 // note: administrative functions
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 //       language support by Max Klohn (amk@span.ch)
 // lic : GPL, v2

 $page_name=basename($GLOBALS["REQUEST_URI"]);
 include ("lib/freemed.php");
 include ("lib/API.php"); // include generic functions

 SetCookie ("_ref", $page_name, time()+$_cookie_expire);

 freemed_open_db ($LoginCookie);
 freemed_display_html_top ();
 freemed_display_banner ();

  // security patch...
if (freemed_get_userlevel($LoginCookie)<9) {
  freemed_display_box_top (_("Administration")." :: "._("ERROR"));
  echo "
    <P>
    <$HEADERFONT_B>
    "._("No Admin Menu Access")."
    <$HEADERFONT_E>
    <P>
    <CENTER>
     <A HREF=\"main.php?$_auth\"
     ><$STDFONT_B>"._("Return to the Main Menu")."<$STDFONT_E>
    </CENTER>
    <P>
  ";
  freemed_display_box_bottom ();
  freemed_display_html_bottom ();
  DIE("");
}

if ($action=="cfgform") {

  // this is the frontend to the config
  // database.

    // icd9 option
  $c_result = $sql->query("SELECT * FROM ".
    "config WHERE (c_option='icd')");
  $c_r = $sql->fetch_array($c_result);
  $icd = $c_r["c_value"];
  if ($icd=="10") {
    $_icd_10 = "SELECTED";
  } else {
    $_icd_9  = "SELECTED";
  } // default is icd9

    // gfx option (graphics enhanced)
  $c_result = $sql->query("SELECT * FROM ".
    "config WHERE (c_option='gfx')");
  $c_r = $sql->fetch_array($c_result);
  $gfx = $c_r["c_value"];
  if ($gfx=="1") {
    $_gfx_1 = "SELECTED";
  } else {
    $_gfx_9  = "SELECTED";
  } // default is disabled

  $cal_ob = freemed_config_value ("cal_ob");
  switch ($cal_ob) {
    case "enable":  $_cal_ob_e = "SELECTED"; break;
    case "disable":
           default: $_cal_ob_d = "SELECTED"; break;
  }

  $calshr = freemed_config_value ("calshr"); // get starting time
  switch ($calshr) {
    case  "4": $_cal_s4  = "SELECTED"; break;
    case  "5": $_cal_s5  = "SELECTED"; break;
    case  "6": $_cal_s6  = "SELECTED"; break;
    case  "7": $_cal_s7  = "SELECTED"; break;
    case  "8": $_cal_s8  = "SELECTED"; break;
    case  "9": $_cal_s9  = "SELECTED"; break;
    case "10": $_cal_s10 = "SELECTED"; break;
    case "11": $_cal_s11 = "SELECTED"; break;
    case "12": $_cal_s12 = "SELECTED"; break;
    default  : $_cal_sd  = "SELECTED"; break;
  } // end starting time switch

  $calehr = freemed_config_value ("calehr"); // get ending time
  switch ($calehr) {
    case "14": $_cal_e14 = "SELECTED"; break;
    case "15": $_cal_e15 = "SELECTED"; break;
    case "16": $_cal_e16 = "SELECTED"; break;
    case "17": $_cal_e17 = "SELECTED"; break;
    case "18": $_cal_e18 = "SELECTED"; break;
    case "19": $_cal_e19 = "SELECTED"; break;
    case "20": $_cal_e20 = "SELECTED"; break;
    default:   $_cal_ed  = "SELECTED"; break;
  } // end ending time switch

  $dtfmt = freemed_config_value ("dtfmt"); // get date format
  switch ($dtfmt) {
    case "mdy":   $_dtfmt_mdy = "SELECTED"; break;
    case "ydm":   $_dtfmt_ydm = "SELECTED"; break;
    case "dmy":   $_dtfmt_dmy = "SELECTED"; break;
    case "ymd":
    default:      $_dtfmt_ymd = "SELECTED"; break;
  } // end date format switch

  $phofmt = freemed_config_value ("phofmt"); // get phone format
  switch ($phofmt) {
    case "usa":           $_phofmt_us = "SELECTED"; break;
    case "fr":            $_phofmt_fr = "SELECTED"; break;
    case "unformatted":
    default:              $_phofmt_uf = "SELECTED"; break;
  } // end phone format switch

  freemed_display_box_top (PACKAGENAME." Configuration", $page_name);
  echo "
    <P>

    <FORM ACTION=\"$page_name\">
    <INPUT TYPE=HIDDEN NAME=\"action\" VALUE=\"cfg\">

    <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=3
     VALIGN=MIDDLE ALIGN=CENTER>

    <TR>
    <TD ALIGN=RIGHT><$STDFONT_B>"._("ICD Code Type")." : <$STDFONT_E></TD>
    <TD ALIGN=LEFT>
    <SELECT NAME=\"icd\">
     <OPTION VALUE=\"9\"  $_icd_9 > 9
     <OPTION VALUE=\"10\" $_icd_10>10
    </SELECT>
    </TD></TR>

    <TR>
    <TD ALIGN=RIGHT><$STDFONT_B>"._("Graphics Enhanced")." : <$STDFONT_E></TD>
    <TD ALIGN=LEFT>
    <SELECT NAME=\"gfx\">
     <OPTION VALUE=\"0\" $_gfx_0>"._("Disabled")."
     <OPTION VALUE=\"1\" $_gfx_1>"._("Enabled")."
    </SELECT>
    </TD></TR>

    <TR>
    <TD ALIGN=RIGHT><$STDFONT_B>"._("Scheduling Start Time")." : <$STDFONT_E></TD>
    <TD ALIGN=LEFT>
    <SELECT NAME=\"calshr\">
     <OPTION VALUE=\"\"  $_cal_sd>$DEFAULT
     <OPTION VALUE=\"4\" $_cal_s4>4 am
     <OPTION VALUE=\"5\" $_cal_s5>5 am
     <OPTION VALUE=\"6\" $_cal_s6>6 am
     <OPTION VALUE=\"7\" $_cal_s7>7 am
     <OPTION VALUE=\"8\" $_cal_s8>8 am
     <OPTION VALUE=\"9\" $_cal_s9>9 am
     <OPTION VALUE=\"10\" $_cal_s10>10 am
    </SELECT>
    </TD></TR>

    <TR>
    <TD ALIGN=RIGHT><$STDFONT_B>"._("Scheduling End Time")." : <$STDFONT_E></TD>
    <TD ALIGN=LEFT>
    <SELECT NAME=\"calehr\">
     <OPTION VALUE=\"\"  $_cal_ed>$DEFAULT
     <OPTION VALUE=\"14\" $_cal_e14>2 pm
     <OPTION VALUE=\"15\" $_cal_e15>3 pm
     <OPTION VALUE=\"16\" $_cal_e16>4 pm
     <OPTION VALUE=\"17\" $_cal_e17>5 pm
     <OPTION VALUE=\"18\" $_cal_e18>6 pm
     <OPTION VALUE=\"19\" $_cal_e19>7 pm
     <OPTION VALUE=\"20\" $_cal_e20>8 pm
    </SELECT>
    </TD></TR>

    <TR>
    <TD ALIGN=RIGHT><$STDFONT_B>"._("Calendar Overbooking")." : <$STDFONT_E></TD>
    <TD ALIGN=LEFT>
    <SELECT NAME=\"cal_ob\">
     <OPTION VALUE=\"enable\"  $_cal_ob_e>"._("Enabled")."
     <OPTION VALUE=\"disable\" $_cal_ob_d>"._("Disabled")."
    </SELECT>
    </TD></TR>

    <TR>
    <TD ALIGN=RIGHT><$STDFONT_B>"._("Date Format")." : <$STDFONT_E></TD>
    <TD ALIGN=LEFT>
    <SELECT NAME=\"dtfmt\">
     <OPTION VALUE=\"ymd\"  $_dtfmt_ymd>YYYY-MM-DD
     <OPTION VALUE=\"mdy\"  $_dtfmt_mdy>MM-DD-YYYY
     <OPTION VALUE=\"dmy\"  $_dtfmt_dmy>DD-MM-YYYY
     <OPTION VALUE=\"ydm\"  $_dtfmt_ydm>YYYY-DD-MM
    </SELECT>
    </TD></TR>

    <TR>
    <TD ALIGN=RIGHT><$STDFONT_B>"._("Phone Number Format")." : <$STDFONT_E></TD>
    <TD ALIGN=LEFT>
    <SELECT NAME=\"phofmt\">
     <OPTION VALUE=\"usa\"         $_phofmt_us>"._("United States")." (XXX) XXX-XXXX
     <OPTION VALUE=\"fr\"          $_phofmt_fr>"._("France")." (XX) XX XX XX XX
     <OPTION VALUE=\"unformatted\" $_phofmt_uf>"._("Unformatted")." XXXXXXXXXXXXXXXX
    </SELECT>
    </TD></TR>

    </TABLE>

    <P>

    <CENTER>
    <INPUT TYPE=SUBMIT VALUE=\" "._("Configure")." \">
    <INPUT TYPE=RESET  VALUE=\"   "._("Reset")."   \">
    </CENTER>
    </FORM>
  ";
  freemed_display_box_bottom ();

} elseif ($action=="cfg") {

  freemed_display_box_top (PACKAGENAME._("Update Config"), $page_name);
  echo "
    <P>
  ";

  $q = $sql->query("UPDATE config SET
    c_value='$icd' WHERE c_option='icd'");
  if (($debug) AND ($q)) echo "ICD = $icd<BR>\n";

  $q = $sql->query("UPDATE config SET
    c_value='$gfx' WHERE c_option='gfx'");
  if (($debug) AND ($q)) echo "gfx = $gfx<BR>\n";

  $q = $sql->query("UPDATE config SET
    c_value='$calshr' WHERE c_option='calshr'");
  if (($debug) AND ($q)) echo "calshr = $calshr<BR>\n";

  $q = $sql->query("UPDATE config SET
    c_value='$calehr' WHERE c_option='calehr'");
  if (($debug) AND ($q)) echo "calehr = $calehr<BR>\n";

  $q = $sql->query("UPDATE config SET
    c_value='$cal_ob' WHERE c_option='cal_ob'");
  if (($debug) AND ($q)) echo "cal_ob = $cal_ob<BR>\n";

  $q = $sql->query("UPDATE config SET
    c_value='$dtfmt' WHERE c_option='dtfmt'");
  if (($debug) AND ($q)) echo "dtfmt = $dtfmt<BR>\n";

  $q = $sql->query("UPDATE config SET
    c_value='$phofmt' WHERE c_option='phofmt'");
  if (($debug) AND ($q)) echo "phofmt = $phofmt<BR>\n";

  echo "
    <P>
    <CENTER><B>"._("Configuration Complete")."</B></CENTER>
  ";
  echo "
    <P ALIGN=CENTER>
    <A HREF=\"$page_name?$_auth\"
     >"._("Return To Administration Menu")."</A>
  ";
  freemed_display_box_bottom ();

} elseif ($action=="reinit") {
  freemed_display_box_top (_("Reinitialize Database"), $page_name);
  
    // here, to prevent problems, we ask the user to check that they
    // REALLY want to...

  echo "\n<CENTER>\n";
  echo _("Are you sure you want to reinitialize the database?")."\n";
  echo "<BR><U><B>"._("This is an IRREVERSIBLE PROCESS!")."</B></U><BR>\n";
  echo "\n</CENTER>\n";

  echo "<BR><CENTER>\n";

  echo "
   <FORM ACTION=\"$page_name\" METHOD=POST>
   <INPUT TYPE=CHECKBOX NAME=\"first_time\" VALUE=\"first\">
   <I>"._("First Initialization")."</I><BR>
   <INPUT TYPE=CHECKBOX NAME=\"re_load\" VALUE=\"reload\">
   <I>"._("Reload Stock Data")."</I><BR>
   <INPUT TYPE=HIDDEN NAME=action VALUE=\"reinit_sure\">
   <TABLE BORDER=0 ALIGN=CENTER><TR><TD>
   <INPUT TYPE=SUBMIT VALUE=\""._("Continue")."\">
   </FORM>

   </TD><TD>

   <FORM ACTION=\"$page_name\" METHOD=POST>
   <INPUT TYPE=SUBMIT VALUE=\""._("Cancel")."\">
   </FORM>

   </TD></TR></TABLE>
   </CENTER>
  ";

  freemed_display_box_bottom ();

} elseif ($action=="reinit_sure") {
 // here we actually put the reinitialization (read - wiping
 // and creating the database structure again) code... so that
 // stupids don't accidentally click on it and... oops!

//  if ($first_time!="first") {
//    echo "<$STDFONT_B>"._("Erasing old database")."... ";
//    $sql-drop_db($database) OR
//      DIE("<B>"._("Error accessing SQL")."</B><$STDFONT_E><BR><BR>\n");
//    echo "<B>"._("done")."</B><$STDFONT_E><BR>\n";
//  }

//  echo "<$STDFONT_B>"._("Creating new database")."... ";
//  $sql->create_db($database) OR
//    DIE("<B>"._("Error accessing SQL")."</B><$STDFONT_E><BR><BR>\n");
//  echo "<B>"._("done")."</B><$STDFONT_E><BR>\n";

  echo "<$STDFONT_B><UL>"._("Creating tables")."... \n";
  echo "$re_load\n";

  // generate test table, if debug is on
  if ($debug) {
    $result=$sql->query("DROP TABLE test");
    $result=$sql->query("CREATE TABLE test (
      name CHAR(10), other CHAR(12), phone INT,
      ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
      PRIMARY KEY (ID))");
    if ($result) { echo "<LI>"._("test db")." \n"; }
  } // end debug section

  // generate module table
  $result=$sql->query("DROP TABLE module"); 
  $result=$sql->query("CREATE TABLE module (
    module_name     VARCHAR(100),
    module_version  VARCHAR(50),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)    
    )");
  if ($result) { echo "<LI>"._("Modules")."\n"; }

  // generate physician db table
  $result=$sql->query("DROP TABLE physician");
  $result=$sql->query("CREATE TABLE physician (
    phylname     VARCHAR(52),
    phyfname     VARCHAR(50),
    phytitle     CHAR(10),
    phymname     VARCHAR(50),
    phypracname  CHAR(30),
    phyaddr1a    CHAR(30),
    phyaddr2a    CHAR(30),
    phycitya     CHAR(20),
    phystatea    CHAR(5),
    phyzipa      CHAR(10),
    phyphonea    VARCHAR(16),
    phyfaxa      VARCHAR(16),
    phyaddr1b    CHAR(30),
    phyaddr2b    CHAR(30),
    phycityb     CHAR(20),
    phystateb    CHAR(5),
    phyzipb      CHAR(10),
    phyphoneb    VARCHAR(16),
    phyfaxb      VARCHAR(16),
    phyemail     CHAR(30),
    phycellular  CHAR(10),
    phypager     CHAR(10),
    phyupin      CHAR(15),
    physsn       CHAR(9),
    phydeg1      INT UNSIGNED,
    phydeg2      INT UNSIGNED,
    phydeg3      INT UNSIGNED,
    physpe1      INT UNSIGNED,
    physpe2      INT UNSIGNED,
    physpe3      INT UNSIGNED,
    phyid1       CHAR(10),
    phystatus    INT UNSIGNED,
    phyref       ENUM(\"yes\",\"no\") NOT NULL,
    phyrefcount  INT UNSIGNED,
    phyrefamt    REAL,
    phyrefcoll   REAL,
    phychargemap TEXT,
    phyidmap     TEXT,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)    
    )");
  if ($result) { echo "<LI>"._("Physicians")."\n"; }

  // generate icd9 code table
  $result=$sql->query("DROP TABLE icd9"); 
  $result=$sql->query("CREATE TABLE icd9 (
    icd9code     VARCHAR(6),
    icd10code    VARCHAR(7),
    icd9descrip  VARCHAR(45),
    icd10descrip VARCHAR(45),
    icdmetadesc  VARCHAR(30),
    icdng        DATE,
    icddrg       TEXT,
    icdnum       INT UNSIGNED,
    icdamt       REAL,
    icdcoll      REAL,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)    
    )");
  if ($result) { echo "<LI>"._("ICD Codes")."\n"; }

  // generate patient database
  $result=$sql->query("DROP TABLE patient");
  $result=$sql->query("CREATE TABLE patient (
    ptdtadd      DATE,
    ptdtmod      DATE,
    ptbal        REAL,
    ptbalfwd     REAL,
    ptunapp      REAL,
    ptdoc        INT UNSIGNED,
    ptrefdoc     INT UNSIGNED,
    ptpcp        INT UNSIGNED,
    ptphy1       INT UNSIGNED,
    ptphy2       INT UNSIGNED,
    ptphy3       INT UNSIGNED,
    ptphy4       INT UNSIGNED,
    ptbilltype   ENUM(\"sta\",\"mon\",\"chg\") NOT NULL,
    ptbudg       REAL,
    ptlname      VARCHAR(50),
    ptfname      VARCHAR(50),
    ptmname      VARCHAR(50),
    ptaddr1      VARCHAR(45),
    ptaddr2      VARCHAR(45),
    ptcity       VARCHAR(45),
    ptstate      VARCHAR(20),
    ptzip        CHAR(10),
    ptcountry    VARCHAR(50),
    pthphone     VARCHAR(16),
    ptwphone     VARCHAR(16),
    ptfax        VARCHAR(16),
    ptemail      VARCHAR(80),
    ptsex        ENUM(\"m\",\"f\",\"t\") NOT NULL,
    ptdob        DATE,
    ptssn        VARCHAR(9),
    ptdmv        VARCHAR(15),
    ptdtlpay     DATE,
    ptamtlpay    REAL,
    ptpaytype    INT UNSIGNED,
    ptdtbill     DATE,
    ptamtbill    REAL,
    ptstatus     INT UNSIGNED,
    ptytdchg     REAL,
    ptar         REAL,
    ptextinf     TEXT,
    ptdisc       REAL,
    ptdol        DATE,
    ptdiag1      INT UNSIGNED,
    ptdiag2      INT UNSIGNED,
    ptdiag3      INT UNSIGNED,
    ptdiag4      INT UNSIGNED,
    ptid         VARCHAR(10),
    pthistbal    REAL,
    ptmarital    ENUM(\"single\",\"married\",
                      \"divorced\", \"separated\",
                      \"widowed\") NOT NULL,
    ptempl       ENUM(\"y\",\"n\") NOT NULL,
    ptemp1       INT UNSIGNED,
    ptemp2       INT UNSIGNED,
    ptguar       TEXT,
    ptrelguar    TEXT,
    ptguarstart  TEXT,
    ptguarend    TEXT,
    ptins        TEXT,
    ptinsno      TEXT,
    ptinsgrp     TEXT,
    ptinsstart   TEXT,
    ptinsend     TEXT,
    ptnextofkin  TEXT,
    iso          VARCHAR(15),
    id INT NOT NULL AUTO_INCREMENT,
    ptdep        INT UNSIGNED,
    ptins1 	 INT UNSIGNED,
    ptins2  	 INT UNSIGNED,
    ptins3 	 INT UNSIGNED,
    ptreldep 	 CHAR(1),
    ptinsno1 	 VARCHAR(50),
    ptinsno2 	 VARCHAR(50),
    ptinsno3 	 VARCHAR(50),
    ptinsgrp1	 VARCHAR(50),
    ptinsgrp2	 VARCHAR(50),
    ptinsgrp3	 VARCHAR(50),
    PRIMARY KEY (id)    
    )");
  if ($result) { echo "<LI>"._("Patients")."\n"; }

  // generate payer database 
  $result=$sql->query("DROP TABLE payer");
  $result=$sql->query("CREATE TABLE payer (
    payerinsco        INT UNSIGNED,
    payerstartdt      TEXT,
    payerenddt        TEXT,
    payerpatient      INT UNSIGNED,
    payerpatientgrp   VARCHAR(50),
    payerpatientinsno VARCHAR(50),
    payertype         INT,
    payerstatus       INT,
    id                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
  )");
  if ($result) { echo "<LI>"._("Payer")."\n"; }

  // generate guarantor database 
  $result=$sql->query("DROP TABLE guarantor");
  $result=$sql->query("CREATE TABLE guarantor (
    guarpatient       INT UNSIGNED,
    guarguar          INT UNSIGNED,
    guarrel	      CHAR(1),
    guarstartdt       TEXT,
    guarenddt         TEXT,
    guarstatus        INT,
    id                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
  )");
  if ($result) { echo "<LI>"._("Guarantor")."\n"; }

  // generate proc database (second generation)
  $result=$sql->query("DROP TABLE procrec");
  $result=$sql->query("CREATE TABLE procrec (
    procpatient            INT UNSIGNED NOT NULL,
    proceoc                TEXT,
    proccpt                INT UNSIGNED,
    proccptmod             INT UNSIGNED,
    procdiag1              INT UNSIGNED,
    procdiag2              INT UNSIGNED,
    procdiag3              INT UNSIGNED,
    procdiag4              INT UNSIGNED,
    proccharges            REAL,
    procunits              REAL,
    procvoucher            VARCHAR(25),
    procphysician          INT UNSIGNED,
    procdt                 DATE,
    procpos                INT UNSIGNED,
    proccomment            TEXT,
    procbalorig            REAL,
    procbalcurrent         REAL,
    procamtpaid            REAL,
    procbilled             INT UNSIGNED,
    procbillable           INT UNSIGNED,
    procauth               INT UNSIGNED,
    procrefdoc             INT UNSIGNED,
    procrefdt              DATE,
    id INT NOT NULL AUTO_INCREMENT,
    procamtallowed         REAL,
    procdtbilled	       TEXT,
    KEY (procpatient),
    PRIMARY KEY (id)
    )");
  if ($result) { echo "<LI>"._("Procedures")."\n"; }

  // generate facility database
  $result=$sql->query("DROP TABLE facility"); 
  $result=$sql->query("CREATE TABLE facility (
    psrname      CHAR(25),
    psraddr1     CHAR(25),
    psraddr2     CHAR(25),
    psrcity      CHAR(15),
    psrstate     CHAR(3),
    psrzip       CHAR(10),
    psrcountry   VARCHAR(50),
    psrnote      VARCHAR(40),
    psrdateentry DATE,
    psrdefphy    INT UNSIGNED,
    psrphone     VARCHAR(16),
    psrfax       VARCHAR(16),
    psremail     VARCHAR(25),
    psrein       VARCHAR(9),
    psrintext    INT UNSIGNED,
    id INT NOT NULL AUTO_INCREMENT,
	psrpos       INT UNSIGNED,
    PRIMARY KEY (id) )");
  if ($result) echo "<LI>"._("Facility")."\n"; 

  if ($re_load)
  {
  	$result=$sql->query("INSERT INTO facility VALUES (
   	'Default Facility', '', '', '', '', '', '', '', '$cur_date',
   	'', '', '', '', NULL )");
  	if ($result) echo "<I>("._("default facility added").")</I> \n"; 
  }

  // generate room database
  $result=$sql->query("DROP TABLE room"); 
  $result=$sql->query("CREATE TABLE room (
    roomname     CHAR(20),
    roompos      INT UNSIGNED,
    roomdescrip  CHAR(40),
    roomdefphy   INT UNSIGNED,
    roomsurgery  ENUM(\"y\", \"n\") NOT NULL,
    roombooking  ENUM(\"y\", \"n\") NOT NULL,
    roomipaddr   VARCHAR(15),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)    
    )");
  if ($result) echo "<LI>"._("Rooms")."\n"; 

  if ($re_load)
  {
  	$result=$sql->query("INSERT INTO room VALUES (
   	'Default Room', '1', '', '', '', '', '', NULL) ");
  	if ($result) echo "<I>("._("default room added").")</I> \n";
  }

  // generate degrees database
  $result=$sql->query("DROP TABLE degrees");
  $result=$sql->query("CREATE TABLE degrees (
    degdegree     CHAR(10),
    degname       VARCHAR(50),
    degdate       DATE,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)    
    )");
  if ($result) echo "<LI>"._("Degrees")."\n"; 
	
  if ($re_load)
  {
  	if (freemed_import_stock_data ("degrees"))
    		echo "<I>("._("Stock Degree Data").")</I> \n";
  }

  // generate specialties database
  $result=$sql->query("DROP TABLE specialties"); 
  $result=$sql->query("CREATE TABLE specialties (
    specname      VARCHAR(50),
    specdesc      VARCHAR(100),
    specdatestamp DATE,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Specialties")."\n";

  if ($re_load)
  {
  	if (freemed_import_stock_data ("specialties"))
    		echo "<I>("._("Stock Specialties Data").")</I> \n";
  }

  // generate insurance company database
  $result=$sql->query("DROP TABLE insco"); 
  $result=$sql->query("CREATE TABLE insco (
    inscodtadd   DATE,
    inscodtmod   DATE,
    insconame    VARCHAR(50) NOT NULL,
    inscoalias   VARCHAR(30),
    inscoaddr1   VARCHAR(45),
    inscoaddr2   VARCHAR(45),
    inscocity    VARCHAR(30),
    inscostate   CHAR(3),
    inscozip     CHAR(10),
    inscophone   VARCHAR(16),
    inscofax     VARCHAR(16),
    inscocontact VARCHAR(100),
    inscoid      CHAR(20),
    inscowebsite VARCHAR(100),
    inscoemail   VARCHAR(50),
    inscogroup   INT UNSIGNED,
    inscotype    INT UNSIGNED,
    inscoassign  INT UNSIGNED,
    inscomod     TEXT,
    id INT NOT NULL AUTO_INCREMENT,
    KEY (insconame),
    PRIMARY KEY (id)    
    )");
  if ($result) echo "<LI>"._("Insurance Companies")."\n"; 

  if ($re_load)
  {
  	if (freemed_import_stock_data ("insco"))
    		echo "<I>("._("Stock Insurance Company Data").")</I> \n";
  }

  // generate insurance company groups db
  $result=$sql->query("DROP TABLE inscogroup"); 
  $result=$sql->query("CREATE TABLE inscogroup (
    inscogroup     VARCHAR(50),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)    
    )");
  if ($result) echo "<LI>"._("Insurance Company Groups")."\n"; 

  if ($re_load)
  {
  	if (freemed_import_stock_data ("inscogroup"))
    		echo "<I>("._("Stock Insurance Company Group Data").")</I> \n";
  }

  // generate CPT (procedural) codes database
  $result=$sql->query("DROP TABLE cpt");
  $result=$sql->query("CREATE TABLE cpt (
    cptcode        CHAR(7),
    cptnameint     VARCHAR(50),
    cptnameext     VARCHAR(50),
    cptgender      ENUM(\"n\", \"m\", \"f\") NOT NULL,
    cpttaxed       ENUM(\"n\", \"y\") NOT NULL,
    cpttype        INT UNSIGNED,
    cptreqcpt      TEXT,
    cptexccpt      TEXT,
    cptreqicd      TEXT,
    cptexcicd      TEXT,
    cptrelval      REAL,
    cptdeftos      INT UNSIGNED,
    cptdefstdfee   REAL,
    cptstdfee      TEXT,
    cpttos         TEXT,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("CPT Codes")."\n";
		
  if ($re_load)
  {
  	if (freemed_import_stock_data ("cpt"))
    		echo "<I>("._("Stock CPT Code Data").")</I> \n";
  }

  // generate cpt modifier db
  $result=$sql->query("DROP TABLE cptmod");
  $result=$sql->query("CREATE TABLE cptmod (
    cptmod         CHAR(2),
    cptmoddescrip  VARCHAR(50),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("CPT Modifers")."\n";

  if ($re_load)
  {
  	if (freemed_import_stock_data ("cptmod"))
    		echo "<I>("._("Stock CPT Modifier Data").")</I> \n";
  }

  // generate physician groups db
  $result=$sql->query("DROP TABLE phygroup");
  $result=$sql->query("CREATE TABLE phygroup (
    phygroupname   VARCHAR(100),
    phygroupfac    INT UNSIGNED,
    phygroupdtadd  DATE,
    phygroupdtmod  DATE,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Physician Groups")."\n";

  // generate user db
  $result=$sql->query("DROP TABLE user"); 
  $result=$sql->query("CREATE TABLE user (
    username       VARCHAR(16) NOT NULL,
    userpassword   VARCHAR(16) NOT NULL,
    userdescrip    VARCHAR(50),
    userlevel      INT UNSIGNED,
    usertype       ENUM (\"phy\", \"misc\") NOT NULL,
    userfac        BLOB,
    userphy        BLOB,
    userphygrp     BLOB,
    userrealphy    INT UNSIGNED,
    id INT(32) UNSIGNED NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id),
    UNIQUE idx_id (id),
    KEY (username),
    UNIQUE idx_username (username)
    )");
  if ($result) echo "<LI>"._("Users")."\n";

  if ($re_load)
  {
  	$result=$sql->query("INSERT INTO user VALUES (
    	'root', '$db_password', 'Superuser', '9', '', '-1', '-1', '-1',
    	'', NULL )");
  	if ($result) echo "<I>[["._("Added Superuser")."]]</I> \n";
  }

  // generate scheduler table
  $result=$sql->query("DROP TABLE scheduler"); 
  $result=$sql->query("CREATE TABLE scheduler (
    caldateof         DATE,
    caltype           ENUM (\"temp\", \"pat\") NOT NULL,
    calhour           INT UNSIGNED,
    calminute         INT UNSIGNED,
    calduration       INT UNSIGNED,
    calfacility       INT UNSIGNED,
    calroom           INT UNSIGNED,
    calphysician      INT UNSIGNED,
    calpatient        INT UNSIGNED,
    calcptcode        INT UNSIGNED,
    calstatus         INT UNSIGNED,
    calprenote        VARCHAR(100),
    calpostnote       TEXT,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Scheduler")."\n";

  // generate physician availability map
  $result=$sql->query("DROP TABLE phyavailmap");
  $result=$sql->query("CREATE TABLE phyavailmap (
    pamdatefrom      DATE,
    pamdateto        DATE,
    pamtimefromhour  INT UNSIGNED,
    pamtimefrommin   INT UNSIGNED,
    pamtimetohour    INT UNSIGNED,
    pamtimetomin     INT UNSIGNED,
    pamphysician     INT UNSIGNED,
    pamcomment       VARCHAR(75),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Physician Availability Map")."\n";

  // generate insurance company groups db
  $result=$sql->query("DROP TABLE phystatus"); 
  $result=$sql->query("CREATE TABLE phystatus (
    phystatus      VARCHAR(30),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)    
    )");
  if ($result) echo "<LI>"._("Physician Statuses")."\n";

  // generate progress notes db (19990707)
  // * 19991228 - add iso
  $result=$sql->query("DROP TABLE pnotes"); 
  $result=$sql->query("CREATE TABLE pnotes (
    pnotesdt       DATE,
    pnotesdtadd    DATE,
    pnotesdtmod    DATE,
    pnotespat      INT UNSIGNED,
    pnotesdescrip  VARCHAR(100),
    pnotesdoc      INT UNSIGNED,
    pnoteseoc      TEXT,
    pnotes_S       TEXT,
    pnotes_O       TEXT,
    pnotes_A       TEXT,
    pnotes_P       TEXT,
    pnotes_I       TEXT,
    pnotes_E       TEXT,
    pnotes_R       TEXT,
    iso            VARCHAR(15),
    id INT NOT NULL AUTO_INCREMENT, 
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Progress Notes")."\n";

  // generate payment record db
  $result=$sql->query("DROP TABLE payrec"); 
  $result=$sql->query("CREATE TABLE payrec (
    payrecdtadd   DATE,
    payrecdtmod   DATE,
    payrecpatient INT UNSIGNED NOT NULL,
    payrecdt      DATE,
    payreccat     INT UNSIGNED,
    payrecproc    INT UNSIGNED,
    payrecsource  INT UNSIGNED,
    payreclink    INT UNSIGNED,
    payrectype    INT UNSIGNED,
    payrecnum     VARCHAR(100),
    payrecamt     REAL,
    payrecdescrip TEXT,
    payreclock    ENUM (\"unlocked\", \"locked\") NOT NULL,
    id INT NOT NULL AUTO_INCREMENT,
    KEY (payrecpatient),
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Payment Records")."\n";

  // generate formulary database
  $result=$sql->query("DROP TABLE frmlry"); 
  $result=$sql->query("CREATE TABLE frmlry (
    frmlrydtadd    DATE,
    frmlrydtmod    DATE,
    class          VARCHAR(20),
    gnrcname       VARCHAR(20),
    trdmrkname     VARCHAR(20),
    ind1           VARCHAR(50),
    ind2           VARCHAR(50),
    ind3           VARCHAR(50),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Formulary")."\n";

  if ($re_load)
  {
  	if (freemed_import_stock_data ("frmlry"))
    		echo "<I>("._("Stock Formulary Data").")</I> \n";
  }

  // Rx (prescription) database
  $result=$sql->query("DROP TABLE rx"); 
  $result=$sql->query("CREATE TABLE rx (
    rxdtadd        DATE,
    rxdtmod        DATE,
    rxpatient      INT UNSIGNED,
    rxdtfrom       DATE,
    rxduration     INT UNSIGNED,
    rxdrug         INT UNSIGNED,
    rxdosage       VARCHAR (100),
    rxrefills      INT UNSIGNED,
    rxsubstitute   VARCHAR (30),
    rxmd5sum       VARCHAR (50),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Prescriptions")."\n";

  // generate simple reports table
  $result=$sql->query("DROP TABLE simplereport");
  $result=$sql->query("CREATE TABLE simplereport (
    sr_label       VARCHAR(50),
    sr_type        INT UNSIGNED,
    sr_text        TEXT,
    sr_textf       TEXT,
    sr_textcm      TEXT,
    sr_textcf      TEXT,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Simple Reports")."\n";

  // generate call-in table/db
  $result=$sql->query("DROP TABLE callin");
  $result=$sql->query("CREATE TABLE callin (
    cilname        VARCHAR(50),
    cifname        VARCHAR(50),
    cimname        VARCHAR(50),
    cihphone       VARCHAR(16),
    ciwphone       VARCHAR(16),
    cidob          DATE,
    cicomplaint    TEXT,
    cidatestamp    DATE,
    cifacility     INT UNSIGNED,
    ciphysician    INT UNSIGNED,
    citookcall     VARCHAR(50),
    cipatient      INT UNSIGNED,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Call-Ins")."\n ";

  // generate room equipment inventory db
  $result=$sql->query("DROP TABLE roomequip"); 
  $result=$sql->query("CREATE TABLE roomequip (
    reqname         VARCHAR(100),
    reqdescrip      TEXT,
    reqdateadd      DATE,
    reqdatemod      DATE,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Room Equipment")."\n ";

  if ($re_load)
  {
  	if (freemed_import_stock_data("roomequip"))
    		echo "<I>("._("Stock Room Equipment Data").")</I> \n ";
  }

  // generate type of service db
  $result=$sql->query("DROP TABLE tos");
  $result=$sql->query("CREATE TABLE tos (
    tosname        VARCHAR(75),
    tosdescrip     VARCHAR(200),
    tosdtadd       DATE,
    tosdtmod       DATE,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Type of Service")."\n ";

  if ($re_load)
  {
  	if (freemed_import_stock_data("tos"))
    		echo "<I>("._("Stock Type of Service Data").")</I> \n ";
  }

  // generate place of service db required for x12
  $result=$sql->query("DROP TABLE pos");
  $result=$sql->query("CREATE TABLE pos (
    posname        VARCHAR(75),
    posdescrip     VARCHAR(200),
    posdtadd       DATE,
    posdtmod       DATE,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Place of Service")."\n ";

  if ($re_load)
  {
  	if (freemed_import_stock_data("pos"))
    		echo "<I>("._("Stock Place of Service Data").")</I> \n ";
  }

  // generate internal service types db
  $result=$sql->query("DROP TABLE intservtype"); 
  $result=$sql->query("CREATE TABLE intservtype (
    intservtype    VARCHAR(50),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Internal Service Types")."\n ";

  if ($re_load)
  {
  	if (freemed_import_stock_data("intservtype"))
    		echo "<I>("._("Stock Internal Service Types Data").")</I> \n";
  }

  // generate patient record template (custom) db
  $result=$sql->query("DROP TABLE patrectemplate"); 
  $result=$sql->query("CREATE TABLE patrectemplate (
    prtname        VARCHAR(100),
    prtdescrip     VARCHAR(100),
    prtfname       TEXT,
    prtvar         TEXT,
    prtftype       TEXT,
    prtftypefor    TEXT,
    prtfmaxlen     TEXT,
    prtfcom        TEXT,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Patient Record Templates")."\n";

  if ($re_load)
  {
  	if (freemed_import_stock_data("patrectemplate"))
    		echo "<I>("._("Stock Patient Record Template Data").")</I> \n";
  }

  // generate questionnaire template (custom) db
  $result=$sql->query("DROP TABLE qtemplate"); 
  $result=$sql->query("CREATE TABLE qtemplate (
    qname          VARCHAR(100),
    qdescrip       VARCHAR(100),
    qfname         TEXT,
    qvar           TEXT,
    qftype         TEXT,
    qftypefor      TEXT,
    qfmaxlen       TEXT,
    qftext         TEXT,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Questionnaire Templates")."\n";

  if ($re_load)
  {
  	 if (freemed_import_stock_data("qtemplate"))
    		echo "<I>("._("Stock Questionnaire Template Data").")</I> \n";
  } 
  // generate diagnosis family db
  $result=$sql->query("DROP TABLE diagfamily"); 
  $result=$sql->query("CREATE TABLE diagfamily (
    dfname         VARCHAR(100),
    dfdescrip      VARCHAR(100),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Diagnosis Families")."\n";

  if ($re_load)
  {
  	if (freemed_import_stock_data("diagfamily"))
    		echo "<I>("._("Stock Diagnosis Family Data").")</I> \n";
  }
  // generate patient statuses
  $result=$sql->query("DROP TABLE ptstatus"); 
  $result=$sql->query("CREATE TABLE ptstatus (
    ptstatus         CHAR(3),
    ptstatusdescrip  VARCHAR(30),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Patient Statuses")."\n";

  if ($re_load)
  {
  	if (freemed_import_stock_data("ptstatus"))
    		echo "<I>("._("Stock Patient Statuses Data").")</I> \n";
  }
  // generate patient record data (custom) db
  $result=$sql->query("DROP TABLE patrecdata"); 
  $result=$sql->query("CREATE TABLE patrecdata (
    prpatient      INT UNSIGNED NOT NULL,
    prtemplate     INT UNSIGNED,
    prdtadd        DATE,
    prdtmod        DATE,
    prdata         TEXT,
    id INT NOT NULL AUTO_INCREMENT,
    KEY (prpatient),
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Patient Record Data")."\n";

  // generate action log table db
  $result=$sql->query("DROP TABLE log"); 
  $result=$sql->query("CREATE TABLE log (
    datestamp      DATE,
    user           INT UNSIGNED,
    db_name        VARCHAR(20),
    rec_num        INT UNSIGNED,
    comment        TEXT,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    )");
  if ($result) echo "<LI>"._("Action Log")."\n";

  // generate configuration table info
  $result=$sql->query("DROP TABLE config"); 
  $result=$sql->query("CREATE TABLE config (
    c_option       CHAR(6),
    c_value        VARCHAR(100),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)    
    )");
  if ($result) echo "<LI>"._("Configuration")."\n";

  if ($re_load)
  {
  	if ($sql->query("INSERT INTO config VALUES (
    	'icd', '9', NULL )"))   if ($debug)   echo "(ICD) \n";
  	if ($sql->query("INSERT INTO config VALUES (
    	'gfx', '1', NULL )"))   if ($debug)   echo "(graphics) \n";
  	if ($sql->query("INSERT INTO config VALUES (
    	'calshr', '$cal_starting_hour', NULL )")) if ($debug) echo "(calshr) \n";
  	if ($sql->query("INSERT INTO config VALUES (
    	'calehr', '$cal_ending_hour', NULL )")) if ($debug) echo "(calehr) \n";
  	if ($sql->query("INSERT INTO config VALUES (
    	'cal_ob', 'enable', NULL )")) if ($debug) echo "(cal_ob) \n";
  	if ($sql->query("INSERT INTO config VALUES (
    	'dtfmt', 'ymd', NULL )")) if ($debug) echo "(dtfmt) \n";
  	if ($sql->query("INSERT INTO config VALUES (
    	'phofmt', 'unformatted', NULL )")) if ($debug) echo "(phofmt) \n";
  }

  // generate incoming faxes table
  $result=$sql->query("DROP TABLE infaxes"); 
  $result=$sql->query("CREATE TABLE infaxes (
    infcode	  VARCHAR(5),  
    infsender	  VARCHAR(50),
    inftotpages	  INT UNSIGNED,
    infthispage	  INT UNSIGNED,
    inftimestamp  TIMESTAMP,
    infimage	  VARCHAR(50),
    inforward	  ENUM(\"no\",\"yes\") NOT NULL,		
    infack	  ENUM(\"no\",\"yes\") NOT NULL,
    infptid	  VARCHAR(10),
    infphysid	  VARCHAR(10),
    id            INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    );");
   if ($result) echo "<LI>"._("Incoming Faxes")."\n";

  // generate fax sender lookup table
  $result=$sql->query("DROP TABLE infaxlut"); 
  $result=$sql->query("CREATE TABLE infaxlut (
    lutsender VARCHAR(50),
    lutname   VARCHAR(50),
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
    );");
  if ($result) echo "<LI>"._("Fax Sender Lookup")."\n";

   // generate printers table (19991008)
   $result = $sql->query ("DROP TABLE printer"); 
   $result = $sql->query ("CREATE TABLE printer (
     prntname   VARCHAR(50),
     prnthost   VARCHAR(50),
     prntaclvl  ENUM(\"9\",\"8\",\"7\",\"6\",\"5\",\"4\",\"3\",\"2\",\"1\",\"0\") NOT NULL,
     prntdesc   VARCHAR(100),
     id         INT NOT NULL AUTO_INCREMENT,
     PRIMARY KEY (id)
     );");
   if ($result) echo "<LI>"._("Printers")."\n";

   // generate fixed form table (19991020)
  $result = $sql->query ("DROP TABLE fixedform");
  $result = $sql->query ("CREATE TABLE fixedform (
     ffname        VARCHAR(50),
     ffdescrip     VARCHAR(100),
     fftype        INT UNSIGNED,
     ffpagelength  INT UNSIGNED,
     fflinelength  INT UNSIGNED,
     ffloopnum     INT UNSIGNED,
     ffloopoffset  INT UNSIGNED,
     ffcheckchar   CHAR(1),
     ffrow         TEXT,
     ffcol         TEXT,
     fflength      TEXT,
     ffdata        TEXT,
     ffformat      TEXT,
     ffcomment     TEXT,
     id            INT NOT NULL AUTO_INCREMENT,
     PRIMARY KEY (id)
     );");
  if ($result) echo "<LI>"._("Fixed Forms")."\n";

  if ($re_load)
  {
  	if (freemed_import_stock_data("fixedform"))
    		echo "<I>("._("Stock Fixed Forms Data").")</I> \n";
  }
  // episode of care
  $result = $sql->query ("DROP TABLE eoc"); 
  $result = $sql->query ("CREATE TABLE eoc (
     eocpatient                INT UNSIGNED NOT NULL,
     eocdescrip                VARCHAR(100),
     eocstartdate              DATE,
     eocdtlastsimilar          DATE,
     eocreferrer               INT UNSIGNED,
     eocfacility               INT UNSIGNED,
     eocdiagfamily             TEXT,
     eocrelpreg                ENUM (\"no\", \"yes\") NOT NULL,
     eocrelemp                 ENUM (\"no\", \"yes\") NOT NULL,
     eocrelauto                ENUM (\"no\", \"yes\") NOT NULL,
     eocrelother               ENUM (\"no\", \"yes\") NOT NULL,
     eocrelstpr                VARCHAR(10),
     eoctype                   ENUM (\"acute\",
                                     \"chronic\",
                                     \"chronic recurrent\",
                                     \"historical\") NOT NULL,
     eocrelautoname            VARCHAR(100),
     eocrelautoaddr1           VARCHAR(100),
     eocrelautoaddr2           VARCHAR(100),
     eocrelautocity            VARCHAR(50),
     eocrelautostpr            VARCHAR(30),
     eocrelautozip             VARCHAR(10),
     eocrelautocountry         VARCHAR(100),
     eocrelautocase            VARCHAR(30),
     eocrelautorcname          VARCHAR(100),
     eocrelautorcphone         VARCHAR(16),
     eocrelempname             VARCHAR(100),
     eocrelempaddr1            VARCHAR(100),
     eocrelempaddr2            VARCHAR(100),
     eocrelempcity             VARCHAR(50),
     eocrelempstpr             VARCHAR(30),
     eocrelempzip              VARCHAR(10),
     eocrelempcountry          VARCHAR(100),
     eocrelempfile             VARCHAR(30),
     eocrelemprcname           VARCHAR(100),
     eocrelemprcphone          VARCHAR(16),
     eocrelemprcemail          VARCHAR(100),
     eocrelpregcycle           INT UNSIGNED,
     eocrelpreggravida         INT UNSIGNED,
     eocrelpregpara            INT UNSIGNED,
     eocrelpregmiscarry        INT UNSIGNED,
     eocrelpregabort           INT UNSIGNED,
     eocrelpreglastper         DATE,
     eocrelpregconfine         DATE,
     eocrelothercomment        VARCHAR(100),
     id                        INT NOT NULL AUTO_INCREMENT,
     KEY (eocpatient),
     PRIMARY KEY (id)
     );");
  if ($result) echo "<LI>"._("Episode of Care")."\n";

  // old reports
  $result = $sql->query ("DROP TABLE oldreports"); 
  $result = $sql->query ("CREATE TABLE oldreports (
     oldrep_timestamp          DATE,
     oldrep_label              VARCHAR(50),
     oldrep_type               INT UNSIGNED,
     oldrep_sender             INT UNSIGNED,
     oldrep_delivery           VARCHAR(20),
     oldrep_author             INT UNSIGNED,
     oldrep_dateline           VARCHAR(100),
     oldrep_header1            VARCHAR(100),
     oldrep_header2            VARCHAR(100),
     oldrep_header3            VARCHAR(100),
     oldrep_header4            VARCHAR(100),
     oldrep_header5            VARCHAR(100),
     oldrep_header6            VARCHAR(100),
     oldrep_header7            VARCHAR(100),
     oldrep_dest1              VARCHAR(100),
     oldrep_dest2              VARCHAR(100),
     oldrep_dest3              VARCHAR(100),
     oldrep_dest4              VARCHAR(100),
     oldrep_signature1         VARCHAR(100),
     oldrep_signature2         VARCHAR(100),
     oldrep_text               TEXT,
     id                        INT NOT NULL AUTO_INCREMENT,
     PRIMARY KEY (id)
     );");
  if ($result) echo "<LI>"._("Old Reports")."\n";

  // patient image database
  $result = $sql->query ("DROP TABLE patimg");
  $result = $sql->query ("CREATE TABLE patimg (
     pipatient                 INT UNSIGNED NOT NULL,
     pilink                    INT UNSIGNED,
     pidate                    INT UNSIGNED,
     pitype                    ENUM (\"picture\", \"xray\") NOT NULL,
     pidata                    BLOB, 
     id                        INT NOT NULL AUTO_INCREMENT,
     KEY (pipatient),
     PRIMARY KEY (id)
     );");
  if ($result) echo "<LI>"._("Patient Images")."\n";

  // authorizations
  $result = $sql->query ("DROP TABLE authorizations"); 
  $result = $sql->query ("CREATE TABLE authorizations (
     authdtadd                 DATE,
     authdtmod                 DATE,
     authpatient               INT UNSIGNED NOT NULL,
     authdtbegin               DATE,
     authdtend                 DATE,
     authnum                   VARCHAR(25),
     authtype                  INT UNSIGNED,
     authprov                  INT UNSIGNED,
     authprovid                VARCHAR(20),
     authinsco                 INT UNSIGNED,
     authvisits                INT UNSIGNED,
     authvisitsused            INT UNSIGNED,
     authvisitsremain          INT UNSIGNED,
     authcomment               VARCHAR(100),
     id                        INT NOT NULL AUTO_INCREMENT,
     KEY (authpatient),
     PRIMARY KEY (id)
     );");
  if ($result) echo "<LI>"._("Authorizations")."\n";

  // insurance modifiers table
  $result = $sql->query ("DROP TABLE insmod"); 
  $result = $sql->query ("CREATE TABLE insmod (
     insmod                    VARCHAR(15),
     insmoddesc                VARCHAR(50),
     id                        INT NOT NULL AUTO_INCREMENT,
     PRIMARY KEY (id)
     );");
  if ($result) echo "<LI>"._("Insurance Modifiers")."\n";

  if ($re_load)
  {
  	if (freemed_import_stock_data("insmod"))
    		echo "<I>("._("Stock Insurance Modifiers").")</I> \n";
  }
  echo "</UL><B>"._("done").".</B><$STDFONT_E><BR>\n";
  
  // now generate "return code" so that we can get back to the
  // admin menu... or perhaps skip that... ??

  echo "
    <BR><BR><CENTER>
    <A HREF=\"$page_name?$_auth\">
     <$STDFONT_B>"._("Return to Administration Menu")."<$STDFONT_E></A>
    </CENTER>
  ";

} else {

  // actual menu code for admin menu goes here \/

freemed_display_box_top(PACKAGENAME." "._("Administration Menu"), $_ref,
$page_name);

echo "
  <$STDFONT_B>
  <TABLE WIDTH=100% VALIGN=CENTER ALIGN=CENTER BORDER=0 CELLSPACING=2
   CELLPADDING=0>
 "; // begin standard font

$_userdata = explode (":", $LoginCookie);

echo "
 <TR><TD ALIGN=RIGHT BGCOLOR=#dddddd>
  <A HREF=\"export.php?$_auth\"
  ><IMG SRC=\"img/kfloppy.gif\" BORDER=0 ALT=\"[*]\"></A>
 </TD><TD ALIGN=LEFT>
  <A HREF=\"export.php?$_auth\"
  ><$STDFONT_B>"._("Export Databases")."<$STDFONT_E></A>
 </TD></TR> 
 <TR><TD ALIGN=RIGHT BGCOLOR=#dddddd>
  <A HREF=\"import.php?$_auth\"
  ><IMG SRC=\"img/ark.gif\" BORDER=0 ALT=\"[*]\"></A>
 </TD><TD ALIGN=LEFT>
 <A HREF=\"import.php?$_auth\"
 ><$STDFONT_B>"._("Import Databases")."<$STDFONT_E></A>
 </TD></TR>
";  

 echo "
    <TR><TD ALIGN=RIGHT BGCOLOR=#dddddd>
     <A HREF=\"module_information.php?$_auth\"
     ><IMG SRC=\"img/magnify.gif\" BORDER=0 ALT=\"[*]\"></A>
    </TD><TD ALIGN=LEFT>
    <A HREF=\"module_information.php?$_auth\"
     ><$STDFONT_B>"._("Module Information")."<$STDFONT_E></A>
    </TD></TR>
 ";

if ($_userdata[0]==1) // if we are root...
 echo "
  <TR><TD ALIGN=RIGHT BGCOLOR=#dddddd>
   <A HREF=\"$page_name?$_auth&action=reinit\"
   ><IMG SRC=\"img/Gear.gif\" BORDER=0 ALT=\"[*]\"></A>
  </TD><TD ALIGN=LEFT>
  <A HREF=\"$page_name?$_auth&action=reinit\"
  ><$STDFONT_B>"._("Reinitialize Database")."<$STDFONT_E></A>
  </TD></TR>
 ";

echo "
  <TR><TD ALIGN=RIGHT BGCOLOR=#dddddd>
   <A HREF=\"$page_name?$_auth&action=cfgform\"
   ><IMG SRC=\"img/config.gif\" BORDER=0 ALT=\"[*]\"></A>
  </TD><TD ALIGN=LEFT>
  <A HREF=\"$page_name?$_auth&action=cfgform\"
  ><$STDFONT_B>"._("Update Config")."<$STDFONT_E></A>
  </TD></TR>
";

if ($_userdata[0]==1)  // if we are root...
  echo "
    <TR><TD ALIGN=RIGHT BGCOLOR=#dddddd>
     <A HREF=\"user.php?$_auth&action=view\"
     ><IMG SRC=\"img/monalisa.gif\" BORDER=0 ALT=\"[*]\"></A>
    </TD><TD ALIGN=LEFT>
    <A HREF=\"user.php?$_auth&action=view\"
     ><$STDFONT_B>"._("User Maintenance")."<$STDFONT_E></A>
    </TD></TR>
  ";

  echo "
    <TR><TD ALIGN=RIGHT BGCOLOR=#dddddd>
     <A HREF=\"main.php?$_auth\"
     ><IMG SRC=\"img/HandPointingLeft.gif\" BORDER=0 ALT=\"[*]\"></A>
    </TD><TD ALIGN=LEFT>
     <A HREF=\"main.php?$_auth\"
     ><B><$STDFONT_B>"._("Return to the Main Menu")."<$STDFONT_E></B></A>
    </TD></TR>
    </TABLE><$STDFONT_E>
  "; // end standard font

  freemed_display_box_bottom ();
}

freemed_close_db(); // close up database

echo "
  <P>
  <$STDFONT_B>
  <CENTER>
  <A HREF=\"main.php?$_auth\">"._("Return to the Main Menu")."</A>
  </CENTER>
  <$STDFONT_E>
"; // return to main menu tab...

freemed_display_html_bottom (); // ending of document...
?>
