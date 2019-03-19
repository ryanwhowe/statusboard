# statusboard
This is a personal status board project that is not really written to be shared, the documentation is very spotty and the front end is in need of a makeover, which is going to be rewritten utilizing AngularJS.

Setup
-----

**Install Composer Components** - The components used in the Symfony installation require composer to install the dependencies not stored in the repository for the project.  To install the components run the following command from the project root directory.  
<code>
composer i
</code>

**Install Yarn resources** - To install the yarn dependencies execute the following in the project root directory.  
<code>
rm yarn.lock  
yarn --modules-folder web/bundles
</code>


Future
------
These are future upgrades
* Rewrite front end utilizing Angular

Security
--------
The following code is needed for generating a security token for the single log in account

<code>
php bin/console security:encode-password
</code>



Extra
-----
install yarn on the dev or production systems

<code>
curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
sudo apt-get update
sudo apt-get install yarn build-essential checkinstall libssl-dev
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.31.0/install.sh | bash
nvm install v8.9.3
</code>


Needed
------
The following is for creating and loading the database used by the calendar application.  The load is not needed for funcationality.  The database does need to be built however in order to function.  If the model is changed in any way then the database will need to be recreated.

<code>
svn export $REPO/MachineCode/MachineSettings/RaspberryPi/machines/workPi/trunk/scripts/deploy/load_calendar.csv 

svn export $REPO/MachineCode/MachineSettings/RaspberryPi/machines/workPi/trunk/scripts/deploy/deployment/statusboard/auth.json

rm app/config/parameters.yml

svn export $REPO/MachineCode/MachineSettings/RaspberryPi/machines/workPi/trunk/scripts/deploy/deployment/statusboard/app/config/parameters.yml app/config/parameters.yml
</code>

<code>
/load_calendar.csv

/auth.json

/app/config/parameters.yml

php bin/console app:buildDatabase

php bin/console app:loadCsvToDatabase load_calendar.csv
<code>
