#!/usr/bin/env php
<?php
//
// Definition of eZCsvimport class
//
// Created on: <27-Sep-2006 22:23:27 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish Community Project
// SOFTWARE RELEASE:  4.2011
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
// 
//   This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
// 
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file
*/

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish CSV import script\n\n" .
                                                        "\n" .
                                                        "\n" .
                                                        "\n" .
                                                        "\n" .
                                                        "" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[lang:][folder:][file:]",
                                "",
                                array( 'file' => 'file to read CSV data from',
									   'lang' => 'language of the object',
                                       'folder' => 'path to directory which will be added to the path of CSV elements' ),
                                false,
                                array( 'user' => true ));
$script->initialize();

$inputFileName = $options['file'];
$lang = $options['lang'];
$storageDir = $options['folder'];

if ( is_null($lang) || $lang=='') {
	$lang = 'fre-FR';
}

if (!is_null($storageDir) && $storageDir != '') {
	$dir = opendir($storageDir);
	if(!$dir) {
		$cli->error( "Folder not found" );
		$script->shutdown( 1 );
	}
	
	$files= array(); // on déclare le tableau contenant le nom des fichiers
	
	getFiles($storageDir,$files);
	
	foreach($files as $file) {
		importFile($file);
	}

	closedir($dir);
	
	

}
elseif ( !is_null($inputFileName) && $inputFileName!='') {
    importFile($inputFileName);
}
else {
	$cli->error( "Need a file to read data from" );
    $script->shutdown( 1 );
}





$script->shutdown();

function getFiles($folder,&$files) {
	$dir = opendir($folder);
	while($element = readdir($dir)) {
		if($element != '.' && $element != '..') {
			
			if (!is_dir($folder.'/'.$element)) {
				$files[] = $folder.'/'.$element;			
			}
			else {
				getFiles($folder.'/'.$element,$files);
			}
		}
	}
}

function importFile($fileName) {
	global $cli,$lang;
	$csvLineLength = 100000;
	
	$cli->output( "Going to import objects from file $fileName\n" );

	$fp = @fopen( $fileName, "r" );
	if ( !$fp )
	{
		$cli->error( "Can not open file $fileName for reading" );
		$script->shutdown( 1 );
	}

	$firstline = true;

	while ( $objectData = fgetcsv( $fp, $csvLineLength , ';', '"' ) ) {

		// Recupere les attributs du csv qui se trouve normalement sur la premiere ligne.
		if ($firstline) {
			$attributeList = $objectData;
			$firstline = false;
			continue;
		}

		// Recupere l'object ID
		$current_node_id = $objectData[0];
		
		// Recupere l'objet de la ligne du csv
		$contentObject = eZContentObject::fetch( $current_node_id,true );
		
		// Récuperation du parent_node_id
		$mainNodeID = $contentObject->MainNodeID();
		$mainNode =  eZContentObjectTreeNode::fetch( $mainNodeID );
		$parentNodeID = $mainNode->ParentNodeID;
		
		// Si il n'y a pas de parents.
		if($parentNodeID === '') {
			$parentNodeID = $mainNodeID;
		}
		
		
		if( $contentObject instanceof eZContentObject ) {
		
			// Instantation du paramètre utilisé pour la fonction updateAndPublishObject
			$params = array(
				'creator_id'       => $contentObject->OwnerID,
				'class_identifier' => $contentObject->ClassIdentifier,
				'parent_node_id'   => $parentNodeID,
				'language'   => $lang
			);
			
			// Récuperation des attributs de l'objet
			$contentObjectDataMap = $contentObject->DataMap();
			
			// Index pour les collonnes du csv (utilisé pour objectData)
			$i = 1;
			
			// On parcours tous les attribut de l'objet
			foreach ($contentObjectDataMap as $key=>$attribute) {
				
				// Resultat de l'attribut du params
				$resultData = '';
				// Identifiant de la classe
				$datatypeString = $attribute->DataTypeString;
				
				// Contenu de l'attribut
				$attributeStringContent = $attribute->toString();
				
				// Si l'attribut de l'objet est utilisé par le csv
				if (in_array($key,$attributeList)) {
					// Si le contenu du csv n'est pas vide.
					if($objectData[$i] != '') {
						switch ( $datatypeString ) {
							case 'ezimage':
							{

								$imageFileAltFR = explode( '|', $attributeStringContent );
								$imageFile = $imageFileAltFR[0];
								$imageAltTrad = $objectData[$i];
								$imageFileAltTrad = $imageFile.'|'.$imageAltTrad;
								// var/siteaccess/images/****/***/jpg| Texte alternatif de l'image
								$resultData = $imageFileAltTrad;
							} break;
							case 'ezxmltext':
							{
								
								$resultData = '<?xml version="1.0" encoding="utf-8"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">'.$objectData[$i];
								$resultData = str_replace('<line>','<line xmlns:tmp="http://ez.no/namespaces/ezpublish3/temporary/">',$resultData);
							} break;
							default :
								$resultData = $objectData[$i];
						}
						
						
					}
					
					// On passe a une autre colonne du csv
					$i += 1;
				}
				// Si l'attribut de l'objet n'est pas utilisé par le csv, on recupere celui de la version FR
				else {
					$resultData = $attributeStringContent;
				}
				
				$params['attributes'][$key] = mb_convert_encoding($resultData, 'UTF-8','ISO-8859-15');
				//$params['attributes'][$key] = $resultData;
			}
			$result = eZContentFunctions::updateAndPublishObject( $contentObject, $params );
			if( $result ) echo "L'objectID ".$current_node_id." est mis a jour\n"; else echo "modification refusee"; 
		}
	}


	fclose( $fp );
	
	
}

?>