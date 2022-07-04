<?php

namespace Digitalis\Module\Dev_Monitor;

use Digitalis\Module\Module;
use Digitalis\Admin\Option\Field;
use Digitalis\Admin\Option\Tab;
use Digitalis\Admin\User\User_Meta;

class Dev_Monitor extends Module {
	
	private $default_timeout = 10;
	private $default_min_length = 5;
	
	private $work_day_length = 8;
	
	private $user_id = false;
	private $timeout = -1;
	private $min_length = -1;
	
	private $user_data = [];
	
	private $display_options = ["Time", "Day", "Week", "Month", "Year"];
	
	public function run () {
		
		$this->fields('add_options');
		$this->tabs('add_tab');
		add_action( 'init', [$this, 'page_load'] );
		
		if (is_admin()) {
			
			$this->add_user_fields();
			
			add_action( 'init', [$this, 'widget_init'] );
			
		}
		
	}
	
	public function add_user_fields () {
		
		$user_meta = new User_Meta(['administrator'], 'Digitalis');
			
		$user_meta->add_field(new Field("Monitor Productivity", "monitor", "checkbox", "", "user_meta"))
		->add_description("Use Digitalis Productivity monitor to record activity.");
		
	}
	
	public function add_tab ($manager) {
		
		$tab = new Tab("Dev Monitor", "dev_monitor", false);
		$tab
		->set_capability(DIGITALIS_ADMIN_CAP)
		->add_action([$this, "render_module_tab"]);
		
		$manager->add_tab($tab);
		
	}
	
	public function add_options ($manager) {
		
		if (!function_exists('get_editable_roles')) require_once(ABSPATH . '/wp-admin/includes/user.php');
		
		//print_r(get_editable_roles());
		
		$roles = get_editable_roles();
		$role_options = [];
		$role_options[DIGITALIS_ADMIN_CAP] = ucfirst(DIGITALIS_ADMIN_CAP);
		foreach ($roles as $value => $role) {
			$role_options[$value] = $role["name"];
		}
		
		$manager->add_field(new Field("Session Timeout (m)", "monitor_timeout", "text", $this->default_timeout));
		
		$manager->add_field(new Field("Session Min. Length (m)", "monitor_min_length", "text", $this->default_min_length));
		
		$manager->add_field(new Field("Show Widget?", "monitor_widget", "checkbox", DIGITALIS_VALUE_TRUE));
		
		$manager->add_field(new Field(
			"Productivity Visible for",
			"monitor_role",
			"select",
			"administrator"
		))->get_current_field()->add_options($role_options);
		
		$manager->add_field(new Field(
			"Timesheet Mode",
			"monitor_widget_mode",
			"select",
			"day"
		))->get_current_field()->add_options($this->display_options, true);
		
		$manager->add_field(new Field("Merge Developer Timesheets?", "monitor_merge_users", "checkbox", DIGITALIS_VALUE_FALSE));
		
	}
	
	public function page_load () {
		
		//$user = wp_get_current_user();
		//print_r($user->allcaps);
		
		//echo (current_user_can("digitalis") ? "yes" : "no");
		//error_log(date("Y-m-d h:i:sa") . " LOADED: " . "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . "   -   ". $base);
		
		
		
		//$base = strtok(basename($_SERVER['REQUEST_URI']), "?");
		//if (($base == "admin-ajax.php") || ($base == "wp-cron.php")) return false;
		
		if (!is_user_logged_in()) 	return false;
		if (wp_doing_ajax()) 		return false;
		if (wp_doing_cron()) 		return false;
		
		$this->user_id = get_current_user_id();
		
		if (!$this->get_user_meta($this->user_id , "monitor", true)) return false;
		
		$activity = $this->get_user_meta($this->user_id , "activity", true);
		if (!$activity) $activity = [];
		
		$num = count($activity);
		$index = $num - 1;
		$now = time();
		
		if ($num > 0 && is_array($activity[$index]) && isset($activity[$index]['end'])) {
			
			if ($this->is_timeout($now, $activity[$index]['end'])) {
				
				if (isset($activity[$index]['begin'])) {
					$seconds = $activity[$index]['end'] - $activity[$index]['begin'];
				} else {
					$seconds = 0;
				}
				
				if ($this->is_neglible($seconds)) {
					array_pop($activity);
				}
				
				$activity[] = [
					'begin'	=> $now,
					'end'	=> $now,
				];
				
			} else {
				$activity[$index]['end'] = $now;
			}
			
		} else {
			
			$activity[] = [
				'begin'	=> $now,
				'end'	=> $now,
			];
			
		}
		
		$this->update_user_meta($this->user_id, "activity", $activity);
		
		//print_r($activity);
		
		
	}
	
