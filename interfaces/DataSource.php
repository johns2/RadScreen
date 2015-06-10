<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 20.04.2015
 * Time: 16:03
 */

interface DataSource {

    function getListData();
    function getWartezeit($device);
}