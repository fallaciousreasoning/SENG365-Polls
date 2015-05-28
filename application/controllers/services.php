<?php

require APPPATH.'/libraries/REST_Controller.php';
/**
 * Created by PhpStorm.
 * User: jayha_000
 * Date: 27/05/2015
 * Time: 10:06 AM
 */

class Services extends REST_Controller {
    /**
     * Gets a list of all available polls or a specific poll (if one is specified)
     * @param null $pollId The id of the poll to fetch. If not specified you'll recieve an array of polls
     */
    public function polls_get($pollId = NULL){
        $this->load->model("poll");

        if (isset($pollId)) {
            try {
                $poll = $this->poll->getPoll($pollId);
                $this->response($poll, 200);
            }catch (Exception $e){
                $this->response(array("errorMessage"=>"Invalid poll Id!"), 500);
            }
        } else{
            $polls = $this->poll->getPolls();
            $this->response($polls, 200);
        }
    }

    /**
     * Should create a new poll object and return a 201 created status
     * with a location header pointing to the newly created poll object
     */
    public function polls_post(){
        $this->load->model("poll");
        $this->load->model("answer");

        $data = json_decode(trim(file_get_contents('php://input')), true);

        //HTML escape any characters that might be displayed
        $pollId = $this->poll->create(html_escape($data["title"]), html_escape($data["question"]));
        $answers = $data["answers"];
        $answers_count = count($answers);

        //Insert all the answers
        for ($i = 0; $i < $answers_count; ++$i){
            $answers[$i]["optionNo"] = $i;
            $answers[$i]["questionId"] = $pollId;
            //HTML escape stuff that might be displayed
            $this->answer->create($pollId, $i, html_escape($answers[$i]["answer"]));
        }

        header("Location: /services/polls/$pollId");
        $this->response(NULL, 201);
    }

    /**
     * Updates an existing poll and returns a 200
     * @param $pollId The id of the poll to update
     */
    public function polls_put($pollId){
        $this->load->model("poll");
        $this->load->model("answer");

        $data = json_decode(trim(file_get_contents('php://input')), true);

        //Store the old poll
        $old = $this->poll->getPoll($pollId);

        //Makes sure stuff that might be displayed gets escaped properly
        $this->poll->update($pollId, html_escape($data["title"]), html_escape($data["question"]));

        $answers = $data["answers"];
        $answers_count = count($answers);

        for ($i = 0; $i < $answers_count; ++$i) {
            $answer = $answers[$i];

            $answer["optionNo"] = $i;
            $answer["questionId"] = $pollId;

            if (isset($answer["id"])){
                $this->answer->update($answer["id"], $pollId, $answer["optionNo"], html_escape($answer["answer"]), $answer["votes"]);
            } else{
                $this->answer->insert($pollId, $answer["optionNo"], html_escape($answer["answer"]));
            }
        }
    }

    /**
     * Deletes an existing poll. Returns a 200 if successful
     * @param $pollId The id of the poll to delete
     */
    public function polls_delete($pollId){
        $this->load->model("poll");

        try {
            $this->poll->deleteRecursive($pollId);
        }catch (Exception $e){
            $this->response(array("errorMessage"=>"Invalid poll Id!"), 500);
        }
    }

    /**
     * Gets a list of all the votes on a specific poll
     * @param $pollId The id of the poll to get votes for
     */
    public function votes_get($pollId){
        $this->load->model("answer");

        $answers = $this->answer->getAnswers($pollId);
        $votes = array();
        foreach ($answers as $answer){
            $votes[] = $answer->votes;
        }

        $this->response($votes, 200);
    }

    /**
     * Posts a vote for a specific poll
     * @param $pollId The id of the poll
     * @param $optionNo The option to vote for, within the poll
     */
    public function votes_post($pollId, $optionNo){
        $this->load->model("answer");

        try {
            $answer = $this->answer->getAnswer($pollId, $optionNo);

            $this->answer->vote($answer->id);
            $this->response(NULL, 201);
        }catch (Exception $e){
            $this->response(array("errorMessage"=>"Invalid poll or option!"), 500);
        }
    }

    /**
     * Deletes all the answers for a specific poll
     * @param $pollId The id of the poll to delete answers for
     */
    public function votes_delete($pollId) {
        $this->load->model("answer");

        try {
            $this->answer->clearVotes($pollId);
        }catch (Exception $e){
            $this->response(array("errorMessage"=>"Invalid poll id"), 500);
        }
    }
}