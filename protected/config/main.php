<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Call Center Kia',
    'defaultController' => 'site/login',
    'language' => 'es',
    'sourceLanguage' => 'es',
    'charset' => 'utf-8',
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.easyimage.EasyImage'
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
    // application components
    'components' => array(
        'ePdf' => array(
            'class' => 'ext.yii-pdf.EYiiPdf',
            'params' => array(
                'mpdf' => array(
                    'librarySourcePath' => 'application.extensions.vendors.mpdf.*',
                    'constants' => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class' => 'mpdf', // the literal class filename to be loaded from the vendors folder
                    'defaultParams' => array(// More info: http://mpdf1.com/manual/index.php?tid=184
                        'mode' => '', //  This parameter specifies the mode of the new document.
                        'format' => 'A4', // format A4, A5, ...
                        'default_font_size' => 18, // Sets the default document font size in points (pt)
                        'default_font' => '', // Sets the default font-family for the new document.
                        'mgl' => 30, // margin_left. Sets the page margins for the new document.
                        'mgr' => 15, // margin_right
                        'mgt' => 10, // margin_top
                        'mgb' => 10, // margin_bottom
                        'mgh' => 9, // margin_header
                        'mgf' => 9, // margin_footer
                        'orientation' => 'P', // landscape or portrait orientation
                    )
                ),
                'HTML2PDF' => array(
                    'librarySourcePath' => 'application.extensions.vendors.html2pdf.*',
                    'classFile' => 'html2pdf.class.php', // For adding to Yii::$classMap
                /* 'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
                  'orientation' => 'P', // landscape or portrait orientation
                  'format'      => 'A4', // format A4, A5, ...
                  'language'    => 'en', // language: fr, en, it ...
                  'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
                  'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
                  'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
                  ) */
                )
            ),
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'defaultRoles' => array('authenticated', 'admin'),
        ),
        'excel' => array(
            'class' => 'application.extensions.excelreader.reader',
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'db' => array(
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
        ),
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=callcenter',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'k143c89?4Fg&2',
//            'username' => 'kia1234',
//            'password' => 'kia1234',
            'charset' => 'utf8',
        ),
         'db2' => array(
          'connectionString' => 'mysql:host=localhost;dbname=adminkia_b4s3k1',
          'class'=>'CDbConnection',
          'emulatePrepare' => true,
          'username' => 'root',
          'password' => 'k143c89?4Fg&2',
//          'username' => 'kia1234',
//          'password' => 'kia1234',
          'charset' => 'utf8',
          ), 
        'image' => array(
            'class' => 'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver' => 'GD',
            // ImageMagick setup path
            'params' => array('directory' => '/opt/local/bin'),
        ),
        'simpleImage' => array(
            'class' => 'application.extensions.CSimpleImage',
        ),
        'easyImage' => array(
            'class' => 'application.extensions.easyimage.EasyImage',
        //'driver' => 'GD',
        //'quality' => 100,
        //'cachePath' => '/assets/easyimage/',
        //'cacheTime' => 2592000,
        //'retinaSupport' => false,
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
//        'ePdf' => array(
//            'class' => 'ext.yii-pdf.EYiiPdf',
//            'params' => array(
//                'HTML2PDF' => array(
//                    'librarySourcePath' => 'application.vendors.html2pdf.*',
//                    'classFile' => 'html2pdf.class.php', // For adding to Yii::$classMap
//                /* 'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
//                  'orientation' => 'P', // landscape or portrait orientation
//                  'format'      => 'A4', // format A4, A5, ...
//                  'language'    => 'en', // language: fr, en, it ...
//                  'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
//                  'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
//                  'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
//                  ) */
//                )
//            ),
//        ),
    /* 'clientScript' => array(
      'scriptMap' => array(
      'jquery.js' => false,
      'jquery.min.js' => false,
      ),
      ), */
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'recaptcha' => array(
            'publicKey' => '6LdpfAYTAAAAAH5QImM0Uzy3Hn1uyF6EAWMbWb89',
            'privateKey' => '6LdpfAYTAAAAACR8BH2nzw-zy5uPb00HXY1TFQWZ',
        ),
        'listPerPage' => 5
    ),
);
