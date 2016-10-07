nginx:                # ID declaration
  pkg:                # state declaration
    - installed        # function declaration

mysql-server:
  pkg:
    - installed

php:
  pkg:
    - installed

php-fpm:
  pkg:
    - installed

php-mysql:
  pkg:
    - installed
