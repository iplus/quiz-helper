<?php
require_once('lib/autoload.php');
/** @var \Base $f3 */
$f3 = \Base::instance();
$f3->set('DB', new DB\SQL('sqlite:questions.sqlite'));

function get_answer(\Base $f3, $params)
{
    try {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: token');
        $db = $f3->get('DB');
        $txt = $f3->get('POST.data');
        $txt = json_decode($txt);
        $res = $db->exec('SELECT * FROM q WHERE text = ?', $txt->question);
        if (!$res) {
            $db->exec('INSERT INTO q(text, answers) VALUES(?, ?)', array($txt->question, implode("\n", $txt->answers)));
        } else {
            echo $res[0]['answer'];
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function show_question(\Base $f3, $params)
{
    $ans = $f3->get('GET.answer');
    $rowid = $f3->get('GET.rowid');
    $db = $f3->get('DB');

    if ($rowid && $ans == "") {
        $f3->reroute('/');
    } else
        if ($ans && $rowid) {
            $f3->dump($rowid);
            $res = $db->exec('UPDATE q SET answer = ? WHERE rowid = ?', array($ans, $rowid));
            $f3->reroute('/');
        } else {

            $res = $db->exec("SELECT rowid, * FROM q WHERE answer = '' ORDER BY random() LIMIT 1", $txt);
            $f3->set('question', $res);
            echo View::instance()->render('show_question.htm');
        }
}

$f3->route('POST /', 'get_answer');
$f3->route('GET /', 'show_question');
$f3->run();
