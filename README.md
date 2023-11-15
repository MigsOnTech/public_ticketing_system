## Create Database 
create database `ciproj`
## Port
The localhost port should be set to `8080`.
## Url Domain
Local API endpoint at `http://localhost:8080/ciproj`
## Setup
Project content must be uploaded in `htdocs/ciproj` folder
run `git pull https://github.com/MigsOnTech/public_ticketing_system.git`
run `composer install`
run `php spark migrate` - migrate all tables
run `php spark db:seed AddDefaultAccountSeeder` - will create default `admin account`
## Default Account
Email : `admin@gmail.com`
Password : `admin`
## Access the Ticketing Website
[Ticketing Web](https://ticketing_web_app.stormkit.dev) - `Please be aware that the URL is subject to modification based on any subsequent changes.`
## Front-end Hosting Used
`Stormkit`

## Error Encounter During Testing
Error Blocked by Client - Resolution: Deactivate ad-blocking plugin Check more details at `https://community.klaviyo.com/developer-group-64/can-anyone-help-me-understand-what-net-err-blocked-by-client-in-chrome-dev-tools-console-when-loading-pages-means-3612`
