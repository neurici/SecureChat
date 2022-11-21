<?php

// number of messages to keep
const NB_MESSAGES_TO_KEEP = 10000;

// number of days to delete an idle chat room
const DAYS_TO_DELETE_IDLE_CHATROOM = 180;

// how many seconds before the user is considered timed out
const NB_SECONDS_USER_TO_BE_DISCONNECTED = 35000;

// choose the type of database you want to use
// MySQL 	= DATABASE_MYSQL
// SQLite 	= DATABASE_SQLITE
const DB_TYPE		= DATABASE_MYSQL;

// mysql database name
const DB_NAME 		= "schat";

// mysql server address
const DB_HOST 		= "localhost";

// mysql credentials
const DB_USER 		= "root";
const DB_PASSWORD 	= "root";

$allowedTimes = array(
    5 => '5 minute',
    30 => '30 minute',
    60 => '1 ora',
    240 => '4 ore',
    1440 => '1 zi',
    10080 => '7 zile',
    40320 => '30 zile',
    525960 => '1 an',
    0 => 'Nelimitat'
);
