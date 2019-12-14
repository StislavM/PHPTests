# PHPTests
Some test on PHPUnit and Selenium using [Steward](https://github.com/lmc-eu/steward)

All tests artifacts stored in **logs** directory. 


http://localhost:8080 - Graphical user interface for Selenoid project

http://localhost:4444/status - Selenoid Statistic for Grafana 

# Deploy Selenoid
1. Replace **{PWD}** in **selenoid/.env** with absolute pass to project dir.
2. Configure **browsers.json** if you need some special browsers or settings
3. Add all docker images **name:tag** used in **browsers.json** to **required_images.txt**
4. Run `sh pull_images.sh` It will pull all required docker images to your system
5. Start `docker-compose up`

# Start tests
1. `composer install && composer dump`
2. For start test you need to do command
`./vendor/bin/steward run production chrome`
for more information use 
`./vendor/bin/steward run --help`
3. For test results 
`./vendor/bin/steward -vvv results`

#Additional
You can add some capability to **steward run**
**--capability="enableVNC:true"** - enable VNC for selenium-ui
 
**--capability="enableLog:true"** - store selenoid log data to **logs/selenoid-logs/**
 
**--capability="enableVideo:true"** - store video-records of selenium tests to **logs/video**
