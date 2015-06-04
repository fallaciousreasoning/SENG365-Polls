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
                $this->response(array("errorMessage"=>"Poll does not exist!"), 404);
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
    public function polls_post($pollId=NULL){
        //Temporary workaround as I can't access PUT data for some reason
        if ($pollId){
            $this->polls_put($pollId);
            return;
        }

        $this->load->model("poll");
        $this->load->model("answer");

        try {
            $data = json_decode(trim(file_get_contents('php://input')), true);

            //Question and title don't need html escaping, angular is magic
            $pollId = $this->poll->create($data["title"], $data["question"]);
            $answers = $data["answers"];
            $answers_count = count($answers);

            //Insert all the answers
            for ($i = 0; $i < $answers_count; ++$i) {
                $answers[$i]["optionNo"] = $i + 1;
                $answers[$i]["questionId"] = $pollId;

                //Answer doesn't need html escaping, angular is magic
                $this->answer->create($pollId, $i, $answers[$i]["answer"]);
            }

            header("Location: /services/polls/$pollId");
            $this->response(NULL, 201);
        }catch (Exception $e){
            $this->response(array("errorMessage"=>"Unable to create poll!"), 404);
        }
    }

    /**
     * Updates an existing poll and returns a 200. For some reason calling this method
     * directly has not effect. This method has been private, as I can't seem to access
     * put data :'(. You can call it through POST
     * @param $pollId The id of the poll to update
     */
    private function polls_put($pollId){
        $this->load->model("poll");
        $this->load->model("answer");

        $data = json_decode(trim(file_get_contents('php://input')), true);

        try {
            //Angular magically escapes stuff
            $this->poll->update($pollId, $data["title"], $data["question"]);

            $answers = $data["answers"];
            $answers_count = count($answers);

            //Remove all existing answers
            $this->answer->deleteAll($pollId);

            for ($i = 0; $i < $answers_count; ++$i) {
                $answer = $answers[$i];

                $answer["optionNo"] = $i + 1;
                $answer["questionId"] = $pollId;

                //Angular magically doesn't worry about html being escaped
                $this->answer->create($pollId, $answer["optionNo"], $answer["answer"], $answer["id"]);
            }
        }
        catch (Exception $e){
            $this->response(array("errorMessage"=>"Unable to update poll"), 404);
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
            $this->response(array("errorMessage"=>"No such poll :'("), 404);
        }
    }

    /**
     * Gets a list of all the votes on a specific poll
     * @param $pollId The id of the poll to get votes for
     */
    public function votes_get($pollId){
        $this->load->model("answer");
        $this->load->model("vote");

        try {
            $answers = $this->answer->getAnswers($pollId);
            $votes = $this->vote->getVotes($pollId);

            $answerVotes = array();
            foreach ($answers as $answer) {
                //Set the number of votes for this answer to 0
                $answerVotes[$answer->optionNo - 1] = 0;
                foreach ($votes as $vote){
                    //Foreach vote that counts towards this answer
                    //add one to the number of vote it has
                    if ($vote->answerId == $answer->id){
                        $answerVotes[$answer->optionNo - 1] += 1;
                    }
                }
            }

            $this->response($answerVotes, 200);
        }catch (Exception $e){
            $this->response(array("errorMessage"=>"Poll does not exist!"), 404);
        }
    }

    /**
     * Posts a vote for a specific poll
     * @param $pollId The id of the poll
     * @param $optionNo The option to vote for, within the poll
     */
    public function votes_post($pollId, $optionNo){
        $this->load->model("answer");
        $this->load->model("vote");

        try {
            $answer = $this->answer->getAnswer($pollId, $optionNo);

            $this->vote->vote($pollId, $answer->id, $this->input->ip_address());
            $this->response(NULL, 201);
        }catch (Exception $e){
            $this->response(array("errorMessage"=>"The poll or option does not exist!"), 404);
        }
    }

    /**
     * Deletes all the answers for a specific poll
     * @param $pollId The id of the poll to delete answers for
     */
    public function votes_delete($pollId) {
        $this->load->model("answer");
        $this->load->model("vote");

        try {
            $this->vote->clearVotes($pollId);
        }catch (Exception $e){
            $this->response(array("errorMessage"=>"The poll does not exist!"), 404);
        }
    }
}