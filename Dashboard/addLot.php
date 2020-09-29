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
    require 'sql.php';
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Add Lot</title>
	<link href="styles.css" rel="stylesheet">
</head>
<body>
<a href="main.php" id="temp"><h4>home</h4></a>
	<nav>
		<form id="navForm">
		<div id="lotBar">
			<label for="lotNum">Lot No.</label>
			<input type="number" name= "lotNo"><br>
		</div>
		<div class="nav">
            <label for="cust">Customer</label>
			<input type="search" name="cust" list="custList">
            <datalist id="custList">
			</datalist>

			<label for="gross">Gross</label>
			<input type="number" name="gross"><br>

			<label for="tare">Tare</label>
			<input type="number" name="tare"><br>

		</div>
		</form>
	</nav>
    <br>
	<div id="tableDiv">

    </div>
</body>

</html>

 <script type="module" defer>
    import * as Script from './scripts.js';

    // US 7.2: obtain list of customers from db
    let custList = <?php echo json_encode(get_customers_list()); ?>;
    Script.custAdd("custList", custList);

    // US 7.5: create table
    Script.createTable("tableDiv", "pallets", [], ["Pallet", "Gross", "Tare", "Net"]);
</script>
