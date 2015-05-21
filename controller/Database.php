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

    public function getData()
    {
        //$sql = "SELECT CONCAT(LEFT(PAT_VORNAME, 1), EXTRACT(DAY FROM PAT_GEBURTSDATUM), LEFT(PAT_NAME, 1), LEFT(idA_UNTBEH_UEB, 3)) as TICKET_NUMBER, b.UNTERS_NAME, b.ARBEITSPLATZ, DATE_FORMAT(b.UNTERS_BEGINN, '%d.%m.%y %h:%i') as UNTERS_BEGINN, DATE_FORMAT(IFNULL(b.UNTERS_BEGINN, a.ANMELDUNG_ANKUNFT), '%d.%m.%y %h:%i') as ANMELDUNG_ANKUNFT, TIMEDIFF(NOW(), b.UNTERS_BEGINN) as WAITING_TIME FROM a_untbeh_ueb b LEFT OUTER JOIN a_patient_basis p ON b.PATIENT_SCHLUESSEL=p.PATIENT_SCHLUESSEL INNER JOIN a_untbeh_ueb_alle a ON b.idA_UNTBEH_UEB=a.idA_UNTBEH_UEB_ALLE WHERE b.UNTERS_STATUS = 't'";
        $sql = "
SELECT

--Kürzel Generator--
  SUBSTR(P.PAT_VORNAME,1,1) ||
  EXTRACT(DAY FROM P.PAT_GEBURTSDATUM) ||
  SUBSTR(P.PAT_NAME,1,1) ||
  SUBSTR(B.PATIENT_SCHLUESSEL,1,1)
  AS TICKET_NUMBER,

  --Konrolle--
  --p.pat_vorname,
  --p.pat_name,
  ------------

--------------------

  U.UNTART_NAME,
  B.ARBEITSPLATZ,

  TO_CHAR (B.UNTERS_BEGINN, 'DD:MM:YYYY:HH24:MI:SS') as UNTERS_BEGINN,
  TO_CHAR(NULLIF(B.UNTERS_BEGINN, B.ANMELDUNG_ANKUNFT),'DD:MM:YYYY:HH24:MI:SS') AS ANMELDUNG_ANKUNFT,

  (case
      when sysdate < b.TERMIN_DATUM
      then ''
      else
      lpad(trunc((sysdate-b.TERMIN_DATUM)*24), 2, '0') ||
      ':' ||
      lpad(abs(mod(floor((sysdate-b.TERMIN_DATUM)*24*60),60)), 2, '0')
  end) as WARTEZEIT

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
  AND B.UNTERS_STATUS = 'b'
  AND ( B.TERMIN_DATUM between trunc(sysdate) and trunc(sysdate+1) )
";
        $result = $this->connection->execute_statement($sql);
        return $result;
    }

/*    public function checkStatus()
    {

    }*/
}
