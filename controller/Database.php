<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 20.04.2015
 * Time: 16:22
 */
include("../datamodell/DBConnection.php");
include("../interfaces/DataSource.php");

class Database implements DataSource
{

    private $connection;

    function __construct()
    {
        $this->connection = new DBConnection("oci");
    }

    public function getListData()
    {
        //MySQL-Statement: $sql = "SELECT CONCAT(LEFT(PAT_VORNAME, 1), EXTRACT(DAY FROM PAT_GEBURTSDATUM), LEFT(PAT_NAME, 1), LEFT(idA_UNTBEH_UEB, 3)) as TICKET_NUMBER, b.UNTERS_NAME, b.ARBEITSPLATZ, DATE_FORMAT(b.UNTERS_BEGINN, '%d.%m.%y %h:%i') as UNTERS_BEGINN, DATE_FORMAT(IFNULL(b.UNTERS_BEGINN, a.ANMELDUNG_ANKUNFT), '%d.%m.%y %h:%i') as ANMELDUNG_ANKUNFT, TIMEDIFF(NOW(), b.UNTERS_BEGINN) as WAITING_TIME FROM a_untbeh_ueb b LEFT OUTER JOIN a_patient_basis p ON b.PATIENT_SCHLUESSEL=p.PATIENT_SCHLUESSEL INNER JOIN a_untbeh_ueb_alle a ON b.idA_UNTBEH_UEB=a.idA_UNTBEH_UEB_ALLE WHERE b.UNTERS_STATUS = 't'";
        $sql = "
SELECT

--Kürzel Generator--
  SUBSTR(P.PAT_VORNAME,1,1) ||
  SUBSTR(P.PAT_NAME,1,1) ||
  EXTRACT(DAY FROM P.PAT_GEBURTSDATUM) ||
  SUBSTR(B.PATIENT_SCHLUESSEL,8,3)
  AS TICKET_NUMBER,

  U.UNTART_NAME,
  B.ARBEITSPLATZ,
  TO_CHAR (B.TERMIN_DATUM, 'DD.MM.YYYY HH24:MI') as TERMIN_DATUM,
  TO_CHAR (B.UNTERS_BEGINN, 'DD.MM.YYYY HH24:MI') as UNTERS_BEGINN,
  TO_CHAR(B.ANMELDUNG_ANKUNFT,'DD.MM.YYYY HH24:MI') AS ANMELDUNG_ANKUNFT,

(CASE WHEN UNTERS_STATUS = 'b'
THEN 'Laufende Untersuchung'
 ELSE (
 CASE WHEN (
 termin_datum - systimestamp + (SELECT NUMTODSINTERVAL(NVL(SUM(ROUND(((UNTERS_BEGINN - TERMIN_DATUM)*24*60),0)), 0), 'MINUTE')
 FROM A_UNTBEH_UEB_ALLE
 WHERE UNTERS_STATUS = 'b'
 AND ARBEITSPLATZ = B.ARBEITSPLATZ
 AND ROWNUM = 1
 )) < (INTERVAL '0' MINUTE)
 THEN (TO_CHAR(extract(day from 24*60*(termin_datum - systimestamp))))
 ELSE (
 TO_CHAR(extract(day from 24 * 60 *(termin_datum - systimestamp +
 (SELECT NUMTODSINTERVAL(NVL(SUM(ROUND(((UNTERS_BEGINN - TERMIN_DATUM)*24*60),0)), 0), 'MINUTE')
 FROM A_UNTBEH_UEB_ALLE
 WHERE UNTERS_STATUS = 'b'
 AND ARBEITSPLATZ = B.ARBEITSPLATZ
 AND ROWNUM = 1)))))
 END)
 END) AS WARTEZEIT
FROM
  A_UNTBEH_UEB_ALLE B,
  A_PATIENT_BASIS P,
  A_UNTARTEN U

WHERE

--Joins--
  B.PATIENT_SCHLUESSEL=P.PATIENT_SCHLUESSEL
  and B.UNTERS_ART = u.UNTART_KUERZEL
---------

--Suchfilter--
  AND (B.UNTERS_STATUS = 'a'
  OR B.UNTERS_STATUS = 'b')
  AND ( B.TERMIN_DATUM between systimestamp-INTERVAL '4' HOUR and systimestamp+INTERVAL '4' HOUR)
  ORDER BY REPLACE(WARTEZEIT,'Laufende Untersuchung','-99999')*1
        ";
        $result = $this->connection->execute_statement($sql);
        return $result;
    }

    public function getWartezeit($device){
        //$sql = "SELECT SUM(ROUND(((UNTERS_BEGINN - TERMIN_DATUM)*24*60),0)) AS WARTEZEIT FROM A_UNTBEH_UEB_ALLE WHERE UNTERS_STATUS = 'u' AND ARBEITSPLATZ ='" .$device . "' AND ROWNUM=1";
        $sql = "SELECT TO_CHAR(NUMTODSINTERVAL(NVL(SUM(ROUND(((UNTERS_BEGINN - TERMIN_DATUM)*24*60),0)), 0), 'MINUTE'), 'MI:SS') AS WARTEZEIT  FROM A_UNTBEH_UEB_ALLE WHERE UNTERS_STATUS = 'b' AND ARBEITSPLATZ ='". $device . "' AND ROWNUM=1";
        $result = $this->connection->execute_statement($sql);
        return $result;
    }
}
