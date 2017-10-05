#!/usr/bin/env bash

Update () {
    echo "-- Update packages --"
    sudo apt-get update
    sudo apt-get upgrade
}
Update

echo "-- Prepare configuration for MySQL --"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password root"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password root"

echo "-- Install tools and helpers --"
sudo apt-get install -y --force-yes python-software-properties vim htop curl git npm

echo "-- Install PPA's --"
sudo add-apt-repository ppa:ondrej/php
sudo add-apt-repository ppa:chris-lea/redis-server
Update

echo "-- Install NodeJS --"
curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -

echo "-- Install packages --"
sudo apt-get install -y --force-yes build-essential apache2 mysql-server git-core nodejs rabbitmq-server redis-server emacs24
sudo apt-get install -y --force-yes php7.1-common php7.1-dev php7.1-json php7.1-opcache php7.1-cli libapache2-mod-php7.1 php7.1 php7.1-mysql php7.1-fpm php7.1-curl php7.1-gd php7.1-mcrypt php7.1-mbstring php7.1-bcmath php7.1-zip
Update

echo "-- Configure PHP &Apache --"
sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.1/apache2/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.1/apache2/php.ini
sudo a2enmod rewrite

echo "-- Creating virtual hosts --"
sudo ln -fs /vagrant/public/ /var/www/app
cat << EOF | sudo tee -a /etc/apache2/sites-available/default.conf
<Directory "/var/www/">
    AllowOverride All
</Directory>

<VirtualHost *:80>
    DocumentRoot /var/www/app
    ServerName app.dev
</VirtualHost>

<VirtualHost *:80>
    DocumentRoot /var/www/phpmyadmin
    ServerName phpmyadmin.dev
</VirtualHost>
EOF
sudo a2ensite default.conf

echo "-- Restart Apache --"
sudo /etc/init.d/apache2 restart

echo "-- Install Composer --"
curl -s https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

echo "-- Install phpMyAdmin --"
wget -k https://files.phpmyadmin.net/phpMyAdmin/4.0.10.11/phpMyAdmin-4.0.10.11-english.tar.gz
sudo tar -xzvf phpMyAdmin-4.0.10.11-english.tar.gz -C /var/www/
sudo rm phpMyAdmin-4.0.10.11-english.tar.gz
sudo mv /var/www/phpMyAdmin-4.0.10.11-english/ /var/www/phpmyadmin

echo "-- Setup databases --"
mysqladmin -u root -proot create homestead
mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON *.* TO 'homestead'@'%' IDENTIFIED BY 'secret' WITH GRANT OPTION; FLUSH PRIVILEGES;"