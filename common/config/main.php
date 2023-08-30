<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // uncomment if you want to cache RBAC items hierarchy
            // 'cache' => 'cache',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'cdn' => [
            'class' => '\yii2cdn\Cdn',
            'baseUrl' => '/cdn',
            'basePath' => dirname(dirname(__DIR__)) . '/cdn',
            'components' => [
             
                'select2' => [
                    'css' => [
                        [
                            'css/select2.css',
                            '@cdn' => 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css
                            ', // online version
                        ]
                        ],
                    'js'=>[
                        [
                            'js/select2.min.js',
                            '@cdn'=>'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.js',
                        ]
                    ]
                        ],
                     
                      
            ],
        ],

        'mail' => [
            'class'            => 'zyx\phpmailer\Mailer',
            'viewPath'         => '@common/mail',
            'useFileTransport' => false,
            'config'           => [
                'mailer'     => 'smtp',
                'host'       => 'smtp.gmail.com',
                'port'       => '587',
                'smtpsecure' => 'tls',
                'smtpauth'   => true,
                'username'   => 'udomclassroom@gmail.com',
                'password'   => 'pwvkanpacmnrvlms',
            ],
            'messageConfig'    => [
                'from' => ['udomclassroom@gmail.com' => 'UDOM CLASSROOM']
               ],
        ],
        "ClassRoomMailer"=>[

            'class'=>'common\components\ClassRoomMailer'
        ],
      
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                //this rule for classwork route
               '<controller:[\w\-]+>/<action:[\w\-]+>/<cid:\w->/classwork' => '<controller>/
                <action>',
                '<controller:[\w\-]+>/<action:[\w\-]+>/<id:\w->' => '<controller>/
                <action>',
          
                 
            ],
        ],
        'hashids' => [
            'class' => 'light\hashids\Hashids',
            'salt' => 'ABDCDGAGAGA',
            'minHashLength' => 5,
            'alphabet' => 'abcdefghigLMNopkRSTuvWzyZ'
        ],

      
    
    ],
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    
        
];