	public function widget_init () {
		
		if ($this->get_option("monitor_widget")) {
			if ($this->user_can_view()) {		
				require_once plugin_dir_path( __FILE__ ) . "widget.class.php";
				new Widget($this);
			}
		}
		
	}
	
	public function get_data ($time_mode = "day", $merge_users = false) {

		$users = get_users();
		
		$data = [];
		$this->user_data = [];
		
		foreach ($users as $user) {
			
			if ($merge_users) {
				$this->get_user_data($user, $time_mode, true);
			} else {
				if ($this->get_user_data($user, $time_mode, false)) $data[] = $this->user_data;
			}
			
		}
		
		if ($merge_users && $this->user_data) $data[] = $this->user_data;
		
		return $data;
		
	}
	
	public function get_user_data ($user, $time_mode = "day", $merge_user = false) {

		if (!$this->get_user_meta($user->ID, "monitor")) return false;
			
		if ($merge_user) {
			
			if (!isset($this->user_data["name"])) $this->user_data["name"] = "Developers";
			if (!isset($this->user_data["timesheet"])) $this->user_data["timesheet"] = [];
			if (!isset($this->user_data["time_mode"])) $this->user_data["time_mode"] = $time_mode;
			if (!isset($this->user_data["total"])) $this->user_data["total"] = 0;
			
		} else {
			
			$this->user_data = [];
			$this->user_data["name"] = $user->user_nicename;
			$this->user_data["timesheet"] = [];
			$this->user_data["time_mode"] = $time_mode;
			$this->user_data["total"] = 0;
			
		}

		$timesheet = $this->get_user_meta($user->ID, "activity", true);
		
		/* $timesheet = [
			["begin" => 1579873248, "end" => 1579890349],
			["begin" => 1580214343, "end" => 1580235345],
			["begin" => 1580332140, "end" => 1580333140],
			["begin" => 1580532140, "end" => 1580537655],
		]; 
		 */
		
		if (!$timesheet) return false;
		
		foreach ($timesheet as $range) {
			
			if (isset($range['begin']) && isset($range['end'])) {

				$block = $this->get_block_stamp($range['begin'], $this->user_data["time_mode"]);
				$seconds = $range['end'] - $range['begin'];
				
				if (!$this->is_neglible($seconds)) {

					$this->user_data["total"] += $seconds;
				
					if (array_key_exists($block, $this->user_data["timesheet"])) {
						$this->user_data["timesheet"][$block] += $seconds;
					} else {
						$this->user_data["timesheet"][$block] = $seconds;
					}
				}
			}
		}
		
		if (!count($this->user_data["timesheet"])) return false;
		
		ksort($this->user_data["timesheet"]);

		return $this->user_data;
		
	}

	public function get_block_stamp ($time, $time_mode = "day") {

		if ($time_mode == "week") $time = $time - date('w', $time) * 24 * 60 * 60;

		return $this->date_to_stamp($time, $this->get_block_format($time_mode));

	}

	public function get_block_label ($timestamp, $time_mode = "day") {

		$datetime = new \DateTime();
		return $datetime->setTimestamp($timestamp)->format($this->get_block_format($time_mode));

	}

	public function get_block_format ($time_mode) {

		switch ($time_mode) {

			case "year":
				return "Y";
			
			case "month":
				return "F Y";
			
			case "week":
				return "jS M Y";
				
			case "time":
				return "Y-m-d H:i:s";
				
			case "day":
			default:
				return "jS M Y";
			
		}		

	}

	public function date_to_stamp ($time, $date_format = "jS M Y") {

		return \DateTime::createFromFormat($date_format, date($date_format, $time))->format("U");

	}
	
