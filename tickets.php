<?php
// require_once('components/header.php');
if (!isset($_SESSION)) {
    session_start();
}
require_once('database/dbconn.php');



// exit(print_r($_REQUEST));
$tickets = new TicketMonitoring();
$tickets->init($_REQUEST);

class TicketMonitoring
{
    private $conn;

    private $db_table = "ticketmonitoring";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function init($args)
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $type = $args['request_type'];

        // exit(print_r($args['request_type']));
        if (isset($type)) {
            switch ($type) {
                case 'show':
                    $this->getTickets($args);
                    break;
                case 'create':
                    $ticketLogs = new TicketLogs();
                    require_once('accounts.php');
                    $historyLogs = new historyLogs();
                    $this->createTicket($args);
                    $ticketLogs->addTicketLog($args);
                    $historyLogs->addLog($args);

                    break;
                case 'showSingle':
                    $ticketLogs = new TicketLogs();
                    $this->getSingleTicket($args);
                    $ticketLogs->getSingleTicketLog($args);
                    break;
                case 'update':
                    $ticketLogs = new TicketLogs();
                    require_once('accounts.php');
                    $historyLogs = new historyLogs();
                    $ticketLogs->addTicketLog($args);
                    $this->updateTicket($args);
                    $historyLogs->addLog($args);
                    break;
                case 'print':
                    // echo 'hi';
                    $this->_export();
                    // exit(print_r($args));
                    require_once('accounts.php');
                    $historyLogs = new historyLogs();
                    $historyLogs->addLog($args);
                    break;
                default:
                    echo "ERROR101";
                    break;
            }
        }
    }

    public function getTickets($params)
    {
        function showData($sql)
        {
            $database = new Database();
            $conn = $database->getConnection();
            $query = $conn->prepare($sql);

            $query->execute();

            $row = $query->fetchAll(PDO::FETCH_ASSOC);

            // $_SESSION['query'] = $row;
            // exit(print_r($row[0]));
            // for ($i = 0; $i < count($row); $i++) {
            //     $rowItem = array($row[$i]);
            //     $_SESSION['data'] = json_encode($rowItem, true);
            // }


            // $temp = "<button onclick='printToExcel()'>Print</button>";
            $temp = "<table id='tbl' class='tbl'>";
            $temp .= "<thead>";
            $temp .= "<tr>";
            $temp .= "<th style='width:75px;'>ID</th>";
            $temp .= "<th class='size'>status</th>";
            $temp .= "<th style='width: 110px;'>Ticket#</th>";
            $temp .= "<th style='width: 200px'>Request</th>";
            $temp .= "<th class='size'>Client</th>";
            $temp .= "<th style='width:75px;'>category</th>";
            $temp .= "<th class='size'>priority</th>";
            $temp .= "<th style='width:75px;'>due</th>";
            $temp .= "<th>Assign to</th>";
            $temp .= "<th style='width:100px;'>Date & Time</th>";
            $temp .= "<th class='size'>option</th></tr>";
            $temp .= "</thead>";


            $temp .= "<tbody>";

            foreach ($row as $data) :

                $status = $data['status'];
                if ($status == "resolved") {
                    $color = "color:green";
                } else if ($status == "pending") {
                    $color = "color:orange";
                } else {
                    $color = "color:red";
                }

                $priority = $data['priority'];
                if ($priority == "low") {
                    $bgcolor = "    color:green";
                } else if ($priority == "medium") {
                    $bgcolor = "color:orange";
                } else {
                    $bgcolor = "color:red";
                }
                $temp .= "<tr>";
                $temp .= "<td style='width:75px;'>" . $data["ticket_id"] . "</td>";
                $temp .= "<td class='size' style='$color'>" . $data["status"] . "</td>";
                $temp .= "<td style='width: 110px;'>" . $data["ticketNumber"] . "</td>";
                $temp .= "<td style='width: 200px'>" . $data["issue_problem"] . "</td>";
                $temp .= "<td class='size'>" . $data["createdby"] . "</td>";
                $temp .= "<td style='width:75px;'>" . $data["category"] . "</td>";
                $temp .= "<td class='size' style='$bgcolor'>" . $data["priority"] . "</td>";
                $temp .= "<td  style='width:75px;'>" . $data["due"] . "</td>";
                $temp .= "<td>" . $data["assignedTo"] . "</td>";
                $temp .= "<td style='width:100px;'>" . $data["dateNTime"] . "</td>";
                $temp .= "<td class='size' style='text-decoration: underline;'>";
                $temp .= "<a href='#' id=" . $data["ticketNumber"] . " onclick='pageLoader(this)'>View</a>";
                $temp .= "</td>";
                $temp .= "</tr>";
            endforeach;
            $temp .= "</tbody>";

            $temp .= "</table>";

            echo $temp;



            $_SESSION['query'] = $row;

            // echo $_SESSION['table'];
        }

        $show = $params['show'];

        if (isset($params['show'])) {
            switch ($show) {
                case 'search':
                    $sql = "SELECT * FROM " . $this->db_table . " 
                        WHERE  
                        ticketNumber LIKE '%" . $params['query'] . "%' 
                        OR issue_problem LIKE '%" .  $params['query'] . "%' 
                        OR createdby LIKE '%" .  $params['query'] . "%' 
                        OR category LIKE '%" .  $params['query'] . "%'
                        OR priority LIKE '%" .  $params['query'] . "%' 
                        OR due LIKE '%" .  $params['query'] . "%' 
                        OR assignedTo LIKE '%" .  $params['query'] . "%'
                        OR dateNTime LIKE '%" .  $params['query'] . "%' 
                        OR status LIKE '%" .  $params['query'] . "%' ";
                    showData($sql);
                    break;
                case 'status':
                    $sql = "SELECT * FROM " . $this->db_table . "
                    WHERE status =  '" . $params['stat'] . "'";
                    showData($sql);
                    break;
                case 'category':
                    $sql = "SELECT * FROM " . $this->db_table . "
                        WHERE category =  '" . $params['category'] . "'";
                    showData($sql);
                    break;
                case 'priority':
                    $sql = "SELECT * FROM " . $this->db_table . "
                        WHERE priority =  '" . $params['priority'] . "'";
                    showData($sql);
                    break;
                case 'sort':
                    $sql = "SELECT * FROM " . $this->db_table . "
                        ORDER BY ticket_ID " . $params['sort'] . "";
                    showData($sql);
                    break;
                case 'user':
                    $sql = "SELECT * FROM " . $this->db_table . "
                    WHERE createdBy = '" . $params['user'] . "'";
                    // exit(print_r($sql));
                    showData($sql);
                    break;
            }
        } else {
            $sql = "SELECT * FROM " . $this->db_table . " ";
            showData($sql);
        }
    }

    public function getSingleTicket($data)
    {
        $ticketNumber = $data['id'];
        // $this->ticketNumber = $data;
        // $this->ticketNumber = htmlspecialchars(strip_tags($this->ticketNumber));
        $sql = "SELECT * from " . $this->db_table . " WHERE 
        ticketNumber = '" . $ticketNumber . "'
        ";

        // exit(print_r($sql));
        $query = $this->conn->prepare($sql);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        $temp = "<input type='hidden' value='" . $row['ticket_id'] . "' id='id_num'>";
        echo $temp;
    }


    public function getCount()
    {
        $sql = "SELECT COUNT(*) as total FROM " . $this->db_table . "";
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query;
    }

    public function getUserTicketCount($stat, $val)
    {
        switch ($stat) {
            case 'all':
                $sql = "SELECT COUNT(*) as total FROM " . $this->db_table . " WHERE createdBy = '" . $val . "'";
                $query = $this->conn->prepare($sql);
                $query->execute();
                return $query;
                break;
            case 'open':
                $sql = "SELECT COUNT(*) as total FROM " . $this->db_table . " WHERE createdBy = '" . $val . "' AND status = '" . $stat . "'";
                $query = $this->conn->prepare($sql);
                $query->execute();
                return $query;
                break;
            case 'pending':
                $sql = "SELECT COUNT(*) as total FROM " . $this->db_table . " WHERE createdBy = '" . $val . "' AND status = '" . $stat . "'";
                $query = $this->conn->prepare($sql);
                $query->execute();
                return $query;
                break;
            case 'resolved':
                $sql = "SELECT COUNT(*) as total FROM " . $this->db_table . " WHERE createdBy = '" . $val . "' AND status = '" . $stat . "'";
                $query = $this->conn->prepare($sql);
                $query->execute();
                return $query;
                break;
            case 'tickets':
                $sql = "SELECT DISTINCT ticketNumber, issue_problem, status FROM ticketMonitoring WHERE createdBy = '" . $val . "'";
                // echo '<script>console.log(' . $sql . ',' . $val . ')</script>';
                $query = $this->conn->prepare($sql);
                $query->execute();
                // echo ('hey');
                return $query;
                break;
        }
    }

    public function getTicketCount($status)
    {

        if ($status == "OPEN") {
            $sql = "SELECT COUNT(*) as total FROM " . $this->db_table . " WHERE status='OPEN'";
            $query = $this->conn->prepare($sql);
            $query->execute();
            return $query;
        } elseif ($status == "RESOLVED") {
            $sql = "SELECT COUNT(*) as total FROM " . $this->db_table . " WHERE status='RESOLVED'";
            $query = $this->conn->prepare($sql);
            $query->execute();
            return $query;
        } elseif ($status == "PENDING") {
            $sql = "SELECT COUNT(*) as total FROM " . $this->db_table . " WHERE status='PENDING'";
            $query = $this->conn->prepare($sql);
            $query->execute();
            return $query;
        } else {
            echo "unknown status";
        }
    }

    public function createTicket($params)
    {
        $ticketNum = uniqid();
        $sql = "INSERT INTO 
        " . $this->db_table . " (
        ticketNumber,
        issue_problem, 
        createdby, 
        category, 
        priority, 
        due,
        assignedTo,  
        dateNTime,
        status )
        VALUES (
            '" . $params['ticketNum'] . "', 
            '" . $params['problem'] . "', 
            '" . $params['username'] . "', 
            '" . $params['category'] . "', 
            '" . $params['priority'] . "', 
            '" . $params['due'] . "',
            '" . $params['assignedTo'] . "',
            '" . $params['dateNTime'] . "',
            '" . $params['status'] . "')";

        $query = $this->conn->prepare($sql);

        if ($query->execute()) {
            $output = "<span class='success'> Successful!
                Ticket Number:  " . $params['ticketNum'] . "
                        </span>";
            echo $output;
        } else {
            echo "<script> console.log('error adding') </script>";
            echo "<span class='error'> error adding </span>";
        }
    }

    public function updateTicket($params)
    {
        // exit(print_r($params));
        $sql = "UPDATE
        " . $this->db_table . "
        SET
        createdBy='" . $params['username'] . "',
        dateNTime ='" . $params['dateNTime'] . "',
        status ='" . $params['status'] . "'
        WHERE
        ticket_id ='" . $params['id'] . "'
        ;";
        // print_r($sql);
        $query = $this->conn->prepare($sql);

        if ($query->execute()) {
            $output = "<span class='success'> Successful!</span>";
            echo $output;
            //exit();
        } else {
            echo "<script> console.log('error updating') </script>";
            echo "<span class='error'> error updating </span>";
        }
        // resolution ='" . $params['actionDetails'] . "',
        // actionBy  ='" . $params['actionBy'] . "'
    }

    public function _export()
    {

        $query_data = $_SESSION['query'];

        $array_keysx = array(
            array('key' => 'status', 'value' => 'status'),
            array('key' => 'ticketNumber', 'value' => 'ticketNumber'),
            array('key' => 'issue_problem', 'value' => 'issue_problem'),
            array('key' => 'createdby', 'value' => 'createdby'),
            array('key' => 'category', 'value' => 'category'),
            array('key' => 'priority', 'value' => 'priority'),
            array('key' => 'due', 'value' => 'due'),
            array('key' => 'assignedTo', 'value' => 'assignedTo'),
            array('key' => 'dateNTime', 'value' => 'dateNTime'),


        );
        // print_r($array_keysx);
        $value = '';
        $array_title = array();
        foreach ($array_keysx as $key => $val) {
            array_push($array_title, $val['value']);
        }
        $value .= '#,';
        for ($x = 0; $x < count($array_title); $x++) {
            $comma = '';
            if ($x != 0) {
                $comma = ",";
            }
            $value .= $comma . $array_title[$x];
        }
        for ($x = 0; $x < count($query_data); $x++) {
            foreach ($query_data[$x] as $key => $val) {
                if (($query_data[$x][$key] == '')) {
                    $query_data[$x][$key] = 'N/A';
                } else {
                    $query_data[$x][$key] = $this->_check_badchar($val);
                }
            }
        }
        for ($x = 0; $x < count($query_data); $x++) {
            $value .= ($x + 1);
            $value .= ',' . $query_data[$x]['status'];
            $value .= ',' . $query_data[$x]['ticketNumber'];
            $value .= ',' . $query_data[$x]['issue_problem'];
            $value .= ',' . $query_data[$x]['createdby'];
            $value .= ',' . $query_data[$x]['category'];
            $value .= ',' . $query_data[$x]['priority'];
            $value .= ',' . $query_data[$x]['due'];
            $value .= ',' . $query_data[$x]['assignedTo'];
            $value .= ',' . $query_data[$x]['dateNTime'];

            $value .= "\r\n";
        }

        $file = fopen('srcs/download.csv', 'w');
        fwrite($file, ($value));
        fclose($file);
        echo '<script>window.location.assign("srcs/download.csv")</script>';
    }

    private function _check_badchar($val)
    {
        if (strpos($val, ',')) {
            $val = '"' . $val . '"';
        }
        return $val;
    }
}

