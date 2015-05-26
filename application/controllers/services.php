<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// The controller to handle AJAX requests.
// Responses are JSON data rather than "views" in a normal sense, so we
// just emit the data from within the controller.
class Services extends CI_Controller {
    
    /**
     * Gets the list of all polls (if no ID is specified) or a specific poll
     * specified by the 'pollId' variable
    **/
    public function polls($pollId=NULL)
    {       
        $this->output->set_content_type('application/json');
        $this->load->model('poll');
        $this->load->model('answer');
        $this->load->model('vote');
        
        try {
            $data = "";

            if (isset($pollId)){
                $data = $this->poll->getPoll($pollId);
            } else{
                $data = $this->poll->getPolls();
            }

            //TODO set the callback properly
            $data = "angular.callbacks._0(".json_encode($data).")";
            $this->output->set_output($data);

        } catch (Exception $e) {
            $this->output->set_status_header(404, 'Unknown product ID');
        } 
    }
    
    /**
     * Votes for an option in a poll
     * @param type $pollId The poll to vote on
     * @param type $optionNo The option to vote for
     */
    private function vote($pollId, $optionNo) {          
        try {            
            $answer = $this->answer->getAnswer($pollId, $optionNo);
            $this->vote->vote($answer, "127.0.0.1");
        } catch (Exception $e) {
            $this->output->set_status_header(404, 'Unknown vote or option ID');
        } 
    }
    
    /**
     * Gets a list of votes for a poll (if only a pollId is specified) or
     * votes for the 'vote' option on the specified poll
     * @param type $pollId The id of the poll
     * @param type $vote The id of the vote to vote for
     */
    public function votes($pollId, $optionNo=NULL){ 
        $this->load->model('poll');
        $this->load->model('answer');
        $this->load->model('vote');
        
        if (isset($optionNo)){ 
            $this->vote($pollId, $optionNo);
        }
    }
}
