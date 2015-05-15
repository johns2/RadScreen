<?php
$conn = oci_connect('radscreen', 'Pa$$w0rd', 'localhost:1521');
$query = 'select table_name from user_tables';
$stid = oci_parse($conn, $query);
oci_execute($stid, OCI_DEFAULT);
while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
foreach ($row as $item) {
echo $item." | ";
}
echo "
\n";
}
oci_free_statement($stid);
oci_close($conn);
?>