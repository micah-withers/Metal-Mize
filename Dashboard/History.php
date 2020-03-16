<!-- **************************************************************************
* Copyright 2020 Marshall Brown, Josiah Schmidt, Micah Withers
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or
* See the License for the specific language governing permissions and
* limitations under the License.
************************************************************************** -->
<?php require 'search.php' ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <!-- Overall page format -->
        <meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8">
        <!-- Link to CSS stylesheet -->
        <link rel="stylesheet", href="styles.css">
    </head>
    <body>
		<div class = "container">
            <!-- Taskbar -->
			<input type="button" onclick="location.href='main.html';" value="Home" />
			<input type="button" onclick="location.href='History.html';" value="Lot History" />
			<input type="button" onclick="location.href='Reports.html';" value="Reports" />
			<button>Customers</button>
			<input type="button" onclick="location.href='accounts.html';" value="Accounts" />

            <form action="" method="post">
            Search:  <input type="text" name="user_input" />
            <form/>
			<a href="../LoginPage/login.html">Logout</a>
		</div>

	<h1>History Page</h1>
    <!-- Results of search -->
    <?php if (isset($_POST["user_input"])) { ?>
        <h1>Results</h1>
        <p><?php echo $result ?></p>
    <?php } ?>

	</body>
</html>