<?php

require_once('components/header.php');
require_once('database/dbconn.php');

$accounts = new Accounts;
$accounts->init($_REQUEST);

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
                case 'show':
                    $logs = new historyLogs();
                    $logs->showHistoryLogs();
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

    public function addLog($params)
    {

        // ini_set('log_errors', 1);
        $sql = "INSERT INTO historylogs 
        (user,event,dateNtime)
        VALUES 
        ('" . $params['username'] . "',
        '" . $params['action'] . "',
        '" . $params['dateNtime'] . "');";
        // exit(print_r($sql));

        $query = $this->conn->prepare($sql);

        if ($query->execute()) {
            // if()
            echo "<script> console.log('history log updated') </script>";
        } else {
            echo "<script> console.log('error updating') </script>";
        }
    }

    public function showHistoryLogs()
    {
        $sql = "SELECT * FROM historylogs";
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query;
    }
}
