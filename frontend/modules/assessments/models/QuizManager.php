<?php

namespace frontend\modules\assessments\models;

use Yii;
use yii\base\Model;
use yii\base\Exception;
use yii\helpers\FileHelper;
use common\models\Quiz;
use Mpdf\Mpdf;
use common\models\Course;
use common\models\StudentQuiz;
use frontend\models\ClassRoomSecurity;
use common\models\Module;
use yii\base\UserException;
use common\exceptions\QuizRestrictedException;

class QuizManager extends Model
{
    /**
     * @var string stores the question itself
     */
    public $question;
    /**
     * @var string stores the question image path
     */
    public $questionImage;
    /**
     * @var string the question type such as multiple-choice, true-false, etc
     */
    public $questiontype;
    /**
     * @var array the answer options for multiple-choice and true-false questions
     */
    public $questionAnswerOptions;
    /**
     * @var array the image answer options for multiple-choice and true-false questions
     */
    public $ImageAnswerOptions;
    /**
     * @var array stores the true answers for a multiple-choice or true-false questions
     */
    public $trueAnswers;
    /**
     * @var bool wheither the question accepts multiple answer choices or not
     */
    public $multipleAnswers;
    /**
     * @var array stores the blanks for fill-in-blanks type of questions
     */
    public $blanks;
    /**
     * @var array stores items that have to be matched for matching items type of question
     */
    public $items;
    /**
     * @var array stores the possible matches for a matching items type of question
     */
    public $matches;
    /**
     * @var string stores the name of the chapter to which the question belongs to
     */

    public $chapter;
    /**
     * @var array stores the possible alternatives for a enumarating type of question
     */

    public $alternatives;
    //constants
    /**
     * @var string stores the path for quizes files
     */
    public $quizzesHome = 'storage/quizes/';
    /**
     * @var string stores the full path for a quiz bank file
     */
    public $q_bank;
    /**
     * @var double the score if the answer is correct
     */
    public $score_correct=1; //defaults to 1 for each correct items
    /**
     * @var double the score if the answer is not correct
     */

     public $score_incorrect=0; //defaults to 0
    /**
     * constructs the object and populates it with necessary information
     *
     * @param  array $question the array buffer containing the question information
     * @param  array $config   the array buffer containing some configuration information
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */

