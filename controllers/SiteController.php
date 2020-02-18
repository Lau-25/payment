<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout', 'user'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $params = User::find()->all();
        return $this->render('index', [
            'params'=>$params,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //return Yii::$app->response->redirect(['/site/user', 'id'=>Yii::$app->user->id]);
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionUser()
    {
        $id = Yii::$app->user->identity->id;
        $params = User::findIdentity($id);
        
        $model = new User();
        
        return $this->render('user', [
            'params' => $params,
            'model' => $model,
        ]);
    }
    
    public function actionUpdate()
    {
        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();
        try{
            $model = new User();
            $model->load(Yii::$app->request->post());
            $id = Yii::$app->request->post(id);
        
            if( $model->load(Yii::$app->request->post())){
                $id = Yii::$app->request->post(id);
                $user1 = User::findIdentity($id);
            
                if($user1->balance > -1000){
                    if($user1->nickname !== $model->nickname){
                        $sub = $user1->balance - round($model->balance, 2);
                        $user1->balance = $sub;
                        $user1->save();
                
                        $user2 = User::findByUsername($model->nickname);
                        $sum = $user2->balance + round($model->balance, 2);
                        $user2->balance = $sum;
                        $user2->save();
                    }
                } else {
                    echo 'nedostatochno sredstv';
                }
            };
        } catch (Exception $e) {
            $transaction->rollback();
        }
        
        return Yii::$app->response->redirect(['/site/user', 'id' => $user1->id]);;
    }
}
