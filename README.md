# BursztynCRM

### Main description

System to manage reservations, cottages and cleaning.

##### Assumptions:

###### Already created:

- manage reservations by using calendar;
- manage cottages by using colors, names and number of guests;
- manage users - add/edit/delete users of the system;

###### To do:
- create functionality to easy find free cottages on defined date;
- manage reservations by using table with filters/searching etc.;
- create public API to find free date for reservation;
- add a new type of event "cleaning" to know how many cottages are to cleaning and who will do it;
- export reservations to XLS;
- warehouse functionality - module integrated with cleaning event;


### Install

After cloning repository you need to install packages of the serve by using the composer.

<pre>
composer install
</pre>

After this you need to install npm packages on frontend side.
<pre>
npm install
</pre>


### Used technologies

##### Backend:

- PHP 7.3
- PHPUnit
- MariaDB
- composer
- Symfony 4.4

##### Frontend
- VueJS
- CSS
- npm