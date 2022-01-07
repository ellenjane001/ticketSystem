<?php

require_once('components/header.php');
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

    public function showHistoryLogs($args)
    {
        function showData($sql)
        {
            $database = new Database();
            $conn = $database->getConnection();
            $query = $conn->prepare($sql);
            $query->execute();
            $row = $query->fetchAll(PDO::FETCH_ASSOC);
            // exit(print_r($row));
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
                $temp .= "<td >" . $data["user"] . "</td>";
                $temp .= "<td >" . $data["event"] . "</td>";
                $temp .= "<td >" . $data["dateNtime"] . "</td>";
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
                    // echo "<script>console.log('hi')</script>";
                    $sql = "SELECT * FROM historylogs WHERE user ='" . $args['search'] . "'";
                    showData($sql);
                    break;
                case 'select':
                    break;
            }
        } else {
            $sql = "SELECT * FROM historylogs";
            showData($sql);
        }
    }
}
