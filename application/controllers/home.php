<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		//get shapefiles and datasets
		$this->db->select('*');
		$this->db->from('counties');
		$this->db->join('voters', 'counties.OBJECTID=voters.countyid');
		$this->db->join('poverty', 'counties.OBJECTID=poverty.countyid', 'left');
		$countydata = $this->db->get();
		$data['countydata'] = $countydata->result_array();
		
		//show cord areas
		$this->db->select('*');
		$this->db->from('winners_county');
		$this->db->join('counties', 'counties.OBJECTID=winners_county.countyid', 'inner');
		$this->db->join('poverty', 'poverty.countyid=winners_county.countyid', 'inner');
		$this->db->join('voters', 'voters.countyid=winners_county.countyid', 'inner');
		$this->db->where('winners_county.candidate', '8');
		$cordareas = $this->db->get();
		$data['cordarea'] = $cordareas->result_array();
		
		//show jubilee strongholds
		$this->db->select('*');
		$this->db->from('winners_county');
		$this->db->join('counties', 'counties.OBJECTID=winners_county.countyid', 'inner');
		$this->db->join('poverty', 'poverty.countyid=winners_county.countyid', 'inner');
		$this->db->join('voters', 'voters.countyid=winners_county.countyid', 'inner');
		$this->db->where('winners_county.candidate', '2');
		$jubileeareas = $this->db->get();
		$data['jubileearea'] = $jubileeareas->result_array();		
		
		
		//show presidential aspirants before county selected
		$this->db->select('presidential_candidates.surname, 
                   presidential_candidates.other_name, 
                   presidential_candidates.running_mate,   
                   parties.name');
		$this->db->from('presidential_candidates');
		$this->db->join('parties', 'presidential_candidates.party= parties.id');
		$result = $this->db->get();
		
		$data['presidential_aspirants'] = $result->result_array();
		
		$data['title'] = 'Home';
		
		$this->load->view('templates/header',$data);
		$this->load->view('home',$data);
		$this->load->view('templates/footer',$data);
	}
	
	public function process()
		{
			
			$countyid = $_POST['countyid'];
			$edType = $_POST['edType'];
			//show presidential aspirants before county selected
			$this->db->select('presidential_candidates.surname, 
	                   presidential_candidates.other_name, 
	                   presidential_candidates.running_mate,   
	                   parties.name');
			$this->db->from('presidential_candidates');
			$this->db->join('parties', 'presidential_candidates.party= parties.id');
			$result = $this->db->get();
	
			$data['presidential_aspirants'] = $result->result_array();
			
			if($edType=='1'){
			//check electoral district
			
			//find county information
			$this->db->where('countyid',$countyid);
			$result = $this->db->get('county');
			$result = $result->result_array();
			$result = $result[0];
			$countyinfo['registered_voters'] = $result['registered'];
			$countyinfo['county_name'] = $result['name'];
			$data['countyinfo'] = $countyinfo;
			
			//find gurbernatorial aspirants
			$this->db->select('gurbernatorial_candidates.surname, 
	                   gurbernatorial_candidates.other_name, 
	                   gurbernatorial_candidates.running_mate,   
	                   parties.name');
			$this->db->from('gurbernatorial_candidates');
			$this->db->where('countyid',$countyid);
			$this->db->join('parties', 'gurbernatorial_candidates.party= parties.id');
			$result = $this->db->get();
	
			$data['gurbernatorial_aspirants'] = $result->result_array();
			
			//find senatorial aspirants
			$this->db->select('senatorial_candidates.surname, 
	                   senatorial_candidates.other_name, 
	                   senatorial_candidates.running_mate,   
	                   parties.name');
			$this->db->from('senatorial_candidates');
			$this->db->where('countyid',$countyid);
			$this->db->join('parties', 'senatorial_candidates.party= parties.id');
			$result = $this->db->get();
	
			$data['senatorial_aspirants'] = $result->result_array();
			
			//find womenrep aspirants
			$this->db->select('womenrep_candidates.surname, 
	                   womenrep_candidates.other_name, 
	                   womenrep_candidates.running_mate,   
	                   parties.name');
			$this->db->from('womenrep_candidates');
			$this->db->where('countyid',$countyid);
			$this->db->join('parties', 'womenrep_candidates.party= parties.id');
			$result = $this->db->get();
	
			$data['womenrep_aspirants'] = $result->result_array();
			$this->load->view('aspirants', $data);
			}
			else{
			//find constituency information
			$this->db->where('constid',$countyid);
			$result = $this->db->get('constituency');
			$result = $result->result_array();
			$result = $result[0];
			$countyinfo['registered_voters'] = $result['registered'];
			$countyinfo['county_name'] = $result['name'];
			$data['countyinfo'] = $countyinfo;
			
			//find national assembly aspirants
			$this->db->select('nationalassembly_candidates.surname, 
	                   nationalassembly_candidates.other_name, 
	                   nationalassembly_candidates.running_mate,   
	                   parties.name');
			$this->db->from('nationalassembly_candidates');
			$this->db->where('constituency',$countyid);
			$this->db->join('parties', 'nationalassembly_candidates.party= parties.id');
			$result = $this->db->get();
	
			$data['nationalassembly_aspirants'] = $result->result_array();
			$this->load->view('natassembly', $data);
			}
			
			
		}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */