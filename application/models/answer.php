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
        $this->load->model('vote');
    }
    
    /**
     * Gets an option from within a poll
     * @param type $pollId The id of the poll
     * @param type $optionNo The number of the option within the poll
     */
    public function getAnswer($pollId, $optionNo){
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
     * Loads a row from the database into a answer object
     * @param type $row The row representing the Answer
     */
    private function load($row) {
        foreach ((array) $row as $field => $value) {
            $fieldName = strtolower($field[0]) . substr($field, 1);
            $this->$fieldName = $value;
        }
        
        $this->votes = $this->vote->getVotes($this->id);
    }
}