    public function __construct($question = null, $instructorid=null,$config = [])
    {
        $this->question = isset($question['question']) ? $question['question'] : null;
        $this->questiontype = isset($question['questiontype']) ? $question['questiontype'] : null;
        $this->questionAnswerOptions = isset($question['options']) ? $question['options'] : [];
        $this->ImageAnswerOptions = $question != null ? isset($question['optionImage']) ? $question['optionImage'] : [] : [];
        $this->questionImage = $question != null ? isset($question['questionImage']) ? $question['questionImage'] : [] : [];
        $this->chapter = $question != null ? (isset($question['chapter']) ? $question['chapter'] : "Others") : null;
        $instructor=isset(yii::$app->user->identity->instructor->instructorID)?yii::$app->user->identity->instructor->instructorID:$instructorid;
        $this->quizzesHome .= str_replace(" ", "", $instructor) . "/";
        $this->q_bank = str_replace(" ", "", $instructor) . "_questionsBank.qb";
        FileHelper::createDirectory($this->quizzesHome);
        FileHelper::createDirectory($this->quizzesHome . 'images/');
        if ($this->questiontype == "fill-in-blanks") {
            $this->blanks = isset($question['blanks']) ? $question['blanks'] : [];
        } elseif ($this->questiontype == "matching") {
            $this->items = isset($question['items']) ? $question['items'] : [];
            $this->matches = isset($question['matches']) ? $question['matches'] : [];
        } elseif ($this->questiontype == "enum") {
            $this->alternatives = isset($question['alternatives']) ? $question['alternatives'] : [];
        } else {
        }
        if (!file_exists($this->quizzesHome . $this->q_bank)) {
            $bankfile = fopen($this->quizzesHome . $this->q_bank, "w");
            fclose($bankfile);
        }
        if ($question != null) {
            if (isset($this->questionAnswerOptions) && !empty($this->questionAnswerOptions)) {
                foreach ($question as $index => $questionx) {
                    if (isset($this->questionAnswerOptions[$index])) {
                            $this->trueAnswers[$index] = $question[$index];
                    } else {
                                continue;
                    }
                }
            } else {
                foreach ($question as $index => $questionx) {
                    if (isset($this->ImageAnswerOptions[$index])) {
                            $this->trueAnswers[$index] = $question[$index];
                    } else {
                                continue;
                    }
                }
            }
        }

        $this->multipleAnswers = isset($question['answerdecision']) ? $question['answerdecision'] : null;
        $this->score_correct=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1;
        $this->score_incorrect=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0;
        parent::__construct($config);
    }
    /**
     * restructures the question to a more presentable form and for better automated marking
     *
     * @return array an array buffer containing the question information
     * @throws Exception
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function questionRestructure()
    {
        $questionbuffer = [];
        $questionbuffer['type'] = $this->questiontype;
        $questionbuffer['question'] = $this->question;
        $questionbuffer['chapter'] = $this->chapter;
        $questionbuffer['questionImage'] = $this->questionImageResolver($this->questionImage);
        if ($questionbuffer['type'] == "true-false" || $questionbuffer['type'] == "multiple-choice") {
            $questionbuffer['multiple'] = $this->multipleAnswers;
            if (empty($this->trueAnswers)) {
                throw new UserException("Fatal Error, please specify at least one true choice");
            }
            if ($questionbuffer['type'] == "true-false" && count($this->trueAnswers) > 1) {
                throw new UserException("Fatal Error, Too many True answers supplied for true/false type questions! Only One is Allowed.");
            } elseif (count($this->trueAnswers) > 1) {
                if ($this->multipleAnswers == null) {
                    throw new UserException("Fatal Error, Too many true answers ! please review your question and make the right decision on how many possible true options are allowed !");
                }
            }

            if (empty($this->questionAnswerOptions) && empty($this->ImageAnswerOptions)) {
                throw new UserException('No options found, please specify at least 2 answer options');
            } elseif (!empty($this->questionAnswerOptions) && !empty($this->ImageAnswerOptions)) {
                throw new UserException('Fatal Error, Mixed textual and image answer options');
            } else {
                $this->ImageAnswerOptions = ($this->ImageAnswerOptions != null) ? $this->imageOptionsResolver($this->ImageAnswerOptions) : null;
                $options = [];
                $options['type'] = !empty($this->questionAnswerOptions) ? "textual" : "images";
                $options['choices'] = $options['type'] == "textual" ? $this->questionAnswerOptions : $this->ImageAnswerOptions;
                $options['true-choices'] = $this->trueAnswers;
                $questionbuffer['options'] = $options;
            }
        } elseif ($questionbuffer['type'] == "matching") {
            if ($this->matches == null) {
                throw new UserException("No matches specified !");
            } else {
                foreach ($this->matches as $index => $match) {
                    if ($match == null) {
                        throw new UserException(
                            "A gap was found inside matches,
               no match from side B should be empty,
                make sure you have added all matching items, 
                then add the non-matching items to side B"
                        );
                    }
                }
            }
            if ($this->items == null) {
                throw new UserException("No items found in side A !");
            }
            $questionbuffer['matches'] = $this->matches;
            $questionbuffer['items'] = $this->items;
        } elseif ($questionbuffer['type'] == "fill-in-blanks") {
            if ($this->blanks == null) {
                throw new UserException("No any blank placeholder specified !");
            }
            foreach ($this->blanks as $index => $blank) {
                if ($blank == null) {
                    throw new UserException("No value specified for blank " . $index + 1);
                }
            }

            if ($this->question == "" || $this->question == null) {
                throw new UserException("question is empty! ");
            }

            $questionbuffer['blanks'] = $this->blanks;
        } elseif ($questionbuffer['type'] == "enum") {
            if ($this->alternatives == null) {
                throw new UserException("No Response alternatives supplied !");
            }
            if ($this->question == null || $this->question == "") {
                throw new UserException("The question is empty !");
            }

            foreach ($this->alternatives as $index => $alternative) {
                if ($alternative == null) {
                    throw new UserException("No value supplied for alternative no. " . $index + 1);
                }
            }
            $questionbuffer['alternatives'] = $this->alternatives;
        } else {
        }
        $questionbuffer['score_correct']=$this->score_correct;
        $questionbuffer['score_incorrect']=$this->score_incorrect;
        return $questionbuffer;
    }
    /**
     * resolve question image options by uploading the related images and assigning
     * resulting paths to the buffer
     *
     * @param  array $imagesbuffer the array buffer containing images options information
     * @return array an array buffer containing resolved image options
     * @throws Exception
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function imageOptionsResolver($imagesbuffer)
    {
        $imageOptions = [];
        if (empty($imagesbuffer) || $imagesbuffer == null) {
            throw new Exception("Image options not found");
        }
        foreach ($imagesbuffer as $index => $buffer) {
            $id = uniqid();
            $path = $this->quizzesHome . "images/" . $id . "." . $buffer->extension;
            if ($buffer->saveAs($path)) {
                array_push($imageOptions, $path);
            }
        }
        return $imageOptions;
    }
    /**
     * resolve question image by uploading the related image and assigning
     * resulting path to the buffer
     *
     * @param  array $imagesbuffer the array buffer containing image  information
     * @return string the image path
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function questionImageResolver($imagesbuffer)
    {
        $imageOptions = [];
        if (empty($imagesbuffer) || $imagesbuffer == null) {
            return null;
        }
        foreach ($imagesbuffer as $index => $buffer) {
            $id = uniqid();
            $path = $this->quizzesHome . "images/" . $id . "." . $buffer->extension;
            if ($buffer->saveAs($path)) {
                array_push($imageOptions, $path);
            }
        }
        return $imageOptions[0];
    }
    /**
     * save a question to the bank
     *
     * @return bool wheither the question has been successfully saved
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function questionSave()
    {
        $course = str_replace(" ", "", yii::$app->session->get('ccode'));
        $index = $course . rand();
        $question = $this->questionRestructure();
        $qbank = $this->q_bank;
        $path = $this->quizzesHome . $qbank;
        $bankdata = "";
        $questiondata = [];
        if (file_exists($path)) {
            $bankdata = file_get_contents($path);
            if ($bankdata != "" || $bankdata != null) {
                $bankdata = $this->RevealBankData($bankdata);
                $bankdata = json_decode($bankdata, true);
            } else {
                $bankdata = [];
            }

            $bankdata[$index] = $question;
            $bankdata = json_encode($bankdata);
            $bankdata = $this->hideBankData($bankdata);
            file_put_contents($path, $bankdata, LOCK_EX);
            return true;
        } else {
            $questiondata[$index] = $question;
            $questiondata = json_encode($bankdata);
            $questiondata = $this->hideBankData($bankdata);
            file_put_contents($path, $questiondata, LOCK_EX);
            return true;
        }
    }
    /**
     * updates a question
     *
     * @param  int $question the ID of the question to be updated
     * @return bool if the question has been updated successfully
     * @throws \yii\base\UserException if the question is  not saved
     * or the old one not deleted successfully
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function updateQuestion($question)
    {
        if (!$this->questionSave()) {
            throw new UserException("Could not save question");
        }
        if (!$this->deleteQuestion($question)) {
            throw new UserException("Could not update question! by the way, a new version of the question has been saved !");
        }

         return true;
    }
    /**
     * encrypts bank data before saving to the bank file
     *
     * @param  string $data the data to be encrypted
     * @return string the decrypted string data
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    private function hideBankData($data)
    {
        return ClassRoomSecurity::encrypt($data);
    }
    /**
     * unhides or decrypts the bank data from the bank
     *
     * @param  string $data the string data to be decrypted
     * @return string the decrypted string data
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    private function RevealBankData($data)
    {
        return ClassRoomSecurity::decrypt($data);
    }
    /**
     * reads the questions bank and puts them into chapters
     *
     * @return array an array buffer containing the questions from the bank
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function questionsBankReader()
    {
        $qbank = $this->q_bank;
        $path = $this->quizzesHome . $qbank;
        $bankdata = file_get_contents($path);
        $bankdata = $this->RevealBankData($bankdata);
        return $this->bank2chapters(json_decode($bankdata, true));
    }
    /**
     * reads the questions bank without putting them into chapters
     *
     * @return array an array buffer containing the questions from the bank
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function rawQuestionsBankReader()
    {
        $qbank = $this->q_bank;
        $path = $this->quizzesHome . $qbank;
        $bankdata = file_get_contents($path);
        $bankdata = $this->RevealBankData($bankdata);
        return json_decode($bankdata, true);
    }
    /**
     * reads questions related to a given chapter and puts them under the chapter
     *
     * @param  string $chapter the chapter name for which questions are to be filtered
     * @return array an array buffer containing all chapter questions
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function chapterBankReader($chapter)
    {
        return $this->bank2chapters($this->rawChapterBankReader($chapter));
    }
    /**
     * reads all questions related to a given chapter without putting them under their chapter
     *
     * @param  string $chapter the chapter name for which questions have to be filtered
     * @return array the array buffer containing all questions related to a chapter
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function rawChapterBankReader($chapter)
    {
        $qbank = $this->q_bank;
        $path = $this->quizzesHome . $qbank;
        $bankdata = file_get_contents($path);
        $bankdata = $this->RevealBankData($bankdata);
        return $this->filterChapterQuestions(json_decode($bankdata, true), $chapter);
    }
    /**
     * Download the chapter bank, all questions related to a given chapter
     *
     * @param  mixed $chapter the name of the chapter for which questions
     *                        are to be downloaded
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function chapterBankDownload($chapter)
    {
        $data = $this->rawChapterBankReader($chapter);
        $data = json_encode($data);
        $data = $this->hideBankData($data);
        header('Content-Description: File Transfer');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="file.qb"');
        print $data;
    }
    /**
     * filters questions related to a given chapter from the questions bank
     *
     * @param  array  $bankdata an array buffer containing the questions bank data
     * @param  string $chapter  the chapter name for which question are to be filtered
     * @return array an array buffer containing filtered questions
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function filterChapterQuestions($bankdata, $chapter)
    {
        if ($bankdata == null) {
            return [];
        }
        foreach ($bankdata as $index => $question) {
            if (!isset($question['chapter'])) {
                    $question['chapter'] = "Others";
            }
            if (!$this->chapterExists($question['chapter'])) {
                $question['chapter'] = "Others";
            }
            if ($question['chapter'] == $chapter) {
                continue;
            }

            unset($bankdata[$index]);
        }

        return $bankdata;
    }
    /**
     * restructures the questions bank chapter wise
     *
     * @param  array $bank an array buffer containing all the questions from the bank
     * @return array an array buffer with questions put under chapters
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function bank2chapters($bank)
    {
        $buffer = [];
        if ($bank == null) {
            return [];
        }
        foreach ($bank as $index => $question) {
            if (!isset($question['chapter'])) {
                  $question['chapter'] = "Others";
            }
            if (!$this->chapterExists($question['chapter'])) {
                $question['chapter'] = "Others";
            }
            $buffer[$question['chapter']][$index] = $question;
        }

        return $buffer;
    }
    /**
     * checks if chapter exists
     *
     * @param  int $chapterID the chapter ID
     * @return bool wheither the chapter exists or not
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function chapterExists($chapterID)
    {
        $chapter = Module::findOne($chapterID);
        if ($chapter == null) {
            return false;
        }

        return true;
    }
    /**
     * deletes a question from the bank
     *
     * @param  int $question the question ID
     * @return bool wheither the question has been successfully deleted
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function deleteQuestion($question)
    {
        $bank = $this->rawQuestionsBankReader();
        if (isset($bank[$question])) {
            unset($bank[$question]);
            if ($this->updateQuestionsBank($bank)) {
                return true;
            }
            return false;
        }

          return false;
    }
    /**
     * retrieves a questions from the bank
     *
     * @param  int $questionID the ID of the question
     * @return array an array buffer containing questions details
     * @throws \yii\base\UserException if the question is not found
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function findQuestion($questionID)
    {
        $bank = $this->rawQuestionsBankReader();
        if (isset($bank[$questionID])) {
            return $bank[$questionID];
        } else {
            throw new UserException("Question not found !");
        }
    }
    /**
     * empty the chapter bank, deletes all questions from the chapter bank
     *
     * @param  string $chapter the name of the chapter to be emptied
     * @return bool wheither the chapter has been emptied successfully
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function emptyChapterBank($chapter)
    {
        $chapterbank = $this->rawChapterBankReader($chapter);
        if ($chapterbank == null) {
            return false;
        }

        foreach ($chapterbank as $index => $question) {
            if (!$this->deleteQuestion($index)) {
                    continue;
            }
        }

        return true;
    }
    /**
     * updates the questions bank file
     *
     * @param  string $content the new content to be saved to the file
     * @return bool wheither the content has been saved successfully
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    private function updateQuestionsBank($content)
    {
        $qbank = $this->q_bank;
        $path = $this->quizzesHome . $qbank;
        $content = $this->hideBankData(json_encode($content));
        file_put_contents($path, $content, LOCK_EX);
        return true;
    }
    /**
     * finds a question from the questions bank
     *
     * @param  int $findindex the question ID to be retreived
     * @return array|null an array buffer containing question information
     * or null if the question is not found
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function questionFind($findindex)
    {
        $qbank = $this->q_bank;
        $path = $this->quizzesHome . $qbank;
        $bankdata = file_get_contents($path);
        $bankdata = $this->RevealBankData($bankdata);
        $bankdata = json_decode($bankdata, true);
        foreach ($bankdata as $index => $question) {
            if ($index == $findindex) {
                return $question;
            }

            continue;
        }

        return null;
    }
    /**
     * saves a new quiz
     *
     * @param  array $quizdata the array buffer containing quiz information
     * @return bool if the quiz has been saved successfully
     * @throws Exception
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function saveQuiz($quizdata)
    {
        $quizName = $quizdata['quizName'];
        $attemptmode = $quizdata['attemptMode'];
        $start = (isset($quizdata['StartingDate']) && isset($quizdata['StartingTime'])) ? $quizdata['StartingDate'] . " " . $quizdata['StartingTime'] : null;
        $end = (isset($quizdata['DeadlineDate']) && isset($quizdata['DeadlineTime'])) ? $quizdata['DeadlineDate'] . " " . $quizdata['DeadlineTime'] : null;
        $duration = intval($quizdata['duration']);
        $viewAnswers = isset($quizdata['viewAnswers']) ? $quizdata['viewAnswers'] : "off";
        $questions = isset($quizdata['quizQuestions']) ? $quizdata['quizQuestions'] : null;
        $numquestions = isset($quizdata['numquestions']) ? $quizdata['numquestions'] : null;
        $numquestions = ($numquestions != null) ? intval($numquestions) : null;
        $types=(isset($quizdata['qtypes'])&&$quizdata['qtypes']!=null)?$quizdata['qtypes']:null;
        $quizQuestions = [];
        $total_marks = 0;

        if (strlen($quizName) > 30) {
            throw new Exception("Quiz Title Should Not Exceed 30 Characters Long !");
        }
        if ($duration == 0 || $duration == null) {
            throw new Exception("Duration must be a number greater than 0 !");
        }
        if ($start == null) {
            throw new Exception("Starting Date And Time Must Be Specified !");
        }

        $total_marks =($attemptmode!="individual")?$this->computeTotalMarks($questions):$quizdata['total_score'];
        if ($attemptmode == "massive") {
            if (empty($questions) || $questions == null) {
                  throw new Exception("Quiz Questions Must Be Set For This Type Of Quiz !");
            }

            foreach ($questions as $index => $question) {
                  $quizQuestions[$question] = $this->questionFind($question);
            }

            try {
                $transaction = Yii::$app->db->beginTransaction();
                $quizdb = new Quiz();
                $quizdb->quiz_title = $quizName;
                $quizdb->total_marks = $total_marks;
                $quizdb->attempt_mode = $attemptmode;
                $quizdb->duration = $duration;
                $quizdb->viewAnswers = $viewAnswers;
                $quizdb->quiz_file = $this->quizFileSaver($quizQuestions);
                date_default_timezone_set('Africa/Dar_es_Salaam');
                $quizdb-> date_created = date("Y-m-d H:i:s");
                $quizdb->start_time = $start;
                $quizdb->status = "new";
                $quizdb->instructorID = yii::$app->user->identity->instructor->instructorID;
                if ($quizdb->save()) {
                    $transaction->commit();
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $f) {
                $transaction->rollBack();
                return false;
            }
        } else {
            if($types==null){throw new Exception("Please chose at least one question type !");}
            if ($numquestions == 0 || $numquestions == null) {
                throw new Exception("Number of questions must be a number greater than 0 !");
            }
            if ($numquestions > $this->getQuestionsCount($quizdata['chapters'],$types)) {
                throw new Exception("You Do Not Have Enough Questions In The Bank,
                 you might need to review the types of questions you have chosen !");
            }
            if ($end == null) {
                throw new Exception("Deadline Date And Time Must Be Specified For This Type Of Quiz !");
            }
            try
            {
            $transaction = Yii::$app->db->beginTransaction();
            $quizdb = new Quiz();
            $quizdb->total_marks = $total_marks;
            $quizdb->attempt_mode = $attemptmode;
            $quizdb->duration = $duration;
            $quizdb->quiz_title = $quizName;
            $quizdb->quiz_file = $this->quizFileSaver($quizdata);
            $quizdb->viewAnswers = $viewAnswers;
            date_default_timezone_set('Africa/Dar_es_Salaam');
            $quizdb-> date_created = date("Y-m-d H:i:s");
            $quizdb->start_time = $start;
            $quizdb->end_time = $end;
            $quizdb->instructorID = yii::$app->user->identity->instructor->instructorID;
            $quizdb->status = "new";
            $quizdb->num_questions = $numquestions;
            $quizdb->save();
            $transaction->commit();
            return true;
            }
            catch(Exception $i)
            {
                $transaction->rollBack();
                return false; 
            }
        }
    }
    /**
     * computes the maximum score for a given number of questions
     *
     * @param  array $questions an array buffer containing the questions
     * @return int the maximun score for the assessment
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function computeTotalMarks($questions)
    {
        $total = 0;
        foreach ($questions as $index => $q) {
            $question = $this->findQuestion($q);
            if ($question['type'] == "multiple-choice" || $question['type'] == "true-false") {

                foreach($question['options']['true-choices'] as $in=>$truechoice)
                {
                    if ($truechoice!= null) {
                    $total+=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1;
                }
            }
                
            } elseif ($question['type'] == "matching") {
                foreach ($question['items'] as $i => $item) {
                    if ($item != null) {
                            $total+=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1;
                    }
                }
            } elseif ($question['type'] == "fill-in-blanks") {
                foreach ($question['blanks'] as $i => $blank) {
                    if ($blank != null) {
                            $total+=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1;
                    }
                }
            } elseif ($question['type'] == "enum") {
                foreach ($question['alternatives'] as $i => $alt) {
                    if ($alt != null) {
                            $total+=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1;
                    }
                }
            } else {
                $total+=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1;
            }
        }

        return $total;
    }

    /**
     * creates and saves the file containing the quiz information
     *
     * @param  array $quizdata an array buffer containing quiz information
     * @return string the file path of the created file
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    private function quizFileSaver($quizdata)
    {
        $uniq = uniqid();
        $file = 'quiz_' . $uniq . '.qz';
        $path = $this->quizzesHome . $file;
        $quizdata = json_encode($quizdata);
        $quizdata = $this->hideBankData($quizdata);
        file_put_contents($path, $quizdata);
        return $file;
    }
    /**
     * deletes a quiz
     *
     * @param  int $quiz the quiz ID
     * @throws \Exception if the quiz is not found
     * @return bool if the quiz has been deleted successfully
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function deleteQuiz($quiz)
    {
        $quiz = Quiz::findOne($quiz);
        if ($quiz == null) {
            throw new Exception("Quiz not found");
        }
        if ($quiz->quiz_file != null) {
            $quizhome = $this->quizzesHome . $quiz->quiz_file;
            if (file_exists($quizhome)) {
                    unlink($quizhome);
                    $quiz->delete();
                    return true;
            }
        } else {
            $quiz->delete();
            return true;
        }

        return false;
    }

    /**
     * updates the quiz information
     *
     * @param  int   $quiz   the quiz ID
     * @param  array $buffer an array  buffer containing quiz information
     * @throws \yii\base\UserException
     * @return bool wheither the quiz has been updated successfully
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function updateQuiz($quiz, $buffer)
    {
        $quizobj=Quiz::findOne($quiz);
        if ($quizobj->hasSubmits()) {
            throw new UserException("Cannot Update a quiz having submits");
        }
        if ($quizobj->isReadyTaking() && !$quizobj->isExpired()) {
            throw new UserException("Cannot Update an ongoing quiz, you might need to wait until it's expired !");
        }
        
        if ($this->saveQuiz($buffer)) {
            if ($this->deleteQuiz($quiz)) {
                    return true;
            } else {
                  throw new UserException("Unable to update quiz, your quiz has been saved as a new one instead, your are advised to delete the old one !");
            }
        } else {
            throw new UserException("Could not save changes! try again later");
        }
    }
    /**
     * reads quiz content
     *
     * @param  int $quiz the ID of the quiz
     * @throws \Exception if the quiz not found
     * @return array the array  buffer containing the quiz information
     * @author khalid hassan  <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function quizReader($quiz)
    {
        $quiz = Quiz::findOne($quiz);
        $quizfile = ($quiz != null) ? $quiz->quiz_file : null;

        if ($quizfile == null) {
            throw new Exception("Quiz not found !",7);
        }
        $path = $this->quizzesHome . $quizfile;
        $quizdata = file_get_contents($path);
        $quizdata = $this->RevealBankData($quizdata);
        return json_decode($quizdata, true);
    }
    /**
     * retreives manually markable questions
     *
     * @param  int $quiz the quiz ID
     * @return array an array buffer containing manually markable questions
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function getQuizManualMarkableQuestions($quiz)
    {
        $quizdata = $this->quizReader($quiz);
        foreach ($quizdata as $in => $question) {
            if ($question['type'] == "shortanswer") {
                      continue;
            }

            unset($quizdata[$in]);
        }

        return $quizdata;
    }
    /**
     * returns the full path for the questions bank file
     *
     * @return string the full path of the bank file
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function getBankHome()
    {
        return $this->quizzesHome . $this->q_bank;
    }
    /**
     * downloads the whole questions in a pdf format
     *
     * @param  string $content the html content to be used to generate the pdf file
     * @return string|null
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function downloadPDFbank($content)
    {

        if ($content != null) {
            $instructor = Yii::$app->user->identity->instructor;
            $name = $instructor->full_name;
            $year = date("Y");
            $mpdf = new Mpdf(['orientation' => 'P']);
            $mpdf->setFooter('{PAGENO}');
            $stylesheet = file_get_contents('css/capdf.css');
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetWatermarkText(yii::$app->params['appUrl'], 0.09);
            $mpdf->showWatermarkText = true;
            $mpdf->WriteHTML('<div align="center"><img src="img/logo.png" width="110px" height="110px"/></div>', 2);
            $mpdf->WriteHTML('<p align="center"><font size=5>Questions Bank (' . $year . ')</font></p>', 3);
            $mpdf->WriteHTML('<p align="center"><font size=3>By ' . $name . '</font></p>', 3);
            $mpdf->WriteHTML('<hr width="80%" align="center" color="#000000">', 2);
            $mpdf->WriteHTML($content, 3);
            $filename ="Questions_Bank.pdf";
            $filename = str_replace(' ', '', $filename);
            $mpdf->Output($filename, "D");
            return null;
        } else {
            return 'no content';
        }
    }
    /**
     * uploads questions to the bank from file
     *
     * @param  mixed the uploaded file
     * @return int|void the number of added questions to the bank or nothing
     * @throws \Exception
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function questionsUploader($bankfile)
    {

        $name = $bankfile->name;
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $size = $bankfile->size;



        if ($ext != "qb") {
            throw new Exception("Please Upload a Questions Bank File (.qb)");
        }
        if ($size == 0 && $bankfile->error == 0) {
            throw new Exception("Uploaded File Is Empty!");
        }
        $content = file_get_contents($bankfile->tempName);
        $content = $this->RevealBankData($content);
        if (!$this->isJson($content)) {
            throw new Exception("Invalid File! File Content Corrupt or Not Supported !");
        }

        $content_to_array = json_decode($content, true);
        $questionsBank = ($this->rawQuestionsBankReader() != null) ? $this->rawQuestionsBankReader() : [];
        $added = 0;
        foreach ($content_to_array as $index => $question) {
            if (array_key_exists($index, $questionsBank)) {
                 continue;
            }
            $questionsBank[$index] = $question;
            $added++;
        }

        if ($this->updateQuestionsBank($questionsBank)) {
            return $added;
        }
    }
    /**
     * uploads the questions to the chapter bank from a file
     *
     * @param  mixed  $bankfile the uploaded file
     * @param  string $chapter  the chapter name
     * @return int|void the number of added questions to the bank or nothing
     * @throws \Exception
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function chapterQuestionsUploader($bankfile, $chapter)
    {

        $name = $bankfile->name;
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $size = $bankfile->size;

        if ($ext != "qb") {
            throw new Exception("Please Upload a Questions Bank File (.qb)");
        }
        if ($size == 0 && $bankfile->error == 0) {
            throw new Exception("Uploaded File Is Empty!");
        }
        $content = file_get_contents($bankfile->tempName);
        $content = $this->RevealBankData($content);
        if (!$this->isJson($content)) {
            throw new Exception("Invalid File! File Content Corrupt or Not Supported !");
        }

        $content_to_array = json_decode($content, true);
        $content_to_array = $this->questionsmove($content_to_array, $chapter);
        $questionsBank = ($this->rawQuestionsBankReader() != null) ? $this->rawQuestionsBankReader() : [];
        $added = 0;
        foreach ($content_to_array as $index => $question) {
            if (array_key_exists($index, $questionsBank)) {
                 continue;
            }
            $questionsBank[$index] = $question;
            $added++;
        }

        if ($this->updateQuestionsBank($questionsBank)) {
            return $added;
        }
    }
    /**
     * moves a given question to a given chapter
     *
     * @param  array  $buffer  an array buffer containing the question information
     * @param  string $chapter the name of the chapter to which
     *                         the question has to be moved
     * @return array an array buffer containing the question information with the new chapter
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function questionsmove($buffer, $chapter)
    {
        foreach ($buffer as $index => $question) {
            $buffer[$index]['chapter'] = $chapter;
        }

        return $buffer;
    }
    /**
     * test if a given string is a valid json string
     *
     * @param  string $string the string value to be tested
     * @return bool wheither the string is json
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function isJson($string)
    {

        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
    /**
     * generates the quiz data to be fed into the quiz attempting page
     *
     * @param  int $quiz the quiz ID
     * @return array an array buffer containing quiz/assessment information
     * @throws \Exception
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function getQuizData($quiz)
    {
        $quiz = Quiz::findOne($quiz);
        if ($quiz == null) {
            throw new Exception("Assessment not found !",6);
        }
        $quizdata = [];
        $sessionid = $quiz->quizID;
        $quizsession = yii::$app->session->get($sessionid);
        $student = yii::$app->user->identity->username;

        if (!$quiz->isReadyTaking()) {
            throw new Exception("You are not allowed to take this Assessment before the due time ! This Assessment starts on " . $quiz->start_time,1);
        }
        if (!($quiz->isAccessGranted()) && (new StudentQuiz())->isRegistered($student, $quiz->quizID)) {
            throw new Exception("You are not allowed to do this Assessment more than one time !",4);
        }
        if ($quiz->isExpired()) {
            throw new Exception("Cannot Take Expired Assessment",2);
        }
        if($this->timer($quiz->quizID)==null)
        {
            throw new Exception("The time allocated for this assessment is over !",5);  
        }
        if ($quiz->isAccessRestricted() && !$quiz->isAccessGranted()) {
            throw new QuizRestrictedException(); //not an error
        }
        if ($quiz->attempt_mode == "massive") {
            if ($quiz->quiz_file == null) {
                    throw new Exception("Assessment File Not Found !",7);
            }
            $quizdata = ($quizsession != null) ? $quizsession : $this->randomizeMassiveQuizType($this->quizReader($quiz->quizID));
            yii::$app->session->set($sessionid, $quizdata);
        } else {
            $quizdata = $this->generateRandomQuiz($quiz->quizID);
        }
        return $this->cleanQuizData($quizdata);
    }
    /**
     * removes true responses before sending the quiz to the student for attempting
     *
     * @param  array $quizdata an array buffer containing the quiz information
     * @return array an array buffer containing the resulting quiz information
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    private function cleanQuizData($quizData)
    {
        foreach ($quizData as $index => $question) {
            unset($question['options']['true-choices']);
            $quizData[$index] = $question;
        }

        return $quizData;
    }
    /**
     * saves submitted file for manually markable questions
     *
     * @param  string $content the html content to be used to generate
     *                         the pdf file
     * @param  int    $quiz    the quizID
     * @return string the path of the created file
     * @throws \yii\base\UserException;
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function quizSubmitFileSaver($content, $quiz)
    {
        $quiz = Quiz::findOne($quiz);
        if ($content != null) {
            $mpdf = new Mpdf(['orientation' => 'P']);
            $mpdf->setFooter('{PAGENO}');
            $stylesheet = file_get_contents('css/capdf.css');
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetWatermarkText(yii::$app->params['appUrl'], 0.09);
            $mpdf->showWatermarkText = true;
            $mpdf->WriteHTML('<div align="center"><img src="img/logo.png" width="110px" height="110px"/></div>', 2);
            $mpdf->WriteHTML('<p align="center"><font size=5>' . yii::$app->user->identity->username . '</font></p>', 3);
            $mpdf->WriteHTML('<p align="center"><font size=5>' . $quiz->quiz_title . '</font></p>', 3);
            $mpdf->WriteHTML('<hr width="80%" align="center" color="#000000">', 2);
            $mpdf->WriteHTML("$content", 3);
            $filename = uniqid() . "_" . $quiz->quizID . ".pdf";
            $mpdf->Output("storage/submit/" . $filename, "F");
            return $filename;
        } else {
            throw new UserException('No content',8);
        }
    }
    /**
     * generates random question for individually attempted quizes
     *
     * @param  int $quiz the quizID
     * @return array an array buffer containing quiz information
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    private function generateRandomQuiz($quiz)
    {

        $quizdata = $this->quizReader($quiz);
        $chapters = $quizdata['chapters'];
        $types=(isset($quizdata['qtypes']) && $quizdata['qtypes']!=null)?$quizdata['qtypes']:null;
        $quiz = Quiz::findOne($quiz);
        $sessionid =$quiz->quizID;
        $quizsession = yii::$app->session->get($sessionid);
        if ($quizsession != null) {
            return $quizsession;
        }
        $randomQuiz = [];
        $num_questions = $quiz->num_questions;
        $questionsBank = [];
        foreach ($chapters as $ind => $chapter) {
            $chapterbank = $this->rawChapterBankReader($chapter);
            $questionsBank += $chapterbank;
        }
        $questionsBank=$this->filterQuestionsBank($questionsBank,$types);
        $questionsindex = array_rand($questionsBank, $num_questions);
        if (is_array($questionsindex)) {
            foreach ($questionsindex as $index => $value) {
                $randomQuiz[$value] = $questionsBank[$value];
            }
        } else {
            $randomQuiz[$questionsindex] = $questionsBank[$questionsindex];
        }
        yii::$app->session->set($sessionid, $randomQuiz);
        return $randomQuiz;
    }
    /**
     * filters the questions types to be included in a given assessment
     * @param array $randomQuiz an array buffer containing all randomly chosen questions
     * @param array $types the types of questions to be filtered
     * @return array an array buffer containing the filtered questions
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     */
    private function filterQuestionsTypes($randomQuiz,$types)
    {
        if($types==null){
            return $randomQuiz;
        }

        foreach($randomQuiz as $index=>$question)
        {
            $questiontype=$question['type'];
            if(in_array($questiontype,$types))
            {
                continue;
            }
            unset($randomQuiz[$index]);
        }
        return $randomQuiz;
    }
     /**
     * filters the questions types to be included in a given assessment
     * @param array $questionsBank an array buffer containing all questions
     * from which assessment questions have to be randomly chosen
     * @param array $types the types of questions to be filtered
     * @return array an array buffer containing the filtered questions
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     */
    private function filterQuestionsBank($questionsBank,$types)
    {
        if($types==null)
        {
            return $questionsBank;
        }

        foreach($questionsBank as $index=>$question)
        {
            $questiontype=$question['type'];
            if(in_array($questiontype,$types))
            {
                continue;
            }

            unset($questionsBank[$index]);
        }

        return $questionsBank;
    }
    /**
     * count the number of question from given chapters
     * @param array $chapters an array buffer containing all chapters
     * @param array $types an array buffer containing all chosen types
     * @return int the number of questions in all the given chapters
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     */

