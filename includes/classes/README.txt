PHP LDAP CLASS FOR MANIPULATING ACTIVE DIRECTORY
Version 3.2

Written by Scott Barnett, Richard Hyland
email: scott@wiggumworld.com, adldap@richardhyland.com
http://adldap.sourceforge.net/

We'd appreciate any improvements or additions to be submitted back
to benefit the entire community :)

PHP Version 5 with SSL and LDAP support 

I generally install libraries and classes in a folder in the document root
called "includes/". If you want to use somewhere else, just edit the
include directives in the scripts.

The examples should be pretty self explanatory. If you require more
information, please visit http://adldap.sourceforge.net/

-------------------

For full API documentation see http://adldap.sourceforge.net/wiki/doku.php?id=api

1.  Copy adLDAP.php to your server
2.  Edit the configuring variables in the class itself if you so wish to
3.  From your script add the following code

require_once(dirname(__FILE__) . '/adLDAP.php');
$adldap = new adLDAP();

-------------------


This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

