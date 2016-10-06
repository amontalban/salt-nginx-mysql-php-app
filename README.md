# Saltstack-Vagrant-Ubuntu-Nginx-MySQL-PHP Demo

Prerequisites: 

The following need to be currently installed on your local system:

Git	https://git-scm.com/

Virtualbox	https://www.virtualbox.org/

Vagrant	https://www.vagrantup.com/	

Saltstack Master & Minion	https://repo.saltstack.com/


Installation Instructions

Open a Terminal Window

Clone the repository

$ sudo git clone https://github.com/scottdspangler/salt-nginx-mysql-php-app.git

Change directories to the location of the repository

user@localhost:~/salt-nginx-mysql-php-app$

Execute the Vagrant executable

user@localhost:~/salt-nginx-mysql-php-app$ sudo vagrant up

The above command will provision a Ubuntu 16.04 LTS “Xenial64” Virtualbox VM and install Saltstack Minion and start the minion process.

Execute the following commands to verify the VM was provisioned, that you can then login to the VM and that the Saltstack minion process is running.

user@localhost:~/salt-nginx-mysql-php-app$ sudo vagrant status

Standard output: 

Current machine states:

minion10                  running (virtualbox)

The VM is running. To stop this VM, you can run `vagrant halt` to
shut it down forcefully, or you can run `vagrant suspend` to simply
suspend the virtual machine. In either case, to restart it again,
simply run `vagrant up`.

user@localhost:~/salt-nginx-mysql-php-app$ sudo vagrant ssh minion10

Standard Output: Welcome to Ubuntu 16.04.1 LTS (GNU/Linux 4.4.0-38-generic x86_64)

 * Documentation:  https://help.ubuntu.com
 * Management:     https://landscape.canonical.com
 * Support:        https://ubuntu.com/advantage
 
Get cloud support with Ubuntu Advantage Cloud Guest:
 http://www.ubuntu.com/business/services/cloud

0 packages can be updated.
0 updates are security updates.

Last login: Day  Month  Time Year from X.X.X.X
ubuntu@minion10:~$ 

$ ps -ef | grep salt-minion
Standard Output:
root      1116     1  0 04:35 ?        00:00:00 /usr/bin/python /usr/bin/salt-minion
root      1156  1116  0 04:35 ?        00:00:00 /usr/bin/python /usr/bin/salt-minion
root      1166  1156  0 04:35 ?        00:00:00 /usr/bin/python /usr/bin/salt-minion

cd /etc/salt; mkdir base

Copy SLS files: 
:~/salt-nginx-mysql-php-app$ cp -p *.sls /etc/salt/base


Salt Master localost $ ifconfig -a (Determine host IP?)

Ubuntu@Minion10:~$ sudo vim /etc/salt/minion
Add: master “IP Address” Example: master 10.0.0.1
Ubuntu@Minion10:~$ sudo service restart salt-minion
Salt Master localhost$ sudo salt-key ‘*’ -L
Accepted Keys:
Denied Keys:
Unaccepted Keys:
ubuntu-xenial.localdomain
Rejected Keys:


sudo salt-key ‘*’ -A
The following keys are going to be accepted:
Unaccepted Keys:
ubuntu-xenial.localdomain
Proceed? [n/Y] y

Salt Master localhost $ sudo salt ‘*’ test.ping
ubuntu-xenial.localdomain:
    True



Salt Master LocalHost $ sudo vim /etc/master
file_root:
  base: 
      - /etc/salt/base
Note: proper syntax, each line in a YAML file is indented by 2 characters.


Add: cp files from master to minion.
Fix php script. 

 Ubuntu@Minion10:~$ ps -ef | grep nginx
root      2540     1  0 05:43 ?        00:00:00 nginx: master process /usr/sbin/nginx -g daemon on; master_process on;
www-data  2541  2540  0 05:43 ?        00:00:00 nginx: worker process
www-data  2542  2540  0 05:43 ?        00:00:00 nginx: worker process

 Ubuntu@Minion10:~$ifconfig -a
enp0s3    Link encap:Ethernet  HWaddr 02:a0:3d:de:85:bf  
          inet addr:10.0.2.15  Bcast:10.0.2.255  Mask:255.255.255.0
          inet6 addr: fe80::a0:3dff:fede:85bf/64 Scope:Link
          UP BROADCAST RUNNING MULTICAST  MTU:1500  Metric:1
          RX packets:43431 errors:0 dropped:0 overruns:0 frame:0
          TX packets:15942 errors:0 dropped:0 overruns:0 carrier:0
          collisions:0 txqueuelen:1000 
          RX bytes:41424035 (41.4 MB)  TX bytes:1103098 (1.1 MB)

