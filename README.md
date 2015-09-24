ezcsvupdate
===========

Those script provide a way to export or import database data into csv in eZPublish 4.4 

eZPublish already got ezcsvexport/ezcsvimport script , but it doesn't take care of translation. 


=================
To export content
=================

Script used to export is in bin/php/ezcsvexportupdate.php
Params : 

–storage-dir  	: Folder to export csv files	(Mandatory)
  exemple : –storage-dir=export 	
   	
–siteaccess / -s 	 : Siteaccess to update	 ( Facultative )
  exemple : -s bourgogne / -s bourgogne_en 	
  default :   default siteaccess

Try case : php bin/php/ezcsvexportupdate.php –verbose 2 -s bourgogne –storage-dir=export
=====================
To import content
=====================

Script used to import is in bin/php/ezcsvimportupdate.php .
Params
–lang  : The language that will be imported on your back office 	
  exemple : –lang=eng-GB 	
  defaut : fre-FR 

–login / -l ( Facultative )	: eZPublish User owner (changement de l'owner de l'objet) 	
  exemple : -l user 	
  default : Anonymous User
  
–password / -p : 	User password 	( Facultative )
  exemple -p pwd 	
  defaut : Anonymous User

–file 	: csv file 	( Mandatory )
exemple : –file=export/news.csv 

–siteaccess / -s :	Siteaccess to update 	-s bourgogne 	( Facultative ) 	
Default : Default SiteAccess 

Try case : php bin/php/ezcsvimportupdate.php -l export -p export –verbose -s bourgogne –lang=eng-GB –file=export/news.csv 
