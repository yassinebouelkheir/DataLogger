*** To SETUP RASPBERRY ***

ALT+F12

sudo apt-get install raspberrypi-ui-mods xinit xserver-xorg
sudo apt install xrdp 

systemctl show -p SubState --value xrdp
sudo adduser xrdp ssl-cert

sudo apt-get update
sudo apt-get upgrade

sudo pip3 install mysql.connector -y 
sudo apt-get install apache2 -y

sudo usermod -a -G www-data pi
sudo chown -R -f www-data:www-data /var/www/html

sudo apt install php7.4 libapache2-mod-php7.4 php7.4-mbstring php7.4-mysql php7.4-curl php7.4-gd php7.4-zip -y

sudo apt install mariadb-server
sudo mysql_secure_installation

sudo apt install phpmyadmin

sudo mysql -u root -p

GRANT ALL PRIVILEGES ON *.* TO 'adminpi'@'localhost' IDENTIFIED BY 'adminpi' WITH GRANT OPTION;

sudo nano /etc/apache2/apache2.conf
Include /etc/phpmyadmin/apache.conf

sudo service apache2 restart

sudo apt install firefox-esr

sudo rm -rf LCD-show
git clone https://github.com/goodtft/LCD-show.git
chmod -R 755 LCD-show
cd LCD-show/
sudo ./LCD35-show


cd LCD-show/
sudo dpkg -i -B xinput-calibrator_0.7.5-1_armhf.deb

cd LCD-show/
sudo ./rotate.sh 0

mkdir /home/pi/.config/lxsession
mkdir /home/pi/.config/lxsession/LXDE-pi
cp /etc/xdg/lxsession/LXDE-pi/autostart /home/pi/.config/lxsession/LXDE-pi/
nano /home/pi/.config/lxsession/LXDE-pi/autostart

www-data ALL=(root) NOPASSWD: /sbin/reboot



