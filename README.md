Clients App
=======

__Clients__ is a time tracking software built in PHP for solo entrepreneurs who charge hourly or bid by the project.

## Installation

1. Fork into a local directory that is inside your Apache server. On Mac OS X, this directory is `/Library/Webserver/Documents/`.
2. Import `sql/clients.sql` file into a database via phpMyAdmin
3.  Configure `class/config.php` with your local database credentials
4. You're ready to log in! Use "test@asdf.com" and "password" for the login credentials. 

## Notes

This program lacks some critical functionality that I plan to build out in the future.

1. Currently, no errors are shown when the user inputs invalid data. Instead, the same screen is displayed with no indication as to what happened. 
2. There is currently now way for new users to be created outside of phpMyAdmin (i.e., it must be done manually). Also, once a user is created, there is no way to update that user.

A project like this makes a lot more sense in Rails, so I may rebuild it in that as my skills progress.