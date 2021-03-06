<!-- **************************************************************************
* Copyright 2020 Marshall Brown, Josiah Schmidt, Micah Withers
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or
* See the License for the specific language governing permissions and
* limitations under the License.
************************************************************************** -->
<?php

// function search ($input) {      //  Takes user input and inserts in sql query
//       //      and returns the result
//     $database = "HandSMetals";
//     $conn = new mysqli($servername, $username, $password, $database);
//     // prepare and bind
//     if (is_numeric($input)) {
//         $stmt = $conn->prepare("SELECT * FROM lots WHERE lotnum = ?");
//         $stmt->bind_param("i", $search);
//         $search = (int)$input;
//     } else {
//         $stmt = $conn->prepare("SELECT * FROM lots WHERE customer LIKE ?");
//         $stmt->bind_param("s", $search);
//         $search = '%' . $input . '%';
//     }
//     $stmt->execute();
//     $res = $stmt->get_result();
//     $stmt->close();
//     $conn->close();
//     return $res;
// }

//
function simpleSelect($statement) {

    require 'dbConnect.php';
    if (!($conn = new mysqli($servername, $username, $password, $dbname))) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        return;
    }
    // prepare and bind
    if (!($stmt = $conn->prepare($statement))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        return;
    }
    if (!($result = $stmt->execute())) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        return;
    }
    $res = $stmt->get_result();
    $stmt->close();
    $conn->close();
    return $res;
}

