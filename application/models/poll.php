<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of poll
 *
 * @author joh19
 */
class Poll extends CI_Model {
    public $id;
    
    public $title;
    public $question;
    
    public $answers;
    
    public function __construct() {
        $this->load->database();
        $this->load->model('answer');
    }

    /**
     * Inserts a new poll into the database
     * @param $title The title of the poll
     * @param $question The question the poll is asking
     * @return mixed The index the poll was inserted at
     */
    public function create($title, $question){
        $this->db->insert("POLLS", array("title"=>$title,"question"=>$question));
        return $this->db->insert_id();
    }
    
    /** Return an array of all polls in the database.
     * @return an array of Poll objects containing all polls, ordered
     * by title.
     */
    public function getPolls() {
        $this->db->order_by('Title');
        $rows = $this->db->get('POLLS')->result();
        $list = array();
        foreach ($rows as $row) {
            $poll = new Poll();
            $poll->load($row);
            $list[] = $poll;
        }
        return $list;
    }

    /**
     * Gets a poll from the database via an Id
     * @param type $pollId The id of the poll to fetch
     * @return Poll The poll
     */
    public function getPoll($pollId){
        $poll = new Poll();
        $query = $this->db->get_where('POLLS', array('id'=>$pollId));
        if ($query->num_rows !== 1) {
            throw new Exception("Poll Id $pollId not found in database");
        }

        $rows = $query->result();
        $row = $rows[0];
        
        //load the row into the poll object
        $poll->load($row);
        
        return $poll;
    }

    /**
     * Deletes the specified poll from the data base
     * @param $pollId The id of the poll to delete
     */
    public function deleteRecursive($pollId){
        $this->load->model('answer');

        $this->db->delete("POLLS", array("id"=>$pollId));
        $this->db->delete("ANSWERS", array("pollId"=>$pollId));
    }
    
    /**
     * Loads a row from the database into a poll object
     * @param type $row The row representing the Poll
     */
    private function load($row) {
        foreach ((array) $row as $field => $value) {
            $fieldName = strtolower($field[0]) . substr($field, 1);
            $this->$fieldName = $value;
        }
        
        $this->answers = $this->answer->getAnswers($this->id);
    }
}
