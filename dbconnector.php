<?php
class dataBaseConnector
{
//функция подключения к базе данных
    public $hostname = "localhost"; // название хоста
    public $database = "moodle"; // название бд
    public $username = "root"; // имя пользователя
    public $password = "root"; // пароль к бд
    public $result; // возвращаемые данные запроса
    public $rows;
    public $subrows;
    private $connection;  // подключение
    private $query; // текст запроса


// создание соединения

    function connectDB()
    {
        $this->connection = mysqli_connect($this->hostname, $this->username, $this->password, $this->database);
        // проверка соединения
        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
        echo "Connected successfully";
    }

    function queryBD($query){
        $this->query = $query;
        $this->result = mysqli_query($this->connection, $this->query);
        $this->rows = mysqli_fetch_all($this->result);
    }

    function subQueryBD($query){
        $this->query = $query;
        $this->result = mysqli_query($this->connection, $this->query);
        $this->subrows = mysqli_fetch_all($this->result);
    }

    function courseName_Identifier(){
        $this->queryBD("Select `mdl_course`.`id`, `mdl_course`.`fullname`, `mdl_course`.`format` from `mdl_course` where `mdl_course`.`format` != 'site'");
        for ($i = 0; $i <= count($this->rows) - 1; $i++) {
            echo "<li>";
            echo "<a href='quizdef.php?id=" . $this->rows[$i][0] . "'>" . $this->rows[$i][1];
            echo "</li>";
        }
    }

    function quizName_identifier()
    {
        $this->queryBD("SELECT `mdl_quiz`.`id`, `mdl_quiz`.`name`, `mdl_quiz`.`intro` FROM `mdl_quiz` where `mdl_quiz`.`course` =" . $_GET['id']);
        for ($i = 0; $i <= count($this->rows) - 1; $i++) {
            echo "<li>";
            echo "<a href='quizdef.php?id=" . $this->rows[$i][0] . "'>";
            echo preg_replace('/<.*?>/', ' ', $this->rows[$i][1]) . ' ' . preg_replace('/<.*?>/is', ' ', $this->rows[$i][2]);
            echo "<a>";
            echo "</li>";

        }
    }

    function questionList_Identifier()
    {
        $this->queryBD("SELECT mdl_quiz.name, mdl_question.name, mdl_question.id, mdl_question.qtype
                        FROM mdl_question 
                        LEFT JOIN mdl_quiz_slots on mdl_quiz_slots.questionid = mdl_question.id 
                        LEFT JOIN mdl_quiz on mdl_quiz.id = mdl_quiz_slots.quizid
                        where mdl_quiz_slots.quizid = " . $_GET['id']);
    }

    function questionList_Compactor(){
        $this->questionList_Identifier();
        echo "<h2>" . $this->rows[0][0] . "</h2>";
        for ($i = 0; $i <= count($this->rows) - 1; $i++) {
            echo "<div>" .
                "<div>" .
                $this->rows[$i][1] .
                "</div>" .
                "<div>" .
                $this->questionType_Identifier($i) .
                "</div>" .
                "</div>";
        }
    }

    function questionType_Identifier($i)
    {
        switch ($this->rows[$i][3]){
            case "multichoice":
                //$this->multiChoice_Identifier($this->rows[$i][2]);
                break;
            case "truefalse":

                break;
        }
    }

//    function multiChoice_Identifier($questionId){
//        $this->subQueryBD(" SELECT mdl_question_answers.answer
//        FROM mdl_question
//        LEFT JOIN mdl_question_answers on mdl_question_answers.question = mdl_question.id
//        WHERE mdl_question.id = " . $questionId);
//        for($i = 0; $i <= count($this->subrows) - 1; $i++){
//            echo $this->subrows[$i][0];
//        }
//    }
}
$dataBase = new dataBaseConnector();
$dataBase->connectDB();