function removeLot ($lotnum) {

    require 'dbConnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("DELETE FROM lots WHERE lotnum = ?");
    $stmt->bind_param("i", $lotnumsql);
    $lotnumsql = (int) $lotnum;
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function removeCust($cust) {

    require 'dbConnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("DELETE FROM customers WHERE company = ?");
    $stmt->bind_param("s", $custsql);
    $custsql = "".$cust."";
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function addLot ($lotnum, $cust) {      //  Takes user input and inserts in sql query

    require 'dbConnect.php';
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO lots (lotnum, customer) VALUES (?, ?)");
    $stmt->bind_param("is", $lotnumsql, $custsql);
    // set parameters and execute
    $lotnumsql = (int)$lotnum;
    $custsql = "".$cust."";
    $result = $stmt->execute();
    $error = $stmt->error;
    $stmt->close();
    $conn->close();
    // US 6.3: Returns success statement or description of the error
    if (!$result) {
        return $error;
    }
    return "Success";
}

// US 7.6: Add pallet to database
function addPallet($lotnum, $gross, $tare) {

    require 'dbConnect.php';
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO pallets (lotnum, gross, tare) VALUES (?, ?, ?)");
    $stmt->bind_param("idd", $lotnumsql, $grosssql, $taresql);
    // set parameters and execute
    $lotnumsql = (int)$lotnum;
    $grosssql = (double)$gross;
    $taresql = (double)$tare;
    $result = $stmt->execute();
    $error = $stmt->error;
    $stmt->close();
    $conn->close();
    // Returns success statement or description of the error
    if (!$result) {
        return $error;
    }
    return "Success";
}

//  User Story 4.4.2
function addCustomer ($company, $contact, $phone, $email) {      //  Takes user input and inserts in sql query

    require 'dbConnect.php';
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO customers (company, contact, phone, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $companysql, $contactsql, $phonesql, $emailsql);
    // set parameters and execute
    $companysql = "".$company."";
    $contactsql = "".$contact."";
    $phonesql = (int)$phone;
    $emailsql = "".$email."";
    $result = $stmt->execute();
    $error = $stmt->error;
    $stmt->close();
    $conn->close();
    if (!$result) {
        return $error;
    }
    return "Success";
}

//  Code to connect to db and return data in customers table (User Story 4.3)
function get_customers () {

    require 'dbConnect.php';
    $database = "HandSMetals";
    $conn = new mysqli($servername, $username, $password, $database);
    $stmt = $conn->prepare("SELECT * FROM customers");
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    $conn->close();
    return $res;
}

//  Code to connect to db and return data in lots table (User Story 3.2)
function get_lots () {

    require 'dbConnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("SELECT * FROM lots");
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    $conn->close();
    return $res;
}

// US 8.4: Returns "net" value from DB of the associated lot "lotnum"
function getLotGross ($lotnum) {

    require 'dbConnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("SELECT SUM(gross) gross FROM pallets WHERE lotnum = ? GROUP BY lotnum");
    $stmt->bind_param("i", $lotnumsql);
    $lotnumsql = (int) $lotnum;
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    $row = $result->fetch_assoc();
    return (double)$row["gross"];
}

// US 8.4: Returns "net" value from DB of the associated lot "lotnum"
function getLotTare ($lotnum) {

    require 'dbConnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("SELECT SUM(tare) tare FROM pallets WHERE lotnum = ? GROUP BY lotnum");
    $stmt->bind_param("i", $lotnumsql);
    $lotnumsql = (int) $lotnum;
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    $row = $result->fetch_assoc();
    return (double)$row["tare"];
}

// US 8.4: Returns "net" value from DB of the associated lot "lotnum"
function getLotNet ($lotnum) {

    require 'dbConnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("SELECT SUM(gross) gross, SUM(tare) tare FROM pallets WHERE lotnum = ? GROUP BY lotnum");
    $stmt->bind_param("i", $lotnumsql);
    $lotnumsql = (int) $lotnum;
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    $row = $result->fetch_assoc();
    return (double)$row["gross"] - (double)$row["tare"];
}

// US 7.6: connect to db and return data in pallets table
function get_pallets ($lotnum) {

    require 'dbConnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("SELECT palletnum, gross, tare, (gross-tare) net FROM pallets WHERE lotnum = ?");
    $stmt->bind_param("i", $lotnumsql);
    $lotnumsql = (int) $lotnum;
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    return $result;
}

// US 8.3: Retruns an array of key:value pairs in the format lotnum:number of pallets
function get_num_pallets() {

    require 'dbConnect.php';
    $database = "HandSMetals";
    $conn = new mysqli($servername, $username, $password, $database);
    // prepare and bind
    $stmt = $conn->prepare("SELECT lotnum, MAX(palletnum) AS palletnum FROM pallets GROUP BY lotnum");
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    $conn->close();
    $rows = array();
    while($row = $res->fetch_assoc()) {
        $rows[(int)$row['lotnum']] = (int)$row['palletnum'];
    }
    return $rows;
}

// US 7.2: get list of customers from db
function get_customers_list() {

    require 'dbConnect.php';
    $database = "HandSMetals";
    $conn = new mysqli($servername, $username, $password, $database);
    // prepare and bind
    $stmt = $conn->prepare("SELECT company FROM customers");
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    $conn->close();
    $rows = array();
    $rowInt = 0;
    while($row = $res->fetch_assoc()) {
        $rows[$rowInt] = $row;
        ++$rowInt;
    }
    return $rows;
}

function get_lots_list() {

    require 'dbConnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("SELECT lotnum FROM lots");
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    $conn->close();
    $rows = array();
    $rowInt = 0;
    while($row = $res->fetch_assoc()) {
        $rows[$rowInt] = (int)$row['lotnum'];
        ++$rowInt;
    }
    return $rows;
}

function getLotsCustomerList() {

    require 'dbConnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("SELECT lotnum, customer FROM lots");
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    $conn->close();
    $lots = array();
    while($row = $res->fetch_assoc()) {
        $lots[(int)$row['lotnum']] = $row['customer'];
    }
    return $lots;
}

function get_fields($result) {
    $fieldsArray = array();
    $finfo = $result->fetch_fields();
    $index = 0;
    foreach ($finfo as $field) {
        $fieldsArray[$index] = $field->name;
        ++$index;
    }
    return $fieldsArray;
}

function get_rows($result) {
    $rows = array();
    $rowInt = 0;
    while($row = $result->fetch_assoc()) {
        $rows[$rowInt] = $row;
        ++$rowInt;
    }
    return $rows;
}

// SQL query to update pallets
function updatePallet($lot, $oldpallet, $newpallet, $gross, $tare) {
    require 'dbConnect.php';
    if (!($conn = new mysqli($servername, $username, $password, $dbname))) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        return;
    }
    // prepare and bind
    if (!($stmt = $conn->prepare("UPDATE pallets SET palletnum = ?, gross = ?, tare = ? WHERE lotnum = ? AND palletnum = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        return;
    }
    $stmt->bind_param('iddii', $newpsql, $gsql, $tsql, $lsql, $oldpsql);
    $newpsql = (int) $newpallet;
    $gsql = (double) $gross;
    $tsql = (double) $tare;
    $lsql = (int) $lot;
    $oldpsql = (int) $oldpallet;
    if (!($result = $stmt->execute())) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        return $result;
    }
    return $result;
}

function removePallet ($lot, $pallet) {

    require 'dbConnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // prepare and bind
    $stmt = $conn->prepare("DELETE FROM pallets WHERE lotnum = ? AND palletnum = ?");
    $stmt->bind_param("ii", $lsql, $psql);
    $lsql = (int) $lot;
    $psql = (int) $pallet;
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    if ($result) {
        $pallets = get_rows(get_pallets($lot));
        for ($i = $psql-1; $i < count($pallets); ++$i) {
            updatePallet($lot, $pallets[$i]["palletnum"], $pallets[$i]["palletnum"]-1, $pallets[$i]["gross"], $pallets[$i]["tare"]);
        }
    }
    return $result;
}

// US 9.2: SQL query to update lots
function updateLot($lot, $customer, $status) {
    require 'dbConnect.php';
    if (!($conn = new mysqli($servername, $username, $password, $dbname))) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        return;
    }
    // prepare and bind
    if (!($stmt = $conn->prepare("UPDATE lots SET customer = ?, status = ? WHERE lotnum = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        return;
    }
    $stmt->bind_param('ssi', $csql, $ssql, $lsql);
    $lsql = (int) $lot;
    $csql = "".$customer."";
    $ssql = "".$status."";
    if (!($result = $stmt->execute())) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        return $result;
    }
    return $result;
}

// US 9.2: SQL query to update lots
function updateCustomer($oldcompany, $newcompany, $contact, $phone, $email) {
    require 'dbConnect.php';
    if (!($conn = new mysqli($servername, $username, $password, $dbname))) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        return;
    }
    // prepare and bind
    if (!($stmt = $conn->prepare("UPDATE customers SET company = ?, contact = ?, phone = ?, email = ? WHERE company = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        return;
    }
    $stmt->bind_param("sssss", $newcompanysql, $contactsql, $phonesql, $emailsql, $oldcompanysql);
    // set parameters and execute
    $newcompanysql = "".$newcompany."";
    $contactsql = "".$contact."";
    $phonesql = "".$phone."";
    $emailsql = "".$email."";
    $oldcompanysql = "".$oldcompany."";
    if (!($result = $stmt->execute())) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        return $result;
    }
    return $result;
}
