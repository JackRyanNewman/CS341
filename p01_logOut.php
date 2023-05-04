<?php 

/*  p1_logOut.php
/*	This page is used to log people out of their accounts
/*
/*	Authors: Jackson Mishuk
/*	Date Created: 04/14/2023
/*	Date Modified: 4/28/2023
*/

    session_start();
    unset($_SESSION['peopleID']);
    
?>