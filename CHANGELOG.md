# Changelog

#### 1.6.0

Integrated Node.js and Gulp 
- Added package.json with gulp dependencies
- Added a gulpfile with common functions and a config file for file paths and globs
- Switched to yarn to install npm modules


#### 1.5.0

Dockerize using nginx, PHP7 and FPM


#### 1.4.1

* Fix directory name issues in yii file *2017-02-07*
* Correct typo in SMTP config *2017-02-07*


#### 1.4.0

* Add dependency injection (DI) *2016-10-19*


#### 1.3.1

* Fix issues with deployer REPO_URL and SLACK_HOOK_URL *2016-09-16*


#### 1.3.0

* Fix issues with deployer script *2016-09-16*
* Add yii2 composer config task *2016-09-16*
* Create sample server.yml for deployer *2016-09-16*
* Remove deploy settings from env file *2016-09-16*
* Add yii2-utils as composer dependency *2016-09-16*


#### 1.2.1

* Fix issue with postCreateProject directories in composer.json *2016-07-23*


#### 1.2.0

* Set header X-Content-Type-Options nosniff *2016-07-22*


#### 1.1.0

* Move core application files into app folder *2016-06-21*
* Add sample ant build.xml file for easy continuous integration *2016-06-21*


#### 1.0.0

* Add Deployer [Deployer](http://deployer.org)  template *2016-06-20*
* Use dotenv library to load environment variables *2016-06-20*
* Set route for New Relic monitoring *2016-06-20*
* Added helper methods to Base Controller  *2016-06-20*
* Add security headers to protect against click jacking and XXS  *2016-06-20*
* Minify HTML for non dev environment *2016-06-20*

