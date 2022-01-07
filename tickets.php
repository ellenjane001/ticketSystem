<?php
// require_once('components/header.php');
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

            $temp = "<table class='tbl'>";
            $temp .= "<thead>";
            $temp .= "<tr>";
            $temp .= "<th class='size'>status</th>";
            $temp .= "<th style='width: 110px;'>Ticket#</th>";
            $temp .= "<th style='width: 200px'>Request</th>";
            $temp .= "<th class='size'>Client</th>";
            $temp .= "<th style='width:75px;'>category</th>";
            $temp .= "<th class='size'>priority</th>";
            $temp .= "<th style='width:75px;'>due</th>";
            $temp .= "<th>Assign to</th>";
            $temp .= "<th style='width:100px;'>Date & Time</th>";
            $temp .= "<th class='size'></th></tr>";
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
                $temp .= "<td class='size' style='$color'>" . $data["status"] . "</td>";
                $temp .= "<td style='width: 110px;'>" . $data["ticketNumber"] . "</td>";
                $temp .= "<td style='width: 200px'>" . $data["issue_problem"] . "</td>";
                $temp .= "<td class='size'>" . $data["createdby"] . "</td>";
                $temp .= "<td style='width:75px;'>" . $data["category"] . "</td>";
                $temp .= "<td class='size' style='$bgcolor'>" . $data["priority"] . "</td>";
                $temp .= "<td  style='width:75px;'>" . $data["due"] . "</td>";
                $temp .= "<td>" . $data["assignedTo"] . "</td>";
                $temp .= "<td style='width:100px;'>" . $data["dateNTime"] . "</td>";

                $temp .= "<td class='size'>";
                $temp .= "<a href='#' id=" . $data["ticketNumber"] . " onclick='pageLoader(this)'>View</a>";
                $temp .= "</td>";
                $temp .= "</tr>";
            endforeach;
            $temp .= "</tbody>";

            $temp .= "</table>";

            echo $temp;
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
            $sql = "SELECT * FROM " . $this->db_table . "";
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
                $sql = "SELECT * FROM " . $this->db_table . " WHERE createdBy = '" . $val . "'";
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
