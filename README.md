# statusboard
This is a personal status board project that is not really written to be shared

Setup
-----

**Install Composer Components** - The components used in the Symfony installation require composer to install the dependencies not stored in the repository for the project.  To install the components run the following command from the project root directory.  
<code>
composer install
</code>

**Install Yarn resources** - To install the yarn dependencies execute the following in the project root directory.  
<code>
rm yarn.lock  
yarn --modules-folder web/bundles
</code>


Future
------
These are future upgrades
* Database Storage
* Custom composer install data pull for internal library from personal repo


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
