<?php
function query($conn, string $q)
{
    $result = oci_parse($conn, $q);
    oci_execute($result);
    $numrows = oci_fetch_all($result, $res);
    oci_close($conn);
    return array($res, $numrows);
}
?>
