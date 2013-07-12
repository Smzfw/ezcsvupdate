ezcsvupdate
===========

This script is used for the CMS eZPublish (4.4), eZPublish already got a script ezcsvexport/ezcsvimport, but it's not used to add translation. 



Export du contenu

Script used to export is in bin/php/ezcsvexportupdate.php
Params : 

–storage-dir 	: Folder to export csv files	
  exemple : –storage-dir=export 	
  Obligatoire 	
–siteaccess / -s 	: Siteaccess to update	
  exemple : -s bourgogne / -s bourgogne_en 	
  Facultative 
  defaut :   defaut siteaccess

Exemple d'utilisation : php bin/php/ezcsvexportupdate.php –verbose 2 -s bourgogne –storage-dir=export

Import du contenu

Script used to import is in bin/php/ezcsvimportupdate.php .
Params
–lang : Langue a laquelle le fichier csv va être importé dans le backoffice 	
  exemple : –lang=eng-GB 	
  Facultative 	
  defaut : fre-FR
–login / -l 	: Utilisateur avec lequel l'objet va être update (changement de l'owner de l'objet) 	
  exemple : -l user 	
  Facultative 	
  defaut : Anonymous User
–password / -p : 	Mot de passe de l'utilisateur 	
  exemple -p pwd 	
  Facultative 	
  defaut : Anonymous User
–file 	: Fichier csv a importer 	–file=export/news.csv 	Obligatoire 	
–siteaccess / -s :	Siteaccess à mèttre a jour 	-s bourgogne 	Facultative 	SiteAccess par défaut

Exemple d'utilisation : php bin/php/ezcsvimportupdate.php -l export -p export –verbose -s bourgogne –lang=eng-GB –file=export/news.csv 
