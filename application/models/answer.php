<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Answer
 *
 * @author joh19
 */
class Answer extends CI_Model {
    public $id;
    public $pollId;
    public $optionNo;
    public $answer;

    public $votes;
    
    public function __construct() {
        $this->load->database();
    }

    /**
     * Creates a new answer
     * @param $pollId The id of the question the answer is for
     * @param $optionNo The option number of the answer
     * @param $answer The answer
     * @param int $votes The number of votes. Defaults to 0
     * @return mixed The insert id
     */
    public function create($pollId, $optionNo, $answer, $votes=0){
        //Escape data before letting it near the data base
        $pollId = (int)$pollId;
        $optionNo = (int)$optionNo;
        $votes = (int)$votes;

        $this->db->insert("ANSWERS", array("pollId"=>$pollId, "optionNo"=>$optionNo, "answer"=>$answer, "votes"=>$votes));
        return $this->db->insert_id();
    }

    /**
     * Updates an existing answer
     * @param $id The id of the answer to update
     * @param $pollId The new poll id
     * @param $optionNo The new option no
     * @param $answer The answer
     */
    public function update($id, $pollId, $optionNo, $answer){
        //Escape data before letting it near the database
        $id = (int)$id;
        $pollId = (int)$pollId;
        $optionNo = (int)$optionNo;

        $this->db->where("id", $id);
        $this->db->update("ANSWERS", array("pollId"=>$pollId, "optionNo"=>$optionNo, "answer"=>$answer));
    }

    /**
     * Gets an option from within a poll
     * @param type $pollId The id of the poll
     * @param type $optionNo The number of the option within the poll
     * @return Answer the answer fetched from the database
     * @throws Exception If more than 1 row or no rows were found.
     */
    public function getAnswer($pollId, $optionNo){
        //Escape the pollId and optionNo
        $pollId = (int)$pollId;
        $optionNo = (int)$optionNo;

        $answer = new Answer();
        $query = $this->db->get_where('ANSWERS', array('pollId'=>$pollId, 'optionNo'=>$optionNo));
        if ($query->num_rows !== 1) {
            throw new Exception("Answer $optionNo not found on poll $pollId");
        }

        $rows = $query->result();
        $row = $rows[0];
        
        //load the row into the poll object
        $answer->load($row);
        
        return $answer;
    }
    
    /** Return an array of all the answers for the poll with the specified id
     * from the database
     * @param $pollId The id of the poll to get answers for
     * @return an array of answers to the poll ordered by their optionNo
    */
    public function getAnswers($pollId) {
        //Escape the pollId
        $pollId = (int)$pollId;

        $this->db->order_by('optionNo');
        $rows = $this->db->get_where('ANSWERS', array('pollId'=>$pollId))->result();
        $list = array();
        foreach ($rows as $row) {
            $answer = new Answer();
            $answer->load($row);
            $list[] = $answer;
        }
        return $list;
    }

    /**
     * Votes for an answer
     * @param $answerId The answer to vote for
     */
    public function vote($answerId) {
        //Escape the answerId
        $answerId = (int)$answerId;

        $this->db->set('votes', 'votes + 1', FALSE);
        $this->db->where("id", $answerId);
        $this->db->update("ANSWERS");
    }

    /**
     * Clears all votes on a poll
     * @param $pollId The id of the poll to clear votes on
     */
    public function clearVotes($pollId){
        //Escape the pollId
        $pollId = (int)$pollId;

        $this->db->where("pollId", $pollId);
        $this->db->update("ANSWERS", array("votes" => 0));
    }

    /**
     * Deletes all the answers for the specific poll
     * @param $pollId The id of the poll to delete answers for
     */
    public function deleteAll($pollId){
        $this->db->delete("ANSWERS", array("pollId"=>$pollId));
    }
    
    /**
     * Loads a row from the database into a answer object
     * @param type $row The row representing the Answer
     */
    private function load($row) {
        foreach ((array) $row as $field => $value) {
            $fieldName = strtolower($field[0]) . substr($field, 1);
            $this->$fieldName = $value;
        }
    }
}
