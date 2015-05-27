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
class Vote extends CI_Model {
    public $id;
    
    public $answerId;
    public $ip;
    
    public function __construct() {
        $this->load->database();
    }
    
    /** Return an array of all the votes for the answer with the specified id
     * from the database
     * @param $answerId The id of the answer to get answers for
     * @return an array of votes for the answer
    */
    public function getVotes($answerId) {
        $rows = $this->db->get_where('VOTES', array('answerId'=>$answerId))->result();
        $list = array();
        foreach ($rows as $row) {
            $vote = new Vote();
            $vote->load($row);
            $list[] = $vote;
        }
        return $list;
    }

    /**
     * Gets a list of all the votes on a specific poll
     * @param $pollId The id of the poll
     * @return array The votes from the specified poll
     */
    public function getAllVotes($pollId) {
        $this->db->select("votes.id, votes.answerId, votes.ip");
        $this->db->from("votes");
        $this->db->join("answers", "votes.answerId = answers.id and answers.pollId = $pollId");
        $rows = $this->db->get()->result();

        $list = array();
        foreach ($rows as $row){
            $vote = new Vote();
            $vote->load($row);
            $list[] = $vote;
        }

        return $list;
    }
    
    /**
     * Votes for a specific answer
     * @param type $answer
     * @param type $ip
     */
    public function vote($answerId, $ip) {
        //$query = $this->db->query("INSERT INTO VOTES (answerId, ip) VALUES ($answer->id, $ip);");//.$this->db->escape($answer->id).", ".$this->db-escape($ip).")");
        $data = array('answerId'=>$answerId, 'ip'=>$ip);
        $this->db->insert('VOTES', $data);
    }
    
    /**
     * Loads a row from the database into a Vote object
     * @param type $row The row representing the Vote
     */
    private function load($row) {
        foreach ((array) $row as $field => $value) {
            $fieldName = strtolower($field[0]) . substr($field, 1);
            $this->$fieldName = $value;
        }
    }
}
