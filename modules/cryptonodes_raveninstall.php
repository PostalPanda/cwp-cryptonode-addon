<?php

// Function to execute the Bash script
function installRavenNodeLocally() {
    $bashScript = "/usr/local/cwpsrv/htdocs/resources/admin/addons/panda-crypto/scripts/nodes/raven/install/raven-rhel-install.sh"; // Modify this with the correct script name and path
    $output = shell_exec("sudo bash $bashScript 2>&1");
    return $output;
}


function installRavenNodeRemotely($hostname, $port, $username, $password, $keyFile, $os) {
    if ($os === 'rhel') {
        $installScript = "/usr/local/cwpsrv/htdocs/resources/admin/addons/panda-crypto/scripts/nodes/raven/install/raven-rhel-install.sh";
    } elseif ($os === 'debian') {
        $installScript = "/usr/local/cwpsrv/htdocs/resources/admin/addons/panda-crypto/scripts/nodes/raven/install/raven-deb-install.sh";
    } else {
        return "Invalid OS selection";
    }

    $command = "ssh -p $port $username@$hostname";
    if (!empty($keyFile)) {
        $command .= " -i $keyFile";
    }

    if (!empty($password) && !empty($keyFile)) {
        $output = "Please provide either a password or an SSH key, not both.";
    } elseif (empty($password) && empty($keyFile)) {
        $output = "Please provide either a password or an SSH key.";
    } else {
        $command .= " 'bash -s' < $installScript 2>&1";
        $output = shell_exec($command);
    }

    return $output;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // This is a GET request, display a form or some information
    echo "Welcome to the RavenCoin Node Installation Script.<br>";
    echo "Please be aware that this script requires sudo privileges to run.<br>";
    echo "Choose your installation method:<br>";
    echo "<form method='post'>
        <input type='radio' name='installationMethod' value='local' checked> Install Locally<br>
        <input type='radio' name='installationMethod' value='remote'> Install Remotely<br><br>";
    echo "For local installation, click the button below to start the installation.<br>";
    echo "<input type='submit' name='installLocally' value='Install Locally'><br><br>";
    echo "For remote installation, fill in the following details:<br>";
    echo "Hostname/IP: <input type='text' name='hostname'><br>";
    echo "SSH Port: <input type='text' name='port'><br>";
    echo "SSH Username: <input type='text' name='username'><br>";
    echo "SSH Password (optional): <input type='password' name='password'><br>";
    echo "SSH Key File (optional): <input type='file' name='keyFile'><br>";
    echo "Select the remote system operating system:<br>";
    echo "<input type='radio' name='os' value='rhel' checked> RHEL/CentOS/Rocky/Alma<br>";
    echo "<input type='radio' name='os' value='debian'> Ubuntu/Debian<br>";
    echo "<input type='submit' name='installRemotely' value='Install Remotely'><br></form>";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $installationMethod = $_POST['installationMethod'];

    if ($installationMethod === 'local') {
        // Install locally
        $output = installRavenNodeLocally();
    } elseif ($installationMethod === 'remote') {
        // Install remotely
        $hostname = $_POST['hostname'];
        $port = $_POST['port'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $os = $_POST['os'];

        // Handle uploaded key file
        $keyFile = '';
        if ($_FILES['keyFile']['size'] > 0) {
            $keyFileName = $_FILES['keyFile']['tmp_name'];
            $keyFileDestination = 'uploaded_key.pem'; // Modify the destination path if needed
            move_uploaded_file($keyFileName, $keyFileDestination);
            $keyFile = $keyFileDestination;
        }

        $output = installRavenNodeRemotely($hostname, $port, $username, $password, $keyFile, $os);
    }

    echo "Installation Output:<br><pre>$output</pre>";
} else {
    // Handle other HTTP methods if needed
    echo "Unsupported HTTP method.";
}

?>