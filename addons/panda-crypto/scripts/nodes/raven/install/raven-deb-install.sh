#!/bin/bash

# Check if the script is running as root
if [ "$EUID" -ne 0 ]; then
  echo "Please run this script as root."
  exit 1
fi

# Create a 'raven' user and group
useradd -m -s /bin/bash raven

# Update the system
apt update
apt upgrade -y

# Install required packages
apt install -y software-properties-common
add-apt-repository ppa:bitcoin/bitcoin
apt update
apt install -y git build-essential libtool autotools-dev automake pkg-config libssl-dev libevent-dev bsdmainutils libboost-system-dev libboost-filesystem-dev libboost-chrono-dev libboost-program-options-dev libboost-test-dev libboost-thread-dev libminiupnpc-dev libzmq3-dev libqrencode-dev libprotobuf-dev libdb4.8-dev libdb4.8++-dev

# Download the RavenCoin source code from the official repository
su - raven -c "git clone https://github.com/RavenProject/Ravencoin.git"

# Change to the RavenCoin source code directory
cd /home/raven/Ravencoin

# Build the RavenCoin node
su - raven -c "./autogen.sh"
su - raven -c "./configure"
su - raven -c "make"

# Install the RavenCoin node
make install

# Clean up the source code directory
cd /home/raven
rm -rf Ravencoin

# Create a data directory for RavenCoin
mkdir -p /home/raven/.raven

# Create a RavenCoin configuration file
cat > /home/raven/.raven/raven.conf << EOF
rpcuser=yourusername
rpcpassword=yourpassword
daemon=1
server=1
listen=1
rpcallowip=127.0.0.1
rpcport=8766
rpcconnect=127.0.0.1
EOF

# Change ownership of the data directory to the 'raven' user
chown -R raven:raven /home/raven/.raven

# Create a systemd service unit for RavenCoin
cat > /etc/systemd/system/ravend.service << EOF
[Unit]
Description=RavenCoin Node
After=network.target

[Service]
User=raven
Group=raven
Type=simple
ExecStart=/usr/local/bin/ravend -conf=/home/raven/.raven/raven.conf
Restart=always

[Install]
WantedBy=multi-user.target
EOF

# Reload systemd and start the RavenCoin service
systemctl daemon-reload
systemctl enable ravend
systemctl start ravend

# Check if the RavenCoin node is running
if systemctl is-active --quiet ravend; then
  echo "RavenCoin node installation, setup, and service creation complete."
  # Create an installed marker file
  su - raven -c "touch /home/raven/.raven/.installed"
else
  echo "RavenCoin node installation failed."
fi
