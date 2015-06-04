<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Vote
 *
 * @author joh19
 */
class Vote extends CI_Model {
    public $id;
    public $pollId;
    public $answerId;
    public $ip;

    public function __construct() {
        $this->load->database();
    }

    /**
     * Gets all the votes on a poll
     * @param type $pollId The id of the poll
     * @return array An array of all votes on the poll
     */
    public function getVotes($pollId){
        //Escape the pollId
        $pollId = (int)$pollId;

        $query = $this->db->get_where('VOTES', array('pollId'=>$pollId));
        $rows = $query->result();

        $votes = array();
        foreach ($rows as $row){
            $vote = new Vote();
            $vote->load($row);

            $votes[] = $vote;
        }

        return $votes;
    }

    /** Return an array of all the answers for the poll with the specified id
     * from the database
     * @param $answerId The id of the answer
     * @return an array of votes for a specific answer
     */
    public function getVotesFor($answerId) {
        //Escape the pollId
        $answerId = (int)$answerId;

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
     * Votes for an answer
     * @param $pollId The poll to vote on
     * @param $answerId The answer to vote for
     * @param $ip The ip that the vote came from
     */
    public function vote($pollId, $answerId, $ip) {
        //Escape the poll and answer id
        $pollId = (int)$pollId;
        $answerId = (int)$answerId;

        $this->db->insert('VOTES', array('pollId'=>$pollId, 'answerId'=>$answerId, 'ip'=>$ip));
    }

    /**
     * Clears all votes on a poll
     * @param $pollId The id of the poll to clear votes on
     */
    public function clearVotes($pollId){
        //Escape the pollId
        $pollId = (int)$pollId;
        $this->db->delete("VOTES", array("pollId" => $pollId));
    }

    /**
     * Loads a row from the database into a vote object
     * @param type $row The row representing the Vote
     */
    private function load($row) {
        foreach ((array) $row as $field => $value) {
            $fieldName = strtolower($field[0]) . substr($field, 1);
            $this->$fieldName = $value;
        }
    }
}