class TicketLogs
{

    private $conn;

    private $db_table = "ticketlogs";



    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    public function getTicketLogs()
    {
        $sql = "SELECT * from " . $this->db_table . "";
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query;
    }

    public function getSingleTicketLog($data)
    {
        // $this->ticketNumber = $data;
        //exit(print_r($data));
        //exit(print_r($this->ticketNumber));
        //$this->ticketNumber = htmlspecialchars(strip_tags($this->ticketNumber));
        $sql = "SELECT * from " . $this->db_table . " WHERE 
        ticketNumber = '" . $data['id'] . "'
        ";
        // exit(print_r($sql));

        $query =  $this->conn->prepare($sql);

        $query->execute();

        //return $query;

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);


        // $temp = "<ul class='ticketLogs'>";
        $temp = "<div class='list'>";

        foreach ($rows as $data) :

            if ($data['user'] == "test") {
                $class = 'him';
            } else if ($data['user'] == "admin") {
                $class = 'me';
            }

            $status = $data['status'];
            if ($status == "resolved") {
                $color = "color:green;";
            } else if ($status == "pending") {
                $color = "color:orange;";
            } else {
                $color = "color:red;";
            }

            $temp .= "<div class='list-item'>";
            $temp .= "<span class='list-item-user'>" . $data["user"] . "</span>";
            $temp .= "<div class='$class'><strong>" . $data["actions"] . "</strong>";
            $temp .= "&nbsp;  resolution: " . $data["resolution"] . "";
            $temp .= "&nbsp; action by: " . $data["actionBy"] . "";
            $temp .= "" . $data["date_time_updated"] . "";
            $temp .= " &nbsp;<span style='$color'> " . $data["status"] . "</span></div>";
            $temp .= "</div> ";
        endforeach;
        $temp .= "</div>";
        // $temp .= "</ul>";

        echo  $temp;
    }
    public function addTicketLog($params)
    {

        // exit(print_r($params));
        $sql = "INSERT INTO 
        " . $this->db_table . "
        (ticketNumber,actions,resolution,
        date_time_updated,
        user,status,actionBy)
        VALUES (
        '" . $params['ticketNum'] . "', 
        ' " . $params['action'] . "', 
        '" . $params['actionDetails'] . "',
        '" . $params['dateNTime'] . "', 
        '" . $params['username'] . "', 
        '" . $params['status'] . "', 
        '" . $params['actionBy'] . "')";
        // exit(print_r($sql));

        $query = $this->conn->prepare($sql);
        if ($query->execute()) {
            echo "<script> console.log('ticket log updated') </script>";
        } else {
            echo "<script> console.log('error updating  ') </script>";
        }
    }
}
