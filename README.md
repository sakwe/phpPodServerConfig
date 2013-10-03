Logins par défaut : 

- "root" password -> "diaspora"
- "diaspora" password -> "diaspora" (utlisateur par défaut, fait partie des "sudoers")

Fichiers/dossiers importants : 

- racine diaspora : /home/diaspora/diaspora

- script diaspora : /etc/init.d/diaspora

- certificats : /home/diaspora/certicate

- backups : /home/diaspora/backup.zip

- racine paramètres du Pod : /var/www/

- auto login : /etc/inittab (ligne : 1:2345:respawn:/sbin/getty 38400 --autologin diaspora tty1)

- écran d'accueil : /usr/local/bin/dynmotd
