# statusboard
This is a personal status board project that was not written to be shared, the documentation is very spotty and the front end is in need of a makeover, which is going to be rewritten utilizing React.js with an updated backend upgraded to either laravel or symfony 5.

#Setup Local Dev
Setup has been updated to utilize docker for local development.

###Create a Docker Override file
copy the override example file to create your own local version
```shell script
cp docker-compose.override.yml.example docker-compose.override.yml
```
The path placeholder needs to be updated 
```shell script
replace <path_to_solution> with the absolute path to your solution files
```

###Create Parameters file
This file need to be created and have the secrets added, or it needs to be pulled from archives
```shell script
cd app\config
cp parameters.yml.dist parameters.yml
```

##Bring up the Docker config
```shell script
docker-compose up -d --build
```
Build the dependencies for the project
```shell script
docker-compose exec php composer i
```
Build the local sqlite database and seed the data using the fixtures. 

**<span style="color:red">WARNING:</span>** This command will drop and recreate the database if there is already a database file present.
```shell script
docker-compose exec php bin/setup
```

##Security
The following code is for generating a security token for the single log in account

```shell script
php bin/console security:encode-password
```


##Additional Utilities
There are some additional console utilities created for maintenance

###Data Exports
####Calendar Dump
```shell script
docker-compose exec php bin/console app:ExportCaledarToCsv <outputFilename>
```
####Server Dump
```shell script
docker-compose exec php bin/console app:ExportServerToCsv <outputFilename>
```

###Calendar Utilities
#### GeneratePayDates
The script will generate pay dates for the optionally passed year, or will generate them starting from 2007 to the current year.  This utility will not create duplicate records, if a pay date already exists for one it generates it does not get written to the Database.  For any year that is run, there should already be holidays imported for (both national and company), this data is required for determining the proper pay dates.
```shell script
docker-compose exec php bin/console app:GeneratePayDates [year]
```
####ImportHoliday
The script utilizes the free [Calendarific](https://calendarific.com/) api to load national US holidays into the database.  If the optional year is specified that will be used otherwise all years from 2007 to present will be loaded.  This will not create dupliate entries, if a national holiday already exists for a given date it will not be added to the database.
```shell script
docker-compose exec php bin/console app:ImportHoliday [year]
```

###Deprecated Utilities
These utilities have been deprecated and are not currently used
####GetWeatherImages
This locally cached the Accuweather images files for use with their API.  These images were too large, the implementation has been changed to utilize a weather font resource.
```shell script
docker-compose exec php bin/console app:GetWeatherImages
```

####yarnInstall
The front end assets previously were installed utilizing `yarn`, this has been update to utilize the run script via `npm`
```shell script
docker-compose exec php bin/console app:yarnInstall
```
