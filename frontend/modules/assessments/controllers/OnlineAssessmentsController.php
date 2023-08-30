<?php

namespace frontend\modules\assessments\controllers;

use common\exceptions\QuizRestrictedException;
use common\models\Quizaccesstokens;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;
use frontend\models\ClassRoomSecurity;
use yii\base\Exception;
use frontend\modules\assessments\models\QuizManager;
use yii\web\UploadedFile;
use common\models\Quiz;
use common\models\StudentQuiz;
use yii\base\UserException;
use frontend\modules\assessments\models\Invigilator;
use frontend\modules\assessments\models\Vfp;

class OnlineAssessmentsController extends \yii\web\Controller
{
    //public $layout = 'instructor';
       /**
        * {@inheritdoc}
        */
    //################################# public $layout = 'admin'; #####################################

    public $defaultAction = 'dashboard';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [

                            'class-quizes',
                            'new-quiz',
                            'questions-bank',
                            'questions-bank2',
                            'new-question',
                            'save-question',
                            'delete-question',
                            'create-quiz',
                            'delete-quiz',
                            'quiz-preview',
                            'scores-view',
                            'download-bank',
                            'questions-uploader',
                            'download-quiz-pdf',
                            'update-quiz',
                            'chapter-bank',
                            'empty-chapter-bank',
                            'chapter-question-save',
                            'chapter-bank-download',
                            'chapter-questions-uploader',
                            'download-chapter-bank',
                            'update-question',
                            'quiz-manual-marking',
                            'edit-score',
                            'delete-score',
                            'get-markables',
                            'tokens',
                            'get-tokens-pdf',
                            'delete-all-tokens',
                            'delete-token',
                            'download-markable-submits',
                            'get-marked-perc',
                            'get-student-vfps',
                            'student-submits-pdf'


                        ],
                        'allow' => true,
                        'roles' => ['INSTRUCTOR']


                    ],
                    // ############################### THIS PART FOR 'INSTRUCTOR $ HOD ROLE' ######################################
                    [
                        'actions' => [

                           'class-quizes',
                           'new-quiz',
                           'questions-bank',
                           'questions-bank2',
                           'save-question',
                           'delete-question',
                           'create-quiz',
                           'delete-quiz',
                           'quiz-preview',
                           'scores-view',
                           'download-bank',
                           'questions-uploader',
                           'download-quiz-pdf',
                           'update-quiz',
                           'chapter-bank',
                           'empty-chapter-bank',
                           'chapter-question-save',
                           'chapter-bank-download',
                           'chapter-questions-uploader',
                           'download-chapter-bank',
                           'update-question',
                           'quiz-manual-marking',
                           'edit-score',
                           'delete-score',
                           'get-markables',
                           'tokens',
                           'get-tokens-pdf',
                           'delete-all-tokens',
                           'delete-token',
                           'download-markable-submits',
                           'get-marked-perc',
                           'get-student-vfps',
                           'student-submits-pdf'
                        ],
                        'allow' => true,
                        'roles' => ['INSTRUCTOR & HOD']


                    ],
                    [
                        'actions' => [

                            'logout',
                            'student-quizes',
                            'quiz-take',
                            'submit-quiz',
                            'update-quiz-timer',
                            'quiz-rules',
                            'verify-token',
                            'record-frontend-vfps'


                        ],
                        'allow' => true,
                        'roles' => ['STUDENT']


                    ],

                ],
            ],
             'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'dropcourse' => ['post'],
                ],
            ],
        ];
    }
    /**
     * retrieves all quizes / online assessments done in a given course
     * and renders the index page to display them
     *
     * @return string the rendered page
     * @author khalid  <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionClassQuizes()
    {
        $quizzes = Quiz::find()->orderBy(['quizID' => SORT_DESC])->all();
        return $this->render('index', ['quizzes' => $quizzes]);
    }
    /**
     * renders the quiz creation form / page
     *
     * @return string the rendered page
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionNewQuiz()
    {
        return $this->render('newquiz.php');
    }
    /**
     * renders the questions bank page to display all questions
     * and create new ones
     *
     * @return string the rendered page
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionQuestionsBank()
    {
        return $this->render('questionsBank');
    }
    /**
     * retrieves all chapter questions and renders a page to display them
     *
     * @return string the rendered page
     * @param  mixed $chapter the encrypted chapter identification
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionChapterBank($chapter)
    {
        $chapterplain = ClassRoomSecurity::decrypt($chapter);
        $bank = (new QuizManager())->rawChapterBankReader($chapterplain);
        return $this->render('chapterBank', ['bank' => $bank,'chapter' => $chapter]);
    }
    /**
     * Downloads the chapter bank, all questions under a given chapter
     *
     * @param  mixed $chapter tha chapter name to be downloaded
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionChapterBankDownload($chapter)
    {
        $chapter = ClassRoomSecurity::decrypt($chapter);
        (new QuizManager())->chapterBankDownload($chapter);
    }
    /**
     * saves a new question in a given chapter
     *
     * @param  string $chapter the encrypted name of the chapter in which the question
     *                         has to be saved
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionChapterQuestionSave($chapter)
    {
        $chapter = ClassRoomSecurity::decrypt($chapter);
        $data = yii::$app->request->post();
        $data['optionImage'] = UploadedFile::getInstancesByName('optionImage');
        $data['questionImage'] = UploadedFile::getInstancesByName('questionImage');
        $data['chapter'] = $chapter;

        $manager = new QuizManager($data);

        try {
            if ($manager->questionSave()) {
                yii::$app->session->setFlash("success", "<i class='fa fa-info-circle'></i> Question Added Successfully !");
                return $this->redirect(yii::$app->request->referrer);
            }
        } catch (\Exception $q) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> Could Not Add Question, Try Again Later !" . $q->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }
    }
    /**
     * deletes all questions from the chapter bank
     *
     * @return \yii\web\response
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionEmptyChapterBank()
    {
        $chapter = yii::$app->request->post('chapter');
        if ((new QuizManager())->emptyChapterBank($chapter)) {
            return $this->asJson(['message' => 'Chapter Bank cleared successfully !']);
        } else {
            return $this->asJson(['failed' => 'Chapter Bank could not be cleared !']);
        }
    }
    /**
     * renders the questionsbank for the quiz creation page
     *
     * @author khalid <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionQuestionsBank2()
    {
        return $this->render('questionsBank2');
    }

    /**
     * renders the page for adding new questions
     *
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionNewQuestion()
    {
        return $this->render('newQuestion');
    }
    /**
     * saves questions to the questions bank
     *
     * @deprecated version 3.0.0  as questions have to be save per chapter / module today
     * @return     \yii\web\Response|void redirects back to the requesting page
     * @author     khalid hassan <thewinner016@gmail.com>
     * @since      2.0.0
     */
    public function actionSaveQuestion()
    {
        $data = yii::$app->request->post();
        //print_r($data); return false;
        $data['optionImage'] = UploadedFile::getInstancesByName('optionImage');
        $data['questionImage'] = UploadedFile::getInstancesByName('questionImage');

        $manager = new QuizManager($data);

        try {
            if ($manager->questionSave()) {
                yii::$app->session->setFlash("success", "<i class='fa fa-info-circle'></i> Question Added Successfully !");
                return $this->redirect(yii::$app->request->referrer);
            }
        } catch (UserException $q) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> Could Not Add Question! " . $q->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        } catch (\Exception $e) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> An error occurred while adding question! try again later");
            return $this->redirect(yii::$app->request->referrer);
        }
    }
    /**
     * updates the question
     *
     * @param  string $q encrypted question ID
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionUpdateQuestion($q)
    {
        $q = ClassRoomSecurity::decrypt($q);

        try {
            $question = (new QuizManager())->findQuestion($q);
            if (yii::$app->request->isPost) {
                $buffer = yii::$app->request->post();

                $manager = new QuizManager($buffer);

                if ($manager->updateQuestion($q)) {
                    yii::$app->session->setFlash("success", "<i class='fa fa-info-circle'></i> Question Update Successful");
                    return $this->redirect("questions-bank");
                }
            }
            return $this->render('questionUpdate', ['buffer' => $question]);
        } catch (UserException $u) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> Question Update failed !" . $u->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        } catch (Exception $f) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> Question Update failed !" . $f->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }
    }
    /**
     * deletes a question
     *
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionDeleteQuestion()
    {
        $question = yii::$app->request->post("question");

        if ((new QuizManager())->deleteQuestion($question)) {
            return $this->asJson(["message" => "Question Deleted Successfully !"]);
        } else {
            return $this->asJson(["message" => "Unable to delete question !"]);
        }
    }
    /**
     * creates a new quiz
     *
     * @throws Exception if any error happens
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionCreateQuiz()
    {
        try {
            if ((new QuizManager())->saveQuiz(yii::$app->request->post())) {
                yii::$app->session->setFlash("success", "<i class='fa fa-info-circle'></i> Exam Created And Announced successfully ! You might need restrict access by generating access tokens");
                return $this->redirect(yii::$app->request->referrer);
            } else {
                throw new UserException("An unknown error occured, please try again");
            }
        } catch (UserException $w) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> Exam creation failed! " . $w->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }
        catch(Exception $e)
        {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> ".$e->getMessage());
            return $this->redirect(yii::$app->request->referrer); 
        }
    }
    /**
     * deletes a quiz
     *
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionDeleteQuiz()
    {
        $question = yii::$app->request->post("quiz");

        try {
            if ((new QuizManager())->deleteQuiz($question)) {
                 return $this->asJson(["message" => "success"]);
            }
        } catch (Exception $q) {
            return $this->asJson(["message" => "Exam Deleting Failed !" . $q->getMessage()]);
        }
    }
    /**
     * previews the created quiz
     *
     * @param  string the encrypted quiz ID
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionQuizPreview($quiz)
    {
        try {
            $quiz = ClassRoomSecurity::decrypt($quiz);
            $quiz = Quiz::findOne($quiz);
            $quiz_title = ($quiz != null) ? $quiz->quiz_title : null;
            $quizdata = (new QuizManager())->quizReader($quiz->quizID);
            return $this->render('quizPreview', ['quizdata' => $quizdata,'title' => $quiz_title,'quiz' => $quiz->quizID]);
        } catch (Exception $e) {
            yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Unable to Preview Exam, Try Again Later!');
            return $this->redirect(yii::$app->request->referrer);
        }
    }
    /**
     * retrieves students scores for an assessments and displays them
     *
     * @param  string $quiz the encrypted quiz ID
     * @return string the rendered page that displays the scores
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionScoresView($quiz)
    {
        $quiz = ClassRoomSecurity::decrypt($quiz);
        $scores = StudentQuiz::find()->where(['quizID' => $quiz])->all();

        return $this->render('quizScores', ['scores' => $scores,'quiz' => $quiz]);
    }
    /**
     * marks assessments meant to be manually marked
     *
     * @param  string $quiz the encrypted quiz ID
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionQuizManualMarking($quiz)
    {
        $quiz = ClassRoomSecurity::decrypt($quiz);
        $markables=(new QuizManager)->getManualMarkableQuestions($quiz);
        $quiz = Quiz::findOne($quiz);
        if (yii::$app->request->isPost) {
            $data = yii::$app->request->post();
            (new QuizManager)->addManualMarkedScore($data['subid'], $data['score']);
        }

        return $this->render('marking', ['quiz' => $quiz,'markables'=>$markables]);
    }
    /**
     * An action for viewing and generating access tokens for assessments
     *
     * @param  string $quiz the encrypted quiz ID
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionTokens($quiz)
    {
        $quiz = ClassRoomSecurity::decrypt($quiz);
        $tokens = Quizaccesstokens::find()->where(['quizID' => $quiz])->all();
        try {
            if (yii::$app->request->isPost) {
                $tokenizer = new Quizaccesstokens();
                if ($tokenizer->load(yii::$app->request->post())) {
                    $generatedtokens = $tokenizer->generateTokens($quiz);
                    if ($generatedtokens > 0) {
                        yii::$app->session->setFlash('success', "<i class='fa fa-info-circle'></i> " . $generatedtokens . " Token(s) generated successfully!");
                        return $this->redirect(yii::$app->request->referrer);
                    } else {
                        yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> No token generated !");
                        return $this->redirect(yii::$app->request->referrer);
                    }
                }
            }
        } catch (UserException $t) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i>Failed! " . $t->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        } catch (Exception $e) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i>Failed! Unknown error occurred");
            return $this->redirect(yii::$app->request->referrer);
        }
        return $this->render('accessTokens', ['tokens' => $tokens]);
    }
    /**
     * an action for downloading all tokens in a pdf file
     *
     * @param  string $quiz the encrypted quiz ID
     * @throws \yii\base\UserException
     * @throws \Exception
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionGetTokensPdf($quiz)
    {
        try {
            $quiz = ClassRoomSecurity::decrypt($quiz);
            $tokens = Quizaccesstokens::find()->asArray()->where(['quizID' => $quiz])->all();

            if ($tokens == null) {
                throw new UserException("No tokens found !");
            }
            if (Quiz::findOne($quiz)->isExpired()) {
                throw new UserException("Cannot download tokens for an expired assessment!");
            }
            (new QuizManager())->downloadPDFTokens($this->renderPartial('tokenspdf', ['tokens' => $tokens]));
        } catch (UserException $t) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i>Download Failed! " . $t->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        } catch (Exception $e) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i>Download Failed! Unknown error occurred");
            return $this->redirect(yii::$app->request->referrer);
        }
    }
    /**
     * an action to retrieve the number of manually markable questions
     *
     * @return \yii\base\Response with the number of markables
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionGetMarkables()
    {
        $quiz = yii::$app->request->post('quiz');
        $sub = yii::$app->request->post('submit');
        $studentID=(StudentQuiz::findOne($sub))->regNo->userID;
        $markables_num = (new QuizManager())->getmarkables_num($quiz, $sub);
        $markable_questions=(new QuizManager())->getSubmittedMarkables($studentID,$quiz);

        $buffer=[
            'markablesnum'=> $markables_num,
            'markables'=>$markable_questions
        ];
        return $this->asJson(['markables' => $buffer]);
    }
    /**
     * an action to download the questions bank in a pdf format
     *
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionDownloadBank()
    {
        (new QuizManager())->downloadPDFbank($this->renderPartial("questionsBank2pdf"));
    }
    /**
     * an action to download the whole bank in pdf format
     *
     * @param  string $chapter the encrypted chapter name
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionDownloadChapterBank($chapter)
    {
        $chapter = ClassRoomSecurity::decrypt($chapter);
        (new QuizManager())->downloadPDFbank($this->renderPartial("chapterBank2pdf", ['chap' => $chapter]));
    }
    /**
     * an action to upload questions using a file
     *
     * @author khalid hassan <thewinner016@gmail.com>
     * @return \yii\base\Response redirects to the requesting page
     * @since  2.0.0
     */
    public function actionQuestionsUploader()
    {
        try {
            $num_added = (new quizManager())->questionsUploader(UploadedFile::getInstancesByName("questionsfile")[0]);

            yii::$app->session->setFlash("success", "<i class='fa fa-info-circle'></i> " . $num_added . " Question(s) Uploaded To The Bank Successfully !");
            return $this->redirect(yii::$app->request->referrer);
        } catch (Exception $b) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> Could Not Upload Questions !" . $b->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }
    }
    /**
     * uploads questions to a given chapter from a file
     *
     * @param  string $chapter the encrypted chapter name
     * @return \yii\base\Response redirects to the requesting page
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionChapterQuestionsUploader($chapter)
    {
        $chapter = ClassRoomSecurity::decrypt($chapter);
        try {
            $num_added = (new quizManager())->chapterQuestionsUploader(UploadedFile::getInstancesByName("questionsfile")[0], $chapter);

            yii::$app->session->setFlash("success", "<i class='fa fa-info-circle'></i> " . $num_added . " Question(s) Uploaded To The Chapter Successfully !");
            return $this->redirect(yii::$app->request->referrer);
        } catch (Exception $b) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> Could Not Upload Questions !" . $b->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }
    }
    /**
     * retreives and displays quizes at the student side
     *
     * @return string the rendered page for displaying the quizes
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionStudentQuizes()
    {

        $quizzes = StudentQuiz::find()->where(['reg_no'=>yii::$app->user->identity->username])->orderBy(['quizID' => SORT_DESC])->all();
        return $this->render('student/studentQuizzes', ['quizzes' => $quizzes]);
    }
    /**
     * an action for student to attempt online assessments
     *
     * @param  string $quiz the encrypted quiz ID
     * @return string|\yii\base\Response return the rendered quiz page or redirects to
     * the requesting page in case of any error
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionQuizTake($quiz)
    {
        $quiz = ClassRoomSecurity::decrypt($quiz);
        $instructor=(Quiz::findOne($quiz))->instructorID;
        try {
            $quizmanager=new QuizManager(null,$instructor,[]);
            $quizdata = $quizmanager->getQuizData($quiz);
            $vfp=new Vfp("Attempt assessment");
            (new Invigilator)->recordVFP($vfp,$quiz);
            return $this->render('student/quizBoard', ['quizdata' => $quizdata,'title' => Quiz::findOne($quiz)->quiz_title,'total_marks' => Quiz::findOne($quiz)->total_marks,'registered' =>true ,'quiz' => $quiz,'inititalTimer' => $quizmanager->timer($quiz)]);
        } catch (Exception $q) {
            // recording a vfp
            $vfp=new Vfp("Attempt assessment ",$q->getMessage(),'User or System',$q->getCode());
            (new Invigilator)->recordVFP($vfp,$quiz);
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> Could Not Load Quiz !" . $q->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        } catch (QuizRestrictedException $r) {
           
            return $this->redirect(["verify-token",'quiz' => ClassRoomSecurity::encrypt($quiz)]);
        }
    }
    /**
     * verifies the quiz access token and grants access to the quiz
     *
     * @param  string $quiz the encrypted quiz ID
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionVerifyToken($quiz=null)
    {
        if (yii::$app->request->isPost) {
            $quizID=null;
            try {
                $token = yii::$app->request->post("token");
                $token=base64_encode(strtolower($token));
                $accesstokenfind=Quizaccesstokens::find()->where(['token'=>$token])->one();
                if($accesstokenfind==null)
                {
                    throw new UserException("Invalid token !",3); 
                }
                $quizmodel = $accesstokenfind->quiz;
                if ($quizmodel == null) {
                    throw new UserException("Quiz not found !",6);
                }
                $quizID=$quizmodel->quizID;
                if ($quizmodel->grantAccess($token)) {
                    $vfp=new Vfp("token Verify | Access grant");
                    (new Invigilator)->recordVFP($vfp,$quizmodel->quizID);
                    return $this->redirect(['quiz-take','quiz' =>ClassRoomSecurity::encrypt($quizmodel->quizID)]);
                } else {
                    throw new UserException("Could not grant access to the assessment !",3);
                }
              
            } catch (UserException $t) {
                $vfp=new Vfp("Verify access token",$t->getMessage(),'User or System',$t->getCode());
                (new Invigilator)->recordVFP($vfp,$quizID);
                $vfp=new Vfp("Attempt assessment",$t->getMessage(),'User or System',$t->getCode());
                (new Invigilator)->recordVFP($vfp,$quizID);
                yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> " . $t->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            } catch (Exception $e) {
                $vfp=new Vfp("Verify access token ",$e->getMessage(),'User or System',$e->getCode());
                (new Invigilator)->recordVFP($vfp,$quizID);
                $vfp=new Vfp("Attempt assessment",$e->getMessage(),'User or System',$e->getCode());
                (new Invigilator)->recordVFP($vfp,$quizID);
                yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> Unknown error happened, try again later!".$e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }
        return $this->render("student/verifytoken");
    }
    /**
     * reads rules for attempting an online assessment and then displays
     * them to the student
     *
     * @param  string $quiz the encrypted quiz ID
     * @return string the rendered page for the rules
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionQuizRules($quiz)
    {
         $rules = file_get_contents("quizrules/quizrules.txt");
         $rules = explode("#", $rules);
         return $this->render('student/quizRules', ['quiz' => $quiz,'rules' => $rules]);
    }
    /**
     * action for submitting the assessment for students
     * submits assessment marks it and then returns the student score
     *
     * @return \yii\web\Response
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionSubmitQuiz()
    {
        $quiz=yii::$app->request->post()['quiz'];
        $instructorid=(Quiz::findOne($quiz))->instructorID;
        try {
            $res = (new Quizmanager(null,$instructorid,[]))->markQuiz(yii::$app->request->post());
            $vfp=new Vfp("Assessment submitting");
            (new Invigilator)->recordVFP($vfp,$quiz);
            $vfp=new Vfp("Assessment Marking");
            (new Invigilator)->recordVFP($vfp,$quiz);
            return $this->asJson(["score" => json_encode($res)]);
        }
        catch(UserException $u)
        {
            $vfp=new Vfp("Assessment submitting|marking",$u->getMessage(),'User or System',$u->getCode());
            (new Invigilator)->recordVFP($vfp,$quiz); 
            return $this->asJson(["failed" => $u->getMessage()]); 
        }
         catch (Exception $s) {
            $vfp=new Vfp("Assessment submitting|marking",$s->getMessage(),'User or System',$s->getCode());
            (new Invigilator)->recordVFP($vfp,$quiz); 
            return $this->asJson(["failed" => $s->getMessage()]);
        }
    }
    /**
     * an action that updates the quiz timer
     *
     * @return \yii\web\Response containing the remaining time or null if the time is over
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionUpdateQuizTimer()
    {
        $quiz = yii::$app->request->post('quiz');

        return $this->asJson(['time' => (new QuizManager())->timer($quiz)]);
    }
    /**
     * downloads the quiz in the pdf format
     *
     * @param  string $quiz the encrypted quiz ID
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionDownloadQuizPdf($quiz)
    {
        $quizID = ClassRoomSecurity::decrypt($quiz);
        $quiz = Quiz::findOne($quizID);
        $quiz_title = ($quiz != null) ? $quiz->quiz_title : null;
        $quizdata = (new QuizManager())->quizReader($quizID);
        (new QuizManager())->downloadPDFQuiz($this->renderPartial("quiz2pdf", ['quizdata' => $quizdata,'title' => $quiz_title]),$quiz_title);
    }
    /**
     * generate a pdf file from student submits
     * @param string $assessment the encrypted assessment ID
     * @param string $student the encrypted student reg number
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     */
    public function actionStudentSubmitsPdf($assessment,$student)
    {
        try
        {
        $assessmentID = base64_decode($assessment);
        $student=base64_decode($student);
        $assessment = Quiz::findOne($assessmentID);
        $submitted= (new Invigilator)->getMarkablesubmissions($student,$assessmentID);
        $content=$this->renderPartial("student/submitfile", ['submitted' => $submitted,'instructor'=>$assessment->instructorID]);
        (new Invigilator)->downloadSubmissionsPdf($content,$student,$assessmentID);
        }
        catch(Exception $e)
        {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i>Downloading Failed !" . $e->getMessage());
            return $this->redirect(yii::$app->request->referrer); 
        }
    }
    /**
     * an action for updating a quiz | online assessment
     *
     * @param  string $quiz the encrypted quiz ID
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionUpdateQuiz($quiz)
    {

        $quizid = ClassRoomSecurity::decrypt($quiz);
        $quiz = (Quiz::findOne($quizid) != null) ? Quiz::findOne($quizid) : new Quiz();
        $quizbuffer = (new QuizManager())->quizReader($quiz->quizID);
        try {
            if ($quiz->hasSubmits()) {
                throw new UserException("You are not allowed to update an assessment having submits!");
            }
            if ($quiz->hasTokens()) {
                throw new UserException("You are not allowed to update an assessment having access tokens! you might need to delete all existing tokens and regenerate new ones after updating your Quiz !");
            }
            if ($quiz->isReadyTaking() && !$quiz->isExpired()) {
                throw new UserException("Cannot Update an ongoing assessment, you might need to wait until it's expired !");
            }
            if (Yii::$app->request->isPost) {
                $buffer = yii::$app->request->post();


                if ((new quizManager())->updateQuiz($quizid, $buffer)) {
                    yii::$app->session->setFlash("success", "<i class='fa fa-info-circle'></i> Assessment Updated Successfully !");
                    return $this->redirect("class-quizes");
                }
            }
        } catch (UserException $d) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i> Assessment Updating failed !" . $d->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        } catch (Exception $e) {
            yii::$app->session->setFlash("error", "<i class='fa fa-exclamation-triangle'></i>Assessment updating failed, An unknown error occurred while updating Assessment !".$e->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }


        return $this->render("updateQuiz", ['quiz' => $quiz,'buffer' => $quizbuffer]);
    }
    /**
     * an action for deleting all quiz access tokens
     *
     * @return \yii\web\Response containing a message about the status of the action
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionDeleteAllTokens()
    {
        $quiz = yii::$app->request->post('quiz');
        try {
            $connection = yii::$app->getDb();
            $command = $connection->createCommand('delete from quizaccesstokens WHERE quizID=:quizid', ['quizid' => $quiz]);
            if ($command->query()) {
                return $this->asJson(['success' => "All access tokens deleted, the assessment is now public and all students can access it without any restrictions!"]);
            } else {
                return $this->asJson(['failure' => "Deleting failed! try again later!"]);
            }
        } catch (Exception $d) {
            return $this->asJson(['failure' => "Unknown error occurred while deleting tokens, try again later!"]);
        }
    }
    /**
     * an action for deleting a single quiz access token
     *
     * @return \yii\web\Response about the status of the action
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionDeleteToken()
    {
        $token = yii::$app->request->post("token");
        $token = Quizaccesstokens::findOne($token);

        try {
            if ($token->delete()) {
                return $this->asJson(['success' => "Token deleted successfully"]);
            } else {
                return $this->asJson(['failure' => "Token deleting failed, try again later!"]);
            }
        } catch (Exception $e) {
            return $this->asJson(['failure' => "An  unknown error occurred while deleting token, try again later!"]);
        }
    }
    /**
     * an action for updating the student score
     *
     * @param  string $scoreID the encrypted score ID
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionEditScore($scoreID)
    {
        $score = StudentQuiz::findOne($scoreID);

        if (yii::$app->request->isPost) {
            if ($score->load(yii::$app->request->post()) && $score->save()) {
                yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> Score Updated Successfully !');
                return $this->redirect(yii::$app->request->referrer);
            } else {
                yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Score Updating failed, try again !');
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->render('updatescore', ['score' => $score]);
    }
    /**
     * an action for deleting a student score
     *
     * @return \yii\web\Response about the status of the action
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function actionDeleteScore()
    {
        $score = yii::$app->request->post("score");
        $score = StudentQuiz::findOne($score);

        if ($score->delete()) {
            return $this->asJson(['message' => 'Record Deleted Successfully']);
        } else {
            return $this->asJson(['message' => 'Record Deleting Failed, try again!']);
        }
    }
    /**
     * downloads all markable questions saved files
     * @param string $assessment the encrypted assessment ID
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     */
    public function actionDownloadMarkableSubmits($assessment)
    {
        try{
            
        $assesspure=ClassRoomSecurity::decrypt($assessment);
        $instructor=(Quiz::findOne($assesspure))->instructorID;
        (new QuizManager(null,$instructor,[]))->downloadMarkableSubmits($assessment);
    } catch (\Exception $dn) {
        yii::$app->session->setFlash('error', $dn->getMessage());
        $this->redirect(yii::$app->request->referrer);
    }
    }
    /**
     * returns the percentage of marked assessment submits
     * against the total number of submits
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     */
    public function actionGetMarkedPerc()
    {
        $assessmentID=yii::$app->request->post("assessmentID");
        print (new QuizManager)->getMarkedPerc($assessmentID);
    }
    /**
     * records frontend student behaviour on the VFP file
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     */

     public function actionRecordFrontendVfps()
     {
        $action=yii::$app->request->post("action");
        $warning=yii::$app->request->post("warning");
        $code=yii::$app->request->post("code");
        $quiz=yii::$app->request->post("quiz");
        $vfp=new Vfp($action,$warning,'User',$code);
        if((new Invigilator)->recordVFP($vfp,$quiz))
        {
          print("recorded");
        } 
     }
     /**
      * finds and return student's vfps for a given assessment
      * @param int $student the student userID
      * @param int $assessment the assessmentID
      */

      public function actionGetStudentVfps($student,$assessment)
      {
        $student=base64_decode($student);
        $assessment=base64_decode($assessment);
        $vfps=(new Invigilator)->getStudentVfps($student,$assessment);
        return $this->render('vfpsView',['vfps'=>$vfps['vfps'],'assessment'=>$assessment]);
      }
}
