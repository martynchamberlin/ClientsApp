Clients App
=======

__Clients__ is a time tracking software built in PHP for solo entrepreneurs who charge hourly or bid by the project.

## Installation

After cloning this repository, you still need to do two things in order to fully configure your instance of this application:

1. Retrieve the [config class](https://github.com/martynchamberlin/ConfigClass) and plug in your database credentials.
2. Import the SQL file located inside this repository's `SQL` directory.
3. Insert a row into the users table with the email address and password that you want to use to log in. Be sure to use md5 encryption on the password when inserting the values. This is very easy to do inside phpMyAdmin.

## Notes

This program lacks some critical functionality that I plan to build out in the future.

1. Currently, no errors are shown when the user inputs invalid data. Instead, the same screen is displayed with no indication as to what happened. 
2. There is currently now way for new users to be created outside of phpMyAdmin (i.e., it must be done manually). Also, once a user is created, there is no way to update that user's email address or password.