     private function getQuestionsCount($chapters,$types)
     {
        $questionscount = 0;
        foreach ($chapters as $ind => $chapter) {
            $chapterbank = $this->rawChapterBankReader($chapter);
            $chapterbank=$this->filterQuestionsBank($chapterbank,$types);
            $questionscount += count($chapterbank);
        }

        return $questionscount;
     }
    /**
     * shuffles questions for massive attempted quizes
     *
     * @param  array $array an array buffer containing quiz questions
     * @return array an array buffer containing shuffled questions
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    private function randomizeMassiveQuizType($array)
    {

        $newarray = $array;
        $keys = array_keys($newarray);
        shuffle($keys);
        $new = [];
        foreach ($keys as $key) {
            $new[$key] = $newarray[$key];
        }

        return $new;
    }
    /**
     * registers a student before entering the online assessment
     *
     * @param  int $quizID the quiz ID
     * @return bool wheither the student has successfully been registered
     * @throws Exception
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function registerStudent($quizID)
    {
        $student = yii::$app->user->identity->student->reg_no;
        $studentquiz = new StudentQuiz();
        $quiz = Quiz::findOne($quizID);
        if ($studentquiz->isRegistered($student, $quizID)) {
            return true;
        }

        if ($quiz->attempt_mode == "massive" && $quiz->isAttemptingTimeOver()) {
            throw new Exception("Attempting Time Is Over, Online Massive-Type Assessments should be attempted lately 20 minutes after their starting time!",9);
        }
        $studentquiz->reg_no = $student;
        $studentquiz->quizID = $quizID;
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $studentquiz->attempt_time = date("Y-m-d H:i:s");
        $studentquiz->status = "attempted";
        $studentquiz->score = 0;
        if ($studentquiz->save()) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * marks the submited quiz, and stores the file for manual marked quizes
     *
     * @param  array $responseBuffer an array buffer containing the submited responses information
     * @return array|void an array buffer containing the student score and a message in case
     * there are questions to be manually marked
     * @throws \Exception
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function markQuiz($responsesBuffer)
    {
        $quiz = $responsesBuffer['quiz'];
        if ((new StudentQuiz())->isSubmitted($quiz)) {
            throw new Exception("You are not allowed to submit twice !",4);
        }
        $scorecount = 0;
        $quizquestions = [];
        $shortanswers = 0;
        foreach ($responsesBuffer as $index => $responses) {
            if ($index == 'quiz' || $index == '_csrf-frontend') {
                      continue;
            }
            array_push($quizquestions, $index);
            $trueanswers = $this->loadQuestionsTrueAnswers($index);
            $type = $this->getQuestionType($index);
            if ($type == "true-false" || $type == "multiple-choice") {
                $scorecount+=$this->getMultipleChoicesScore($trueanswers, $responses, $index);
            } elseif ($type == "fill-in-blanks") {
                $scorecount += $this->getQblanksScore($trueanswers, $responses['inputs'],$index);
            } elseif ($type == "matching") {
                $scorecount += $this->getQmatchesScore($trueanswers, $responses['studentmatches'],$index);
            } elseif ($type == "enum") {
                $scorecount += $this->getQenumScore($trueanswers, $responses['studentalternatives'],$index);
            } else {
                $shortanswers++;
                continue;
            }
        }
        $quizmod = Quiz::findOne($quiz);
        $instructorid=$quizmod->instructorID;
        $totalmarks = $quizmod->total_marks;
        // $attemptmode = $quizmod->attempt_mode;
        $filename = "";
        $message = "";
        $markables = null;
     
        //saving short answer questions in a file for manual marking
        if ($shortanswers > 0) {
            $content = yii::$app->controller->renderPartial("student/submitfile", ['submitted' => $responsesBuffer,'instructor'=>$instructorid]);
            try {
                $filename = $this->quizSubmitFileSaver($content, $quiz);
                $message = $shortanswers . " more question(s) are awaiting being manually marked  by the instructor !";
                $markables = $shortanswers;
                $vfp=new Vfp("Short answer questions save");
                (new Invigilator)->recordVFP($vfp,$quiz);
            } catch (UserException $f) {
                $vfp=new Vfp("Short answer questions save",$f->getMessage(),'System',$f->getCode());
                (new Invigilator)->recordVFP($vfp,$quiz);
                return;
            }
        }
       
        if((new Invigilator)->recordSubmission($responsesBuffer))
        {
            $vfp=new Vfp("Submission Content vfp save");
            (new Invigilator)->recordVFP($vfp,$quiz); 
        }
        
        $scorecount = round(($scorecount * $totalmarks) / $this->computeTotalMarks($quizquestions),2);
        

        $this->updateStudentQuizScore($quiz, $scorecount, $filename, $markables);
        $vfp=new Vfp("Record student score");
        (new Invigilator)->recordVFP($vfp,$quiz);
        return ['score' => $scorecount,'totalmarks' => $totalmarks,'message' => $message];
    }
    /**
     * retrieves the questions score for correct or incorrect answers
     * @param int $questionID the ID of the question in a quiz
     * @param string $type the type of score to be fetched either for incorrect or correct answers
     * @return double the question's score for either correct or incorrect answer
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     */
    public function getQscore($questionID,$type)
    {
      $question=$this->findQuestion($questionID);
      $score_correct=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1;
      $score_incorrect=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0;

      return ($type=="correct")?$score_correct:$score_incorrect;

    }
    /**
     * computes and returns the score for fill-in-blanks questions
     *
     * @param  array $blanks    an array buffer containing the blanks existing in a question
     * @param  array $responses an array buffer containing the student's reponses
     * @param int $questionID the ID of the question for which we want to fetch scores
     * @return int the total score
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function getQblanksScore($blanks, $responses,$questionID)
    {
        $score = 0;
        $blanks = $this->toLower($blanks);
        $responses = $this->toLower($responses);
        foreach ($blanks as $in => $blank) {
            if($responses[$in]==null){continue;} // no response provided for this blank
            if ($blank != $responses[$in]) {
                $score+=$this->getQscore($questionID,"incorrect");
                continue;
            }
            $score+=$this->getQscore($questionID,"correct");
        }
        return $score;
    }
    /**
     * computes and returns the score for matching items questions
     *
     * @param  array $matches   an array buffer containing the best matches for a question
     * @param  array $responses an array buffer containing the student's reponses
     * @param int $questionID the ID of the question for which we want to fetch scores
     * @return int the total score
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function getQmatchesScore($matches, $responses,$questionID)
    {
        $matches = array_keys($matches);
        $score = 0;
        foreach ($responses as $in => $match) {
            if($match==null){continue;} // response not provided for this item
            if ($match != $matches[$in]) {
                $score+=$this->getQscore($questionID,"incorrect");
                continue;
            }
            $score+=$this->getQscore($questionID,"correct");
        }
        return $score;
    }
    /**
     * computes and returns the score for enumeration questions
     *
     * @param  array $alternatives an array buffer containing the best alternatives for a question
     * @param  array $responses    an array buffer containing the student's reponses
     * @param int $questionID the ID of the question for which we want to fetch scores
     * @return int the total score
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function getQenumScore($alternatives, $responses,$questionID)
    {
        $score = 0;
        $alternatives = $this->toLower($alternatives);
        $responses = $this->toLower($responses);
        foreach ($responses as $in => $response) {
            if($response==null){continue;}
            if (!in_array($response, $alternatives)) {
                $score+=$this->getQscore($questionID,"incorrect");
                continue;
            }
            $score+=$this->getQscore($questionID,"correct");
        }
        return $score;
    }
    /**
     * turn all responses to lowercase
     *
     * @param  array $buffer an array buffer containing all responses
     * @return array the new array containing the converted values
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function toLower($buffer)
    {
        foreach ($buffer as $ind => $item) {
            $buffer[$ind] = strtolower($item);
        }
        return $buffer;
    }
    /**
     * adds the manual marked score to the existing score
     *
     * @param  int   $submitID the ID of the assessment submit
     * @param  float $score    the obtained score
     * @return bool if the score has been added and saved successfully
     * @throws \yii\base\UserException if the given submit is already fully marked
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function addManualMarkedScore($submitID, $score)
    {
        $submit = StudentQuiz::findOne($submitID);
        if ($submit->isFullyMarked()) {
            throw new UserException("This submit is fully marked, You might need to update score instead !");
        }
        $submissions=array_keys((new Invigilator)->getMarkablesubmissions($submit->regNo->userID,$submit->quizID));
        $total_markable_marks=$this->computeTotalMarks($submissions);
        $quizmaxima = $submit->quiz->total_marks;
        $submit->score += ($score * $quizmaxima) / $total_markable_marks;
        $submit->status = "marked";
        if ($submit->save()) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * updates the student's score after the submission of the assessment
     *
     * @param  int    $quiz     the quizID
     * @param  float  $score    the  new score for the student
     * @param  string $file     the file path containing manually markable questions
     *                          if they exist
     * @param  int    $markable the number of markable questions found in the submitted assessment
     * @throws Exception
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    private function updateStudentQuizScore($quiz, $score, $file, $markables)
    {
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $student = yii::$app->user->identity->student->reg_no;
        $studentquiz = new StudentQuiz();
        $submit_time = date("Y-m-d H:i:s");
        if (!$studentquiz->isRegistered($student, $quiz)) {
            $this->registerStudent($quiz);
        }
        if ($studentquiz->isSubmitTimeOver($quiz, $submit_time)) {
            throw new Exception("submitting time is over",5);
        }
        $studentQuizUpdate = $studentquiz->find()->where(["reg_no" => $student,"quizID" => $quiz])->one();
        $studentQuizUpdate->score = $score;
        $studentQuizUpdate->file = $file != null ? $file : null;
        $studentQuizUpdate->submit_time = $submit_time;
        $studentQuizUpdate->status = "submitted";
        $studentQuizUpdate->markables = $markables;
        $studentQuizUpdate->save();
    }
    /**
     * retrieves the number of manually markable questions
     *
     * @param  int $quiz   the quiz ID
     * @param  int $submit the submit ID
     * @return int the number of manually markable questions
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function getmarkables_num($quiz, $submit)
    {
        if (Quiz::findOne($quiz)->attempt_mode == "massive") {
            return count($this->getQuizManualMarkableQuestions($quiz));
        } else {
            return (StudentQuiz::findOne($submit))->markables;
        }
    }
    /**
     * finds and returns the manual markable questions from the assessment
     *
     * @param  int $quizID the assessment ID
     * @return array an array buffer containing all markable questions from the quiz
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function getManualMarkableQuestions($quizID)
    {
        $quizmode=(Quiz::findOne($quizID))->attempt_mode;
        if($quizmode=='individual')
        {
            return [];
        }
        $quiz=$this->quizReader($quizID);
        foreach($quiz as $questionID=>$question)
        {
            if($question['type']!="shortanswer")
            {
                unset($quiz[$questionID]);
            }
        }
        return $quiz;
    }
    /**
     * finds and returns the question type
     *
     * @param  int $qindex the question ID
     * @return string|void the question type or nothing if the question
     * does not exist
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function getQuestionType($qindex)
    {
        $bank = $this->rawQuestionsBankReader();
        foreach ($bank as $index => $question) {
            if ($index != $qindex) {
                continue;
            }

            return $question['type'];
        }
    }
    /**
     * loads true answers for a given question
     *
     * @param  int $questionindex the index of the question which is also the ID of the question
     *                            in the bank
     * @return array|null an array buffer containing true answers for a question
     * or nothing if the type of the question is not well recognized
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    private function loadQuestionsTrueAnswers($questionindex)
    {
        $bank = $this->rawQuestionsBankReader();
        foreach ($bank as $index => $question) {
            if ($index != $questionindex) {
                continue;
            }

            if ($question['type'] == "true-false" || $question['type'] == "multiple-choice") {
                return $question['options']['true-choices'];
            } elseif ($question['type'] == "fill-in-blanks") {
                return $question['blanks'];
            } elseif ($question['type'] == "matching") {
                return $question['matches'];
            } elseif ($question['type'] == "enum") {
                return $question['alternatives'];
            } else {
                return null;
            }
        }
    }
    /**
     * checks if a given answer is a true answer compared to the specified true answers
     *
     * @param  array $trueresponses    an array buffer containing true responses
     * @param  array $studentresponses an array buffer containing student responses
     * @param  int   $question         the question ID of the question in questions bank
     * @return bool wheither the question is true
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    private function isTrueResponse($trueresponses, $studentresponses, $question)
    {

        foreach ($studentresponses as $in => $response) {
            if ($response == null) {
                  unset($studentresponses[$in]);
            }
        }
        if ($this->findQuestion($question)['multiple'] != 'on') {
            $studentresponses = array_flip($studentresponses);
        }
        if (count($trueresponses) != count($studentresponses)) {
            return false;
        }
        $matches = array_intersect_key($trueresponses, $studentresponses);
        if ($matches == null) {
            return false;
        }
        if (count($matches) != count($trueresponses)) {
            return false;
        }


        return true;
    }

     /**
     * returns the total score for multiple choice questions
     *
     * @param  array $trueresponses    an array buffer containing true responses
     * @param  array $studentresponses an array buffer containing student responses
     * @param  int   $question         the question ID of the question in questions bank
     * @return double  the total score for a given question
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function getMultipleChoicesScore($trueresponses, $studentresponses, $question)
    {
        $score=0;
        foreach ($studentresponses as $in => $response) {
            if ($response == null) {
                  unset($studentresponses[$in]);
            }
        }
        if ($this->findQuestion($question)['multiple'] != 'on') {
            $studentresponses = array_flip($studentresponses);
        }
        foreach($studentresponses as $key=>$res)
        {
            if(!array_key_exists($key,$trueresponses))
            {
                $score+=$this->getQscore($question,"incorrect");
                continue;
            }
            $score+=$this->getQscore($question,"correct");
        }
        return $score;
    }
    /**
     * computes the remaining time and returns it
     *
     * @param  int $quiz the quiz ID
     * @return string|null the remaining time or null if the time is over
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function timer($quiz)
    {
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $quiz = Quiz::findOne($quiz);
        $start = null;
        $savedstart = yii::$app->session->get("starttime" . $quiz->quizID);
        if ($quiz->attempt_mode == "individual") {
            $start = ($savedstart != null) ? $savedstart : date("Y-m-d H:i:s");
        } else {
            $start = ($savedstart != null) ? $savedstart : $quiz->start_time;
        }

        if ($start != null) {
            yii::$app->session->set("starttime" . $quiz->quizID, $start);
        }
        $starttodate = new \DateTime($start);
        $starttodate->modify("+{$quiz->duration} minutes");
        $endtime = strtotime($starttodate->format('Y-m-d H:i:s'));
        $nowtime = strtotime(date("Y-m-d H:i:s"));
        $end = date_create($starttodate->format('Y-m-d H:i:s'));
        $diff = date_diff($end, date_create(date('Y-m-d H:i:s')));
        if ($nowtime < $endtime) {
            return $diff->format('%H:%i:%s');
        }

        return null;
    }
    /**
     * downloads the quiz in the pdf format
     *
     * @param  string $content the html string to be used to generate the pdf file
     * @return null|string
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function downloadPDFQuiz($content,$title)
    {
        if ($content != null) {
            $instructor = Yii::$app->user->identity->instructor;
            $name = $instructor->full_name;
            $mpdf = new Mpdf(['orientation' => 'P']);
            $mpdf->setFooter('{PAGENO}');
            $stylesheet = file_get_contents('css/capdf.css');
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetWatermarkText(yii::$app->params['appUrl'], 0.09);
            $mpdf->showWatermarkText = true;
            $mpdf->WriteHTML('<div align="center"><img src="img/logo.png" width="80px" height="80px"/></div>', 2);
            $mpdf->WriteHTML('<p align="center"><font size=5>' . $title . '</font></p>', 3);
            $mpdf->WriteHTML("<table border='1' cellspacing='0'  width=100%><tr style='background-color:#def'><td>Full name</td><td>Registration No</td><td>Program</td></tr>",3);
            $mpdf->WriteHTML("<tr ><td height=30px width=50%> </td><td height=30px> </td ><td height=30px width=20%> </td></tr>",3);
            $mpdf->WriteHTML("</table>");
            $mpdf->WriteHTML('<hr width="80%" align="center" color="#000000" style="margin-top:30px">', 2);
            $mpdf->WriteHTML($content, 3);
            $filename ="exam.pdf";
            $filename = str_replace(' ', '', $filename);
            $mpdf->Output($filename, "D");
            return null;
        } else {
            return 'no content';
        }
    }
    /**
     * creates the pdf file containing assessment tokens
     *
     * @param  string $content the html content to be use for the pdf file
     * @return null
     * @throws \yii\base\UserException
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    public function downloadPDFTokens($content)
    {

        if ($content != null) {
            $mpdf = new Mpdf(['orientation' => 'P']);
            $mpdf->setFooter('{PAGENO}');
            $stylesheet = file_get_contents('css/capdf.css');
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetWatermarkText(yii::$app->params['appUrl'], 0.15);
            $mpdf->showWatermarkText = true;
            $mpdf->WriteHTML('<div><img src="img/logo.png" width="40px" height="40px"/></div>', 2);
            $mpdf->WriteHTML('<p><font size=3>' . yii::$app->params['orgName'] . '<br>Online Assessment Access Tokens</font></p>', 3);
            $mpdf->WriteHTML('<hr width="80%" align="center" color="#000000">', 2);
            $mpdf->WriteHTML($content, 3);
            $filename = "tokens.pdf";
            $mpdf->Output($filename, "D");
            return null;
        } else {
            throw new UserException("No tokens found !");
        }
    }
    public function downloadMarkableSubmits($assessmentID)
    {
        $assessmentID = ClassRoomSecurity::decrypt($assessmentID);
        $assessment =Quiz::findOne($assessmentID);

            $dir = "storage/submit/";

            if (!file_exists(realPath($dir))) {
                mkdir($dir);
            }

            $ziptmp = $dir . "submits_tmp.zip";

            $zipper = new \ZipArchive();

            if (!$zipper->open($ziptmp, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
                 throw new \Exception("could not create archive");
            }
     //building download information

            $readme = "Assessment submits download information \n------------------------------------------";
            $assessmenttitle = $assessment->quiz_title;
            $assessment_type = $assessment->attempt_mode;
            $expire = $assessment->start_time." ( +".$assessment->duration." min ) EAT";
            $downloadinstructor = yii::$app->user->identity->instructor->full_name;
            $no_submits = 0;
            $no_files = 0;
            $no_missing = 0;
            $missing_files = "";
            $readme .= "\nAssessment Title: " .  $assessmenttitle . "\nAttempt Mode: " . $assessment_type . "\nExpiring/Expired on: " . $expire . "\n";
            $readme .= "Downloaded By Instructor: " . $downloadinstructor . "\n";
            $submits = null;

            $submits=$assessment->studentQuizzes;
            $no_submits = count($submits);
            if (empty($submits) || $submits == null) {
                throw new \Exception("No submits found");
            }

            foreach ($submits as $in=>$submit) {
                if($submit->status!="submitted" && $submit->status!="marked")
                {
                    continue;
                }
                   $file = $submit->file;
                   $regno = str_replace('/', '-', $submit->reg_no);
                if (file_exists("storage/submit/" . $file)) {
                    $no_files++;
                    $localfile = $regno . "." . pathinfo($file, PATHINFO_EXTENSION);
                    $zipper->addFile("storage/submit/" . $file, $localfile);
                    continue;
                }

                $missing_files .= $regno . ",";
                $no_missing++;
            }

            $ending = "Copyright 2020 - " . date('Y') . " The University of Dodoma,  All Rights Reserved.\n\n UDOM-eCLASSROOM V3.0";
            $readme .= "Total Number of Submits: " . $no_submits . "\nNumber Of Downloaded Files: " . $no_files . "\nNumber Of Missing Files: " . $no_missing . "\n";
            $readme .= "Missing Files: " . $missing_files . "\n\n\n\n\n\n";
            $zipper->addFromString('Readme.txt', $readme);

            $zipper->close();

            Yii::$app->response->sendFile($ziptmp, "Assessment_" . $assessment->start_time . "_Submits.zip")->on(\yii\web\Response::EVENT_AFTER_SEND, function ($event) {
                unlink($event->data);
            }, $ziptmp);
            if (connection_aborted()) {
                unlink($ziptmp);
            }
      //register_shutdown_function(unlink($ziptmp));
      
    }
    /**
     * retrieves the marked submits for a given assessment
     * @param int $assessmentID the assessment ID
     * @return array an array buffer containing all marked submits
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     * 
     */
    public function getMarkedSubmits($assessmentID)
    {
        $assessment=Quiz::findOne($assessmentID);
        $attempts=$assessment->studentQuizzes;

        foreach($attempts as $in=>$attempt)
        {
            if($attempt->status!="marked")
            {
               unset($attempts[$in]);
            }
        }

        return $attempts;
    }
     /**
     * retrieves the marked submits for a given assessment
     * @param int $assessmentID the assessment ID
     * @return array an array buffer containing all marked submits
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     * 
     */
public function getMarkableSubmits($assessmentID)
{
    $assessment=Quiz::findOne($assessmentID);
        $attempts=$assessment->studentQuizzes;

        foreach($attempts as $in=>$attempt)
        {
            if($attempt->status!="submitted")
            {
               unset($attempts[$in]);
            }
        }

        return $attempts;
}
/**
 * computes and return the percentage of marked submits against the total number
 * of submits
 * @param int $assessmentID the assessment ID
 * @return double the percentage
 * @author khalid hassan <thewinner@gmail.com>
 * @since 3.0.0
 * 
 */
    public function getMarkedPerc($assessmentID)
    {
        //return $assessmentID;
        $total=$this->getMarkableSubmits($assessmentID);
        $total=($total!=null)?count($total):1;
        $marked=$this->getMarkedSubmits($assessmentID);
        $marked=($marked!=null)?count($marked):0;

        return ($marked>0)?round(($marked*100)/$total):0;
    }

    /**
     * finds the full assessment as submitted by the student
     * @param int $student the userID of the student
     * @param int $assessment the assessment ID 
     * @return array an array buffer containing all questions related to submitted ones
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     * 
     */

     public function getSubmittedMarkables($student,$assessment)
     {
        try
        {
       $questions=array_keys((new Invigilator)->getMarkablesubmissions($student,$assessment));
       $qnum=1;
       foreach($questions as $in=>$question)
       {
        $questionbuffer=$this->findQuestion($question);
        if($questionbuffer['type']!="shortanswer")
        {
            unset($questions[$in]);
            continue;
        }
        $questionbuffer['qnum']=$qnum;
         $questions[$in]=$questionbuffer;
         $qnum++;
       }

       return $questions;
        }
        catch(UserException $e)
        {
            return [];
        }
   }
}
