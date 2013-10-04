The curent projet is unusable.
------------------------------

Goal : 

- run a VM pre-installed and configured for a Diaspora* Pod hosting
- configure local ip, domain name, dydns/dynovh, ssl, Diaspora*, mail and services
- apply to system configuration : passwords, network, apache, diaspora and ipcheck(.py)
- quikly obtain an operational Diaspora Pod 

This project is complement with a (Debian) Virtual (Server) Machine

I'll tell more in the README and README-DEV next time ;-)

Current uncategorised personal notes : 

	Default login : 

	- "root" password -> "diaspora"
	- "diaspora" password -> "diaspora" (defaul user member of "sudoers")

	Files and directories : 

	- diaspora root : /home/diaspora/diaspora

	- script diaspora : /etc/init.d/diaspora

	- certificates : /home/diaspora/certicate

	- backups : /home/diaspora/backup.zip

	- auto login : /etc/inittab (ligne : 1:2345:respawn:/sbin/getty 38400 --autologin diaspora tty1)

	- console home screen : /usr/local/bin/dynmotd
