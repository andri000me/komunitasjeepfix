<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CMS Sekolahku | CMS (Content Management System) dan PPDB/PMB Online GRATIS
 * untuk sekolah SD/Sederajat, SMP/Sederajat, SMA/Sederajat, dan Perguruan Tinggi
 * @version    2.4.2
 * @author     Anton Sofyan | https://facebook.com/antonsofyan | 4ntonsofyan@gmail.com | 0857 5988 8922
 * @copyright  (c) 2014-2019
 * @link       https://sekolahku.web.id
 *
* PERINGATAN :
 * 1. TIDAK DIPERKENANKAN MENGGUNAKAN CMS INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 2. TIDAK DIPERKENANKAN MEMPERJUALBELIKAN APLIKASI INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 3. TIDAK DIPERKENANKAN MENGHAPUS KODE SUMBER APLIKASI.
 */

class M_employees extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'employees';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get Data
	 * @param String $keyword
	 * @param String $return_type
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_where($keyword = '', $return_type = 'count', $limit = 0, $offset = 0) {
		$this->db->select("
			x1.id
			, x1.nik
			, x1.full_name
			, x2.option_name AS employment_type
			, IF(x1.gender = 'M', 'L', 'P') AS gender
			, COALESCE(x1.birth_place, '') birth_place
			, x1.birth_date
			, x1.photo, x1.is_deleted
		");
		$this->db->join('options x2', 'x1.employment_type_id = x2.id', 'LEFT');
		if ( ! empty($keyword) ) {
			$this->db->like('x1.nik', $keyword);
			$this->db->or_like('x1.full_name', $keyword);
			$this->db->or_like('x1.gender', $keyword);
			$this->db->or_like('x1.birth_place', $keyword);
			$this->db->or_like('x1.birth_date', $keyword);
			$this->db->or_like('x2.option_name', $keyword);
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table . ' x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Dropdown
	 * @return Array
	 */
	public function dropdown() {
		$query = $this->db
			->select('id, nik, full_name')
			->where('is_deleted', 'false')
			->order_by('full_name', 'ASC')
			->get(self::$table);
		$data = [];
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[$row->id] = $row->nik .' - '. $row->full_name;
			}
		}
		return $data;
	}

	/**
	 * Get Employment Type
	 * @param Integer $id
	 * @return String
	 */
	public function get_employment_type($id) {
		$query = $this->model->RowObject(self::$pk, $id, self::$table);
		if (is_object($query)) {
			$employment_type = $this->model->RowObject('id', $query->employment_type_id, 'options');
			if (is_object($employment_type)) {
				return $employment_type->option_name;
			}
			return NULL;
		}
		return NULL;
	}

	/**
	 * Get Inactive Accounts
	 * @return Resource
	 */
	public function get_inactive_accounts() {
		$this->db->select('x1.id, x1.nik, x1.full_name, x1.email');
		$this->db->join('users x2', 'x1.id = x2.user_profile_id AND x2.user_type = "employee"', 'LEFT');
		$this->db->where('x2.user_profile_id', NULL);
		$this->db->where('x1.is_deleted', 'false');
		return $this->db->get('employees x1');
	}

	/**
	 * Profile
	 * @param Integer $id
	 * @return Resource
	 */
	public function profile($id) {
		$this->db->select('
			x1.id
			, x1.assignment_letter_number
			, x1.assignment_letter_date
			, x1.assignment_start_date
			, x1.parent_school_status
			, x1.full_name
			, x1.gender
			, x1.nik
			, x1.birth_place
			, x1.birth_date
			, x1.mother_name
			, x1.street_address
			, x1.rt
			, x1.rw
			, x1.sub_village
			, x1.village
			, x1.sub_district
			, x1.district
			, x1.postal_code
			, x2.option_name AS religion
			, x3.option_name AS marriage_status
			, x1.spouse_name
			, x4.option_name AS spouse_employment
			, x1.citizenship
			, x1.country
			, x1.npwp
			, x5.option_name AS employment_status
			, x1.nip
			, x1.niy
			, x1.nuptk
			, x6.option_name AS employment_type
			, x1.decree_appointment
			, x1.appointment_start_date
			, x7.option_name AS institutions_lifter
			, x1.decree_cpns
			, x1.pns_start_date
			, x11.option_name AS rank
			, x8.option_name AS salary_sources
			, x1.headmaster_license
			, x9.option_name AS laboratory_skills
			, x10.option_name AS special_needs
			, x1.braille_skills
			, x1.sign_language_skills
			, x1.phone
			, x1.mobile_phone
			, x1.email
			, x1.photo
		');
		$this->db->join('options x2', 'x1.religion_id = x2.id', 'LEFT');
		$this->db->join('options x3', 'x1.marriage_status_id = x3.id', 'LEFT');
		$this->db->join('options x4', 'x1.spouse_employment_id = x4.id', 'LEFT');
		$this->db->join('options x5', 'x1.employment_status_id = x5.id', 'LEFT');
		$this->db->join('options x6', 'x1.employment_type_id = x6.id', 'LEFT');
		$this->db->join('options x7', 'x1.institution_lifter_id = x7.id', 'LEFT');
		$this->db->join('options x8', 'x1.salary_source_id = x8.id', 'LEFT');
		$this->db->join('options x9', 'x1.laboratory_skill_id = x9.id', 'LEFT');
		$this->db->join('options x10', 'x1.special_need_id = x10.id', 'LEFT');
		$this->db->join('options x11', 'x1.rank_id = x11.id', 'LEFT');
		$this->db->where('x1.id', _toInteger($id));
		return $this->db->get('employees x1')->row();
	}

	/**
	 * Get Employees
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_employees($limit = 0, $offset = 0) {
		$this->db->select("
			x1.id
		  , x1.nik
		  , x1.full_name
		  , IF(x1.gender = 'M', 'Laki-laki', 'Perempuan') as gender
		  , x1.birth_place
		  , x1.birth_date
		  , x1.photo
		  , x2.option_name AS employment_type
		");
		$this->db->join('options x2', 'x1.employment_type_id = x2.id', 'LEFT');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->order_by('x1.full_name', 'ASC');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Check if email exists
	 * @param String $email
	 * @param Integer $id
	 * @return Boolean
	 */
	public function email_exists( $email, $id = 0 ) {
		$this->db->where('email', $email);
		if ( _isNaturalNumber($id) ) $this->db->where('id <>', _toInteger($id));
		$count = $this->db->count_all_results(self::$table);
		return $count > 0;
	}
}
