# Saltstack-Vagrant-Ubuntu-Nginx-MySQL-PHP Demo

Prerequisites: 

The following need to be currently installed on your local system:

Git	https://git-scm.com/

Virtualbox	https://www.virtualbox.org/

Vagrant	https://www.vagrantup.com/	

Saltstack Master & Minion	https://repo.saltstack.com/


Installation Instructions

Open a Terminal Window

Note: Please make sure you are in you home directory.

Clone the repository

user@localhost:~$ sudo git clone https://github.com/scottdspangler/salt-nginx-mysql-php-app.git

Change directories to the location of the repository

user@localhost:~/salt-nginx-mysql-php-app$

Execute the Vagrant executable

Note: During the provisioning of mininon10, a additional NIC is configured with a Private IP: 192.168.0.10

user@localhost:~/salt-nginx-mysql-php-app$ sudo vagrant up minion10

Bringing machine 'minion10' up with 'virtualbox' provider...

==> minion10: Checking if box 'ubuntu/xenial64' is up to date...

==> minion10: Machine already provisioned. Run `vagrant provision` or use the `--provision`

==> minion10: flag to force provisioning. Provisioners marked to run always will still run.


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

Open a second console window:

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

Execute the following command to verify the salt-minion process is running.

ubuntu@minion10:~$ ps -ef | grep salt-minion

Standard Output:

root      1116     1  0 04:35 ?        00:00:00 /usr/bin/python /usr/bin/salt-minion

root      1156  1116  0 04:35 ?        00:00:00 /usr/bin/python /usr/bin/salt-minion

root      1166  1156  0 04:35 ?        00:00:00 /usr/bin/python /usr/bin/salt-minion

On the localhost (salt-master) execute the following commands to create a base directory under /etc/salt and to copy the

appropriate .sls files to the base directory.

user@localhost:~/salt-nginx-mysql-php-app$ cd /etc/salt; mkdir base

Copy SLS files:  

user@localhost:~/salt-nginx-mysql-php-app/config-files/sls-files$ sudo cp -p *.sls /etc/salt/base

user@localhost:~/salt-nginx-mysql-php-app$ ifconfig -a (Determine host IP?)

ubuntu@Minion10:~$ sudo vim /etc/salt/minion

Find near the top of the file # master: salt , uncomment the line and add: master X.X.X.X (where x.x.x.x is master IP)

save file and exit:

ubuntu@minion10:/etc/salt$ sudo systemctl restart salt-minion

Execute the following commands on the localhost salt-master:

user@localhost:/etc/salt$

user@localhost:/etc/salt$ sudo salt-key ‘*’ -L

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
    
user@localhost:~/salt-nginx-mysql-php-app$ sudo vim /etc/salt/master

Uncomment the following the lines below and add the SLS path to be (- /etc/salt/base)

file_root:

  base: 
  
      - /etc/salt/base
      
Note: proper syntax, each line in a YAML file is indented by 2 characters.

user@localhost:~/etc/salt/base$

The following command will install nginx, mysql-server, php, php-fpm, php-mysql, and git on minion10.

user@localhost:~/etc/salt/base$ sudo salt '*' state.highstate

The following commands below will install the proper nginx configuration file, an PHP test info.php file and the PHP script on minion10.

user@localhost:~/etc/salt/base$ cd /salt-nginx-mysql-php-app/config-files/PHPfiles

user@localhost:~/salt-nginx-mysql-php-app/config-files/PHPfiles$ sudo salt-cp "*" empmulti.php info.php /var/www/html/ 

Testing the Nginx Web Server:

Ubuntu@Minion10:~$ ps -ef | grep nginx

root      2540     1  0 05:43 ?        00:00:00 nginx: master process /usr/sbin/nginx -g daemon on; master_process on;

www-data  2541  2540  0 05:43 ?        00:00:00 nginx: worker process

www-data  2542  2540  0 05:43 ?        00:00:00 nginx: worker process

Ubuntu@Minion10:~$ ifconfig -a
 
Verify one of the NIC's is set to 192.168.1.10

Test Nginx Webserver: Browser: http://192.168.1.10

Nginx default page

If you see the above page, you have successfully installed Nginx.

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

Configure Nginx to Use the PHP Processor

Make sure the snippet from the /etc/nginx/sites-available/default file looks like the example below:

Ubuntu@Minion10:~$ sudo vim /etc/nginx/sites-available/default

/etc/nginx/sites-available/default

server {

    listen 80 default_server;
    
    listen [::]:80 default_server;

    root /var/www/html;
    index index.php index.html index.htm index.nginx-debian.html;

    server_name server_domain_or_IP;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
    
        include snippets/fastcgi-php.conf;
        
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    }

    location ~ /\.ht {
    
        deny all;
    }
}


When you've made the above changes, you can save and close the file.

Test your configuration file for syntax errors by typing:

Ubuntu@Minion10:~$ sudo nginx -t

If any errors are reported, go back and recheck your file before continuing.

When you are ready, reload Nginx to make the necessary changes:

Ubuntu@Minion10:~$ sudo systemctl reload nginx

Create a PHP File to Test Configuration

Your LEMP stack should now be completely set up. We can test it to validate that Nginx can correctly hand .php files off to

our PHP processor.

We can do this by creating a test PHP file in our document root. Open a new file called info.php within your document root

in your text editor:

Ubuntu@Minion10:~$ sudo vim /var/www/html/info.php

Type or paste the following lines into the new file. This is valid PHP code that will return information about our server:

/var/www/html/info.php

<?php

phpinfo();

When you are finished, save and close the file.

Now, you can visit this page in your web browser by visiting your server's domain name or public IP address followed by

http://server_domain_or_IP/info.php

You should see a web page that has been generated by PHP with information about your server:

If you see a page with the following heading , you've set up PHP processing with Nginx successfully.

PHP Version 7.0.8-0ubuntu0.16.04.3

Verify you are in the home directory on the minion.

ubuntu@minion10:/etc/salt$ cd ~

ubuntu@minion10:~$ pwd

/home/ubuntu

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

Verify the empmulti.php file is present in the /var/www/html directory.

ubuntu@minion10:/var/www/html$ ls
empmulti.php  index.nginx-debian.html  info.php


Create a new user within the MySQL shell:

ubuntu@minion10:/var/www/html$ sudo mysql

Welcome to the MySQL monitor.  Commands end with ; or \g.

Your MySQL connection id is X

Server version: 5.7.15-0ubuntu0.16.04.1 (Ubuntu)

Copyright (c) 2000, 2016, Oracle and/or its affiliates. All rights reserved.

Oracle is a registered trademark of Oracle Corporation and/or its

affiliates. Other names may be trademarks of their respective

owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.


mysql> CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';

Query OK, 0 rows affected (0.02 sec)

mysql> GRANT ALL PRIVILEGES ON * . * TO 'newuser'@'localhost';

Query OK, 0 rows affected (0.00 sec)

mysql> FLUSH PRIVILEGES;

Query OK, 0 rows affected (0.01 sec)

mysql> \q

Bye

ubuntu@minion10:~$

In a browser enter the following URL to display the results of listing employee's who are of "Male Gender", have a "Birth

Date" of 1965-02-01 and a "Hire Date" of a date greater than 1990-01-01.

http://192.168.1.10/empmulti.php