	public function draw_table ($data, $unit = false) {

		if (!$data) return;
		
		wp_enqueue_style('digitalis_productivity_widget', plugin_dir_url( __FILE__ ) . 'css/widget_style.css', [], DIGITALIS_VERSION);
		
		foreach ($data as $user_data) {
			
			if (count($user_data["timesheet"]) == 0) continue;
			
			echo "<h1>&middot; {$user_data["name"]} &middot;</h1>";
			
			echo "<div class='frame'>";
			echo "<table class='digitalis-productivity'><tbody>";
			echo "<tr>";
				echo "<th>" . ucfirst($user_data["time_mode"]) . ":</th>";
				echo "<th>" . ($unit ? ucfirst($unit) : "Days:") . ":</th>";
			echo "</tr>";
			
			foreach ($user_data["timesheet"] as $block => $seconds) {
				
				$block_label = $this->get_block_label($block, $user_data["time_mode"]);

				$duration = $this->seconds_to_duration($seconds, $unit);
				
				if ($duration) {
					echo "<tr>";
						echo "<td>$block_label</td>";
						echo "<td>$duration</td>";
					echo "</tr>";	
				}
			}
			
			echo "<tr class='total'>";
				echo "<td>Total:</td>";
				echo "<td>" . $this->seconds_to_duration($user_data["total"], $unit) . "<span>*</span></td>";
			echo "</tr>";	
			
			echo "</tbody></table>";
			
			echo "<div class='notes'>* <b>Not</b> inclusive of administrative, planning and graphic design hours.</div>";
			
			echo "</div>";
			
		}
		
	}
	
	public function render_module_tab () {
		
		$buttons = [];
		$query = [];
		$query["page"] = (isset($_GET["page"]) ? $_GET["page"] : "digitalis");
		if (isset($_GET["tab"])) $query["tab"] = $_GET["tab"];
		if (isset($_GET["merge_users"])) $query["merge_users"] = $_GET["merge_users"];
		
		foreach ($this->display_options as $display_option) {
			
			$buttons[$display_option] = add_query_arg("display_option",  sanitize_title_with_dashes($display_option));
			
		}
		
		$query["display_option"] = (isset($_GET["display_option"]) ? $_GET["display_option"] : "");
		
		if (!isset($_GET["merge_users"]) || !$_GET["merge_users"]) {
			$value = 1;
			$label = "Merge Users";
		} else {
			$value = 0;
			$label = "Individual Users";
		}

		$buttons[$label] = add_query_arg("merge_users", $value);

		if (!isset($_GET["unit"]) || ($_GET["unit"] != 'hours')) {
			$value = 'hours';
			$label = "Time in Hours";
		} else {
			$value = 'days';
			$label = "Time in Days";
		}

		$buttons[$label] = add_query_arg("unit", $value);
		
		$this->button_ribbon($buttons);
		
		echo "<div id='digitalis_productivity'>";
		$this->draw_table(
			$this->get_data(
				(isset($_GET["display_option"]) ? $_GET["display_option"] : "day"),
				(isset($_GET["merge_users"]) ? $_GET["merge_users"] : false)
			),
			(isset($_GET["unit"]) ? $_GET["unit"] : false)
		);
		echo "</div>";
		
	}
	
	public function button_ribbon ($buttons) {
		
		echo "<div class='digitalis-ribbon'>";
		
		foreach ($buttons as $label => $url) {
			echo "<a class='digitalis-button' href='$url'>$label</a>";
		}
		
		echo "</div>";
		
	}
	
	public function user_can_view () {
		
		return current_user_can($this->get_option('monitor_role'));
		
	}
	
	public function is_timeout ($now, $time) {
	
		if ($this->timeout == -1) $this->timeout = $this->minutes_to_seconds($this->get_option("monitor_timeout"));
		return ($now - $time > $this->timeout);
		
	}
	
	public function is_neglible ($seconds) {
		
		if ($this->min_length == -1) $this->min_length = $this->minutes_to_seconds($this->get_option("monitor_min_length"));
		return $seconds < $this->min_length;
		
	}
	
	public function minutes_to_seconds ($minutes) {
		
		return $minutes * 60;
		
	}
	
	public function seconds_to_duration ($seconds, $mode = false) {
		
		switch ($mode) {

			case 'hours':

				return round($seconds / (60 * 60), 2);

			case 'days':
			default:

				$total_minutes = $seconds / 60;
				$total_hours = $total_minutes / 60;
				$total_days = $total_hours / $this->work_day_length;
				
				$days = floor($total_days);
				$hours = floor($total_hours - (floor($total_days) * $this->work_day_length));
				$minutes = floor($total_minutes - (floor($total_hours) * 60));
				
				//$minutes = date("i", $seconds);
				//$hours = date("H", $seconds);
				//$days = date("z", $seconds);
				
				$duration = "";
				if ($days) $duration .= $days . "d ";
				if ($hours) $duration .= sprintf('%02d', $hours) . "h ";
				$duration .= sprintf('%02d', $minutes) . "m";
				
				return $duration;

		}


		
	}

	
}

new Dev_Monitor();