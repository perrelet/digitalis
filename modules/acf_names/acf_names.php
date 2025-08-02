<?php

namespace Digitalis\Module\ACF_Names;

class ACF_Names {
	
	function __construct () {
		
		if (class_exists('acf') && is_admin()) add_filter('acf/get_field_label', [$this, 'get_field_label'], 10, 3);
		
	}
	
	public function get_field_label ($label, $field, $context) {
		
		if (current_user_can(DIGITALIS_ADMIN_CAP)) {

			if (array_key_exists("wpml_cf_preferences", $field)) {
				
				$wpml_choices = array(
					WPML_IGNORE_CUSTOM_FIELD	=> __("Don't translate",'acfml'),
					WPML_COPY_CUSTOM_FIELD		=> __("Copy",'acfml'),
					WPML_COPY_ONCE_CUSTOM_FIELD => __("Copy once", 'acfml'),
					WPML_TRANSLATE_CUSTOM_FIELD => __("Translate", "acfml")
				);

				$name = $wpml_choices[$field['wpml_cf_preferences']];
				
			} else {

				$name = $field["_name"];

			}

			$label = "<div class='digit-acf-label'>{$label}</div><div class='digit-acf-name'><span>name: </span><span>{$name}</span></div>";

			static $index;
			if (!$index) {
				$index = 0;
				?>
				<script>
					document.addEventListener('DOMContentLoaded', function () {
						document.querySelectorAll('.digit-acf-name > span:last-child').forEach(function (el) {
							el.addEventListener('click', () => {
								navigator.clipboard.writeText(el.innerText);
								el.parentElement.classList.add(`data-copied`);
								setTimeout(() => el.parentElement.classList.remove(`data-copied`), 1000);
							});
						});
					});
				</script>
				<style>
					@keyframes digit-acf-name {
						0%   { opacity: 0.25; }
						100% { opacity: 1; }
					}
					.acf-label.acf-label > label {
						display: flex;
						flex-direction: row;
						justify-content: space-between;
					}
					.digit-acf-name {
						> span {
							opacity: 0.25;
						}
						> span:last-child {
							cursor: pointer;
							display: inline-block;
							&:hover {
								opacity: 1;
							}
							&.data-copied,
							&:active {
								
								opacity: 1;
							}
						}
						&.data-copied {
							
							> span:last-child {
								opacity: 1;
								color: #0ac100;
								animation: digit-acf-name 0.75s ease-in-out infinite;
								
							}
						}
					}
				</style>
				<?php
			}
			
			$index++;
			
			//$field['label'] .= $html;
			
		}

		return $label;
	}
	
}

new ACF_Names();