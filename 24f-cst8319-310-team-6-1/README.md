# CST8334 Software Development Project
## Client
Nadia Hosseinzadeh-hello@appyyo.com 
Jonathan Osmond
## Authors
Iuliia Obukhova-obuk0001@algonquinlive.com
Mohammadhassan Yeganeshenas-Yega0004@algonquinlive.com​
Hang Lin- Lin00197@algonquinlive.com
Nikolas Simeunovic-sime0023@algonquinlive.com
## Description
This web application connects gig workers with employers.

## Prerequisites
Visual Studio Code
Xammp 
MySQL
Twillio Trial Account
## Setting up the local environment
Visual Studio Code:
1.Install VSC
2.Install all php extensions
Xammp:
Install Xammp
Extract Project folder to xammp/htdocs
Open Xammp Control Panel and Start Apache module and MySQL module.
Configure php.ini and sendmail.ini with a valid smtp username, password and server
MySQL:
Start MySQL server
Run SQL file SQL3Workbench.sql from program in Workbench.
-- i Run LinkedInUser from SQL3.sql 
Twillio:
Go to Twillio Page
Create a Twillio Trial Account
Install TwilioSDK by: composer require twilio/sdk
Get Activation Code "Develop>Messaging>Try It Out>Send an SMS>API View
Register Number "Develop>Phone Numbers>Manage>Verified Caller IDs>add a new caller id
## Running the application
1.Complete Steps Prior to this section.
2.Update database connectivity variables in credentials.php and db-connect.php
3.Load Index.php
4.Create a GIG worker or Gig Employer account
5.Complete Questionaire
6.Verify your account using email and sms.
7.Now you can view your profile.
## Future Recommendations
Buy Phone API

