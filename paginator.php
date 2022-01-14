<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once('database/dbconn.php');
$paginator = new paginator();
$paginator->init($_REQUEST);

class paginator
{
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function init($args)
    {
        $type = $args;
        switch ($type) {
            case 'all':
                $this->getData($args);
                break;
        }
    }

    public function getData($limit = 20, $page = 1)
    {
        $mainQuery = "SELECT * FROM ticketMonitoring";
        // $this->_limit   = $limit;
        // $this->_page    = $page;

        if ($limit == 'all') {
            $query      = $mainQuery;
        } else {
            $query      = $mainQuery . " LIMIT " . (($page - 1) * $limit) . ", $limit";
        }
        $request             = $this->conn->prepare($query);

        // while ($row = $request->fetchAll(PDO::FETCH_ASSOC)) {
        //     $results[]  = $row;
        // }

        // $result         = new stdClass();
        // $result->page   = $this->_page;
        // $result->limit  = $this->_limit;
        // $result->total  = $this->_total;
        // $result->data   = $results;

        // return $result;
    }
}
