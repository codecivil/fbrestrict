# FBRestrict
FBRestrict is a workaround for providing a more granular user management on FritzBoxes.

## Caveat
While FBrestrict provides a mean to restrict users to specified admin pages of a FritzBox, it is still possible for a technically versed user to break out from this restriction and obtain full user permissions. So, please implement this software only in closely controlled environments.

## Installation
### Prepare th fbrestrict page
1. Copy the contents of server/web to your web directory, let's say /var/www/html.
2. Adapt /var/www/html/fbrestrict/config.php.
3. Adapt /var/www/html/fbrestrict/public/css/vendor.css and /var/www/html/fberror/public/css/vendor.css.

### Prepare Apache
1. Adapt server/apache2/\*.conf to your needs, matching the adaptations above.
2. Copy the contents of server/apache2 to /etc/apache2/sites-avaible
3. Enable proxy and headers modules: 
```
a2enmod proxy_http
a2enmod headers
```
4. Enable the fbrestrict pages:
```
a2ensite fb.conf fbrestrict.conf fberror.conf
```
5. Restart apache
```
systemctl restart apache2
```

Now users should be able to access (your adaptation of) http://fbrestrict.example.com and the restricted FritzBox sites via this link. They need to install the "client" given as a a Firefox extension. The source code and the signed extension are to be found in the client/firefox directory. 
