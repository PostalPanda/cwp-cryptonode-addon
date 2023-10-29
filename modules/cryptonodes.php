
<?php
if (!isset($include_path)) {
    echo "invalid access";
    exit();
}

$cpu_info = shell_exec("cat /proc/cpuinfo");


echo "<h2>Panda's Crypto Module</h2>";
echo "<p>This module allows you to manage different cryptocurrency nodes. You can install or remove nodes as per your requirements. Please ensure you have the necessary system resources available before installing a node.</p>";
echo "<p><font color='red'>Cryptocurrency nodes are resource intensive and should not be ran on small virtual machines. Please be sure your system meets the requirements listed in addition to the requirements of your web panel and websites.</font></p>";

echo "<table border='1'>";
echo "<tr>";
echo "<th>Crypto Name</th>";
echo "<th>Description</th>";
echo "<th>Install/Remove</th>";
echo "</tr>";

echo "<tr>";
echo "<td>Ravencoin</td>";
echo "<td>Ravencoin node requires at least 2GB of RAM, 1GB of disk space, and a network connection with at least 1Mb/s download speed.</td>";

if (!file_exists('/home/raven/.raven/.installed')) {
    echo "<td><button onclick=\"location.href='index.php?module=cryptonodes_raveninstall'\">Install</button></td>";
} else {
    echo "<td><button onclick=\"location.href='./ravenremover.sh'\">Remove</button><br/><button onclick=\"location.href='ravenconfigure.php'\">Configure</button></td>";
}

echo "</tr>";
echo "</table>";
?>
