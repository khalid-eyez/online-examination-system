<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Africa/Dar_es_Salaam',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'dateTimeConversion' => [
            'class' => 'userlogs\components\DateTimeHelper'
],
// 'redis' => [
//     'class' => 'yii\redis\Connection',
//     'hostname' => 'localhost',
//     'port' => 6379,
//     'database' => 0,
// ],
// 'cache' => [
//     'class' => 'yii\redis\Cache',
// ],
'cache' => [
    'class' => 'yii\caching\FileCache',
],
//  'session' => [
//     'class' => 'bscheshirwork\redis\Session',
//     'redis' => 'redis',
//     'name' => 'advanced-frontend'
//  ],
 'session' => [
   
    'name' => 'advanced-frontend'
 ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => [ 'auth/login' ],
            'authTimeout' => 1800,
        ],
        'log' => [
            //'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'auth/error',
        ],
        'urlManager' => [
            'class' => '\yii\web\UrlManager',
             'enablePrettyUrl' => true,
             'showScriptName' => false,
             'rules' => [
                '/'=>'/login',
                '/help'=>'/home/help',
                '/login'=>'/auth/login',
                '/appz'=>'/home/apps',
                '/register'=>'/auth/register',
                
                // url rules for the portal module
                [

                    'class' => '\yii\web\GroupUrlRule',
                    'rules'  => [
                        '/dashboard'     => '/portal/instructor/dashboard',
                        '/changepassword' => '/portal/portal/changepassword',
                        '/courses'     => '/portal/instructor/courses',
                        '/password-change' =>'/portal/portal/change-password-restrict',
                        '/course/partners' =>'/portal/instructor/partners',
                        '/student/dashboard' =>'/portal/student/dashboard',
                        '/curriculum' => '/portal/student/courses',
                        '/carryovers' => '/portal/student/carrycourse',
                        '/student/profile' => '/portal/studentprofile/view',
                        '/student/profile/update' => '/portal/studentprofile/update',
                        '/short-courses'=>'/portal/student/short-courses'
            
            
                    ],
            
                ],

                /// url rules for forum module

                [

                    'class' => '\yii\web\GroupUrlRule',
                    'rules'  => [
                        '/new-thread'     => '/forum/forum/add-thread',
                     
            
            
                    ],
            
                ],

                // url rules for the awards  modules
                [

                    'class' => '\yii\web\GroupUrlRule',
                    'rules'  => [
                        '/course/awards'     => '/awards/awards/awards',
                        '/student/awards' =>'/awards/awards/downloaded-awards'
                     
            
            
                    ],
            
                ],

                //url rules for the academics modules
                    [

                        'class' => '\yii\web\GroupUrlRule',
                        'rules'  => [
                            '/department/programs'     => '/academics/academics/create-program',
                            '/department/students' =>'/academics/academics/student-list',
                            '/department/courses'=>'academics/academics/create-course',
                            '/department/instructors'=>'/academics/academics/instructor-course',
                            '/department/short-courses'=>'/academics/academics/short-course'
                         
                
                
                        ],
                
                    ]

            ],
        ],

        'assetManager' => [
            'bundles' => [

                'yii\bootstrap\BootstrapAsset' => FALSE,

            ],
            'appendTimestamp' => true
        ]
        
    ],
    'modules' => [
        'auditlog' => [
                    'class' => 'frontend\userlogs\AuditEntryModule'
        ],
        // 'debug' => [
        //     'class' => 'yii\debug\Module', //should be disabled on production
        // ],
        'portal' => [
            'class' => 'frontend\modules\portal\Portal',
        ],
        'coursemonitor' => [
            'class' => 'frontend\modules\coursemonitor\coursemonitor',
        ],
        'content' => [
            'class' => 'frontend\modules\content\Content',
        ],
        'forum' => [
            'class' => 'frontend\modules\forum\Forum',
        ],
        'CA' => [
            'class' => 'frontend\modules\CA\CA',
        ],
        'academics' => [
            'class' => 'frontend\modules\academics\Academics',
        ],
        'classmanager' => [
            'class' => 'frontend\modules\classmanager\ClassManager',
        ],
        'onlinelectures' => [
            'class' => 'frontend\modules\onlinelectures\OnlineLectures',
        ],
        'assessments' => [
            'class' => 'frontend\modules\assessments\Assessments',
        ],
        'admin' => [
            'class' => 'frontend\modules\admin\Admin',
         ],
         'awards' => [
            'class' => 'frontend\modules\awards\Awards',
        ],
        'course-monitor' => [
            'class' => 'frontend\modules\coursemonitor\CourseMonitor',
        ],
        'gridview' =>  [
            'class' => 'kartik\grid\Module'
        ],
        'dynagrid'=> [
            'class'=>'kartik\dynagrid\Module'
        ],
    ],
    'defaultRoute' => 'auth',
    'params' => $params,
];
