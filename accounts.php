<?php

// require_once('components/header.php');
if (!isset($_SESSION)) {
    session_start();
}
require_once('database/dbconn.php');

$accounts = new Accounts;
$accounts->init($_REQUEST);
$history = new historyLogs;
$history->initialize($_REQUEST);
class Accounts
{
    public $db_table = 'accounts';
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function init($params)
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $type = $params['requestType'];

        if (isset($type)) {
            switch ($type) {
                case 'login':
                    $this->Login($params);
                    break;
                case 'logout':
                    $logs = new historyLogs();
                    $logs->addLog($params);
                    break;
                default:
                    echo "ERROR101";
                    break;
            }
        }
    }

    public function getUsers()
    {
        $sql = "SELECT * from " . $this->db_table . "";
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query;
    }

    public function getCount()
    {
        $sql = "SELECT COUNT(*) as total FROM accounts";
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query;
    }

    public function Login($params)
    {
        // exit(print_r($params));

        $sql = "SELECT * from accounts
        WHERE 
        BINARY username = '" . $params['username'] . "'
        AND 
        BINARY password = '" . $params['password'] . "' LIMIT 1";
        // exit($sql);


        $query =  $this->conn->prepare($sql);
        // exit(print_r($query));

        if ($query->execute()) {



            $accountsArr = [];
            while ($result = $query->fetchAll(PDO::FETCH_ASSOC)) {
                if (count($result) > 0) {
                    foreach ($result as $row) :

                        if ($row['username'] == $params['username'] && $row['password'] == $params['password']) {

                            $_SESSION['accountInfo'] =  $row;

                            $logs = new historyLogs();
                            $logs->addLog($params);

                            echo "<script> window.location.assign('home.php?id=" . $row['id'] . "')</script>";
                        } else {
                            echo "ERROR";
                        }
                    endforeach;
                } else {
                    echo "NO result";
                }
            }
        }
    }
}

class historyLogs
{

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function initialize($args)
    {
        // print_r($args);

        $type = $args['request'];
        switch ($type) {
            case 'user':
                $this->showHistoryLogs($args);
                break;
        }
    }

    public function addLog($params)
    {

        // ini_set('log_errors', 1);
        $sql = "INSERT INTO historylogs 
        (user,event,dateNtime)
        VALUES 
        ('" . $params['username'] . "',
        '" . $params['action'] . "',
        '" . $params['dateNTime'] . "');";
        // exit(print_r($sql));
        $query = $this->conn->prepare($sql);

        if ($query->execute()) {
            // if()
            echo "<script> console.log('history log updated') </script>";
        } else {
            echo "<script> console.log('error updating') </script>";
        }
    }

    public function selectEvents()
    {
        $sql = "SELECT DISTINCT event FROM historylogs";
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query;
    }

    public function getDate($args)
    {
        $dateToday = $args;

        $sql = "SELECT dateNtime from historyLogs";
        $query = $this->conn->prepare($sql);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        foreach ($row as $date) :

            $date1 = strtotime($date["dateNtime"]);
            $array = array($date1);
        endforeach;
        $date2 = strtotime($dateToday);
        $diff = abs($date2 - $date1);
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24)
            / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
            $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24
            - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24)
            / (60 * 60));
        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24
            - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
            - $hours * 60 * 60) / 60);
        $oneYr = "year";
        $add = "s";
        $oneMonth = "month";
        $oneDay = "day";
        $oneHr = "hour";
        $oneMin = "minute";
        $info1 = array("$months", "$days", "$hours", "$minutes");
        $info = array("$oneYr", "$oneMonth", "$oneDay", "$oneHr", "$oneMin");

        $intArray = array_map(
            function ($value) {
                return (int)$value;
            },
            $info1
        );
        foreach ($intArray as $data) {
            foreach ($info as $item) {
                if ($data > 0) {
                    if ($data === 1) {
                        $result = $data;
                        $result .= $item;
                    } else {
                        $result = $data;
                        $result .= $item . $add;
                    }
                } else {
                    $result = "0";
                }
                $intervalFormat = array($result);
            }
        }

        return $intervalFormat;
    }

    public function showHistoryLogs($args)
    {
        function showData($sql, $value)
        {
            $history = new historyLogs();
            $date = array($history->getDate($value));
            exit(print_r($date));
            $database = new Database();
            $conn = $database->getConnection();

            $query = $conn->prepare($sql);
            $query->execute();
            $row = $query->fetchAll(PDO::FETCH_ASSOC);

            $temp = "<table class='historyLogs-table'>";
            $temp .= "<thead>";
            $temp .= "<tr>";
            $temp .= "<th class='id'>id</th>";
            $temp .= "<th>user</th>";
            $temp .= "<th>event</th>";
            $temp .= "<th>date and time</th></tr>";
            $temp .= "</thead>";
            $temp .= "<tbody>";
            foreach ($row as $data) :

                $temp .= "<tr >";

                $temp .= "<td class='id'>" . $data["id"] . "</td>";
                $temp .= "<td>" . $data["user"] . "</td>";
                $temp .= "<td>" . $data["event"] . "</td>";
                $temp .= "<td>" . $date . "</td>";
                $temp .= "</tr>";
            endforeach;
            $temp .= "</tbody>";
            $temp .= "</table>";
            echo $temp;
        }
        $params = $args['query'];

        if (isset($args['query'])) {
            switch ($params) {
                case 'all':
                    $sql = "SELECT * FROM historylogs";
                    showData($sql);
                    break;
                case 'userHistory':

                    $sql = "SELECT * FROM historylogs WHERE user ='" . $args['search'] . "' order by id DESC";
                    showData($sql);
                    break;
                case 'select':
                    if (isset($args['search']) && isset($args['search2'])) {
                        $sql = "SELECT id, dateNtime, event , user FROM historylogs WHERE 
                     user = '" . $args['searchUser'] . "' AND dateNtime BETWEEN '" . $args['search'] . "' 
                     AND '" . $args['search2'] . "'";
                        showData($sql);
                        // exit(print_r($sql));
                    } else {
                        echo "must select date and time";
                    }
                    break;
                case 'eventHistory':
                    // echo ("hi");
                    $sql = "SELECT * FROM historylogs WHERE event ='" . $args['search'] . "'";
                    showData($sql);
                    break;
                    break;
            }
        } else {
            $sql = "SELECT * FROM historylogs order by id DESC";

            $date = $args['date'];
            showData($sql, $date);
        }
    }
}
