#!/usr/bin/env bash

wget -O - https://repo.saltstack.com/apt/ubuntu/16.04/amd64/latest/SALTSTACK-GPG-KEY.pub | sudo apt-key add -
echo "deb http://repo.saltstack.com/apt/ubuntu/16.04/amd64/latest xenial main" > /etc/apt/sources.list.d/saltstack.list
sudo apt-get update
apt-get install -y salt-minion
#:sudo apt-get install -y debconf-utils

cat <<EOF >/etc/salt/minion
master: Enter IP address of Salt Master here 
EOF

#cat <<EOF >>/etc/hosts
#10.0.0.11 salt-master
#EOF

salt-minion -d