enp0s8    Link encap:Ethernet  HWaddr 08:00:27:3c:6c:24  
          inet addr:192.168.1.10  Bcast:192.168.1.255  Mask:255.255.255.0
          inet6 addr: fe80::a00:27ff:fe3c:6c24/64 Scope:Link
          UP BROADCAST RUNNING MULTICAST  MTU:1500  Metric:1
          RX packets:159 errors:0 dropped:0 overruns:0 frame:0
          TX packets:15 errors:0 dropped:0 overruns:0 carrier:0
          collisions:0 txqueuelen:1000 
          RX bytes:27984 (27.9 KB)  TX bytes:1226 (1.2 KB)

lo        Link encap:Local Loopback  
          inet addr:127.0.0.1  Mask:255.0.0.0
          inet6 addr: ::1/128 Scope:Host
          UP LOOPBACK RUNNING  MTU:65536  Metric:1
          RX packets:0 errors:0 dropped:0 overruns:0 frame:0
          TX packets:0 errors:0 dropped:0 overruns:0 carrier:0
          collisions:0 txqueuelen:1 
          RX bytes:0 (0.0 B)  TX bytes:0 (0.0 B)

Test Nginx Webserver: Browser: http://192.168.1.10

In your nginx site configuration (/etc/nginx/sites-available/default), modify the line in the server {} section and make sure the the specific lines are uncommented (No # at the beginning of each line of text).
index index.html index.htm to index index.php index.html index.htm.
 root /var/www/html;

        # Add index.php to the list if you are using PHP
        index index.php index.html index.htm index.nginx-debian.html;

        server_name localhost;

        location / {
                # First attempt to serve request as file, then
                # as directory, then fall back to displaying a 404.
                try_files $uri $uri/ =404;
        }

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000

        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                
                ##With php7.0-cgi alone:
                #fastcgi_pass 127.0.0.1:9000;
                ##With php7.0-fpm:
                fastcgi_pass unix:/run/php/php7.0-fpm.sock;
        }

#        deny access to .htaccess files, if Apache's document root
#        concurs with nginx's one
        
        location ~ /\.ht {
                deny all;
        }       
}

MySQL Secure Installation

 Ubuntu@Minion10:~$ sudo mysql_secure_installation


Securing the MySQL server deployment.

Connecting to MySQL using a blank password.

VALIDATE PASSWORD PLUGIN can be used to test passwords
and improve security. It checks the strength of password
and allows the users to set only those passwords which are
secure enough. Would you like to setup VALIDATE PASSWORD plugin?

Press y|Y for Yes, any other key for No: n 
Please set the password for root here.

New password: 

Re-enter new password: 
By default, a MySQL installation has an anonymous user,
allowing anyone to log into MySQL without having to have
a user account created for them. This is intended only for
testing, and to make the installation go a bit smoother.
You should remove them before moving into a production
environment.

Remove anonymous users? (Press y|Y for Yes, any other key for No) : y
Success.

Normally, root should only be allowed to connect from
'localhost'. This ensures that someone cannot guess at
the root password from the network.

Disallow root login remotely? (Press y|Y for Yes, any other key for No) : y
Success.

By default, MySQL comes with a database named 'test' that
anyone can access. This is also intended only for testing,
and should be removed before moving into a production
environment.


Remove test database and access to it? (Press y|Y for Yes, any other key for No) : y
 - Dropping test database...
Success.

 - Removing privileges on test database...
Success.

Reloading the privilege tables will ensure that all changes
made so far will take effect immediately.

Reload privilege tables now? (Press y|Y for Yes, any other key for No) : y
Success.

All done! 

Clone Test Database from Github

 Ubuntu@Minion10:~$ sudo git clone  https://github.com/scottdspangler/test_db.git

Cloning into 'test_db'...
remote: Counting objects: 94, done.
remote: Total 94 (delta 0), reused 0 (delta 0), pack-reused 94
Unpacking objects: 100% (94/94), done.
Checking connectivity... done.

 Ubuntu@Minion10:~$ cd test_db
 Ubuntu@Minion10:~$ sudo mysql < employees_partitioned.sql
INFO
CREATING DATABASE STRUCTURE
INFO
storage engine: InnoDB
INFO
LOADING departments
INFO
LOADING employees
INFO
LOADING dept_emp
INFO
LOADING dept_manager
INFO
LOADING titles
INFO
LOADING salaries
data_load_time_diff
00:01:27


PHPMyAdmin Configuration


sudo ln -s /usr/share/phpmyadmin /usr/share/nginx/html


sudo vagrant box update
sudo vagrant status minion10








PHPMyadmin : User: debian-sys-maint Password: testing
