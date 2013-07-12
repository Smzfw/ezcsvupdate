ezcsvupdate
===========

This script is used for the CMS eZPublish (4.4), eZPublish already got a script ezcsvexport/ezcsvimport, but it's not used to add translation. 



Export du contenu

Le script PHP utilisé pour l'export du contenu en csv se trouve dans bin/php/ezcsvexportupdate.php
Paramètres
Params |   Description 	| Exemple d'utilisation 	| Utilité |	Défaut
–storage-dir 	Dossier dans lequel les fichiers csv vont être exporté 	–storage-dir=export 	Obligatoire 	
–siteaccess / -s 	Siteaccess à mèttre a jour 	-s bourgogne / -s bourgogne_en 	Facultative 	SiteAccess par défaut

Exemple d'utilisation : php bin/php/ezcsvexportupdate.php –verbose 2 -s bourgogne –storage-dir=export

Import du contenu

Le script PHP utilisé pour l'import du csv au contenu se trouve dans bin/php/ezcsvimportupdate.php .
Paramètres
Params |   Description   | Exemple d'utilisation 	| Utilité |	Défaut
–lang 	| Langue a laquelle le fichier csv va être importé dans le backoffice 	–lang=eng-GB 	Facultative 	fre-FR
–login / -l 	| Utilisateur avec lequel l'objet va être update (changement de l'owner de l'objet) 	-l user 	Facultative 	Utilisateur Anonymous
–password / -p | 	Mot de passe de l'utilisateur 	-p pwd 	Facultative 	Utilisateur Anonymous
–file 	| Fichier csv a importer 	–file=export/news.csv 	Obligatoire 	
–siteaccess / -s |	Siteaccess à mèttre a jour 	-s bourgogne 	Facultative 	SiteAccess par défaut

Exemple d'utilisation : php bin/php/ezcsvimportupdate.php -l export -p export –verbose -s bourgogne –lang=eng-GB –file=export/news.csv 
