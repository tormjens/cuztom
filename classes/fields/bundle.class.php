<?php

class Cuztom_Bundle
{
	var $id;
	var $fields = array();

	/**
	 * Outputs a bundle
	 * 
	 * @param  	object 			$post
	 * @param   string 			$context
	 *
	 * @author  Gijs Jorissen
	 * @since   1.6.5
	 *
	 */
	function output( $post, $context )
	{
		echo '<div class="padding-wrap">';
			echo '<a class="button-secondary cuztom-button js-cuztom-add-sortable js-cuztom-add-bundle cuztom-add-sortable" href="#">';
				echo sprintf( '+ %s', __( 'Add', CUZTOM_TEXTDOMAIN ) );
			echo '</a>';

			echo '<ul class="js-cuztom-sortable cuztom-sortable js-cuztom-sortable-bundle">';
				
				$meta = $context == 'user' ? get_user_meta( $post->ID, $this->id, true ) : get_post_meta( $post->ID, $this->id, true );

				if( ! empty( $meta ) && isset( $meta[0] ) )
				{
					$i = 0;
					foreach( $meta as $bundle )
					{
						echo '<li class="cuztom-sortable-item">';
							echo '<div class="cuztom-handle-sortable cuztom-handle-bundle"></div>';
							echo '<fieldset>';
							echo '<table border="0" cellading="0" cellspacing="0" class="form-table cuztom-table">';
								
								foreach( $this->fields as $id_name => $field )
								{
									$field->pre = '[' . $this->id . '][' . $i . ']';
									$value = isset( $meta[$i][$id_name] ) ? $meta[$i][$id_name] : '';
									
									if( ! $field instanceof Cuztom_Field_Hidden )
									{
										echo '<tr>';
											echo '<th class="cuztom_th th">';
												echo '<label for="' . $id_name . '" class="cuztom_label">' . $field->label . '</label>';
												echo '<div class="cuztom-description description">' . $field->description . '</div>';
											echo '</th>';
											echo '<td class="cuztom-td cuztom_td td">';

												if( $field->_supports_bundle() )
													echo $field->output( $value );
												else
													_e( '<em>This input type doesn\'t support the bundle functionality (yet).</em>' );

											echo '</td>';
										echo '</tr>';
									}
									else
									{
										echo $field->output( $value );
									}
								}

							echo '</table>';
							echo '</fieldset>';
							echo count( $meta ) > 1 ? '<div class="cuztom-remove-sortable cuztom-remove-bundle"></div>' : '';
						echo '</li>';
						
						$i++;
					}
					
				}
				else
				{
					echo '<li class="cuztom-sortable-item">';
						echo '<div class="handle_bundle"></div>';
						echo '<fieldset>';
						echo '<table border="0" cellading="0" cellspacing="0" class="form-table cuztom-table">';
							
							$fields = $this->fields;
							
							foreach( $fields as $id_name => $field )
							{
								$field->pre = '[' . $this->id . '][0]';
								$value = '';

								if( ! $field instanceof Cuztom_Field_Hidden )
								{
									echo '<tr>';
										echo '<th class="cuztom_th th">';
											echo '<label for="' . $id_name . '" class="cuztom_label">' . $field->label . '</label>';
											echo '<div class="cuztom-description description">' . $field->description . '</div>';
										echo '</th>';
										echo '<td class="cuztom-td cuztom_td td">';

											if( $field->_supports_bundle() )
												echo $field->output( $value );
											else
												_e( '<em>This input type doesn\'t support the bundle functionality (yet).</em>' );

										echo '</td>';
									echo '</tr>';
								}
								else
								{
									echo $field->output( $value );
								}
							}

						echo '</table>';
						echo '</fieldset>';
					echo '</li>';
				}
			echo '</ul>';
		echo '</div>';
	}

	/**
	 * Save bundle meta
	 * 
	 * @param  	int 			$post_id
	 * @param  	string 			$value
	 *
	 * @author 	Gijs Jorissen
	 * @since 	1.6.2
	 * 
	 */
	function save( $id, $value, $context )
	{
		if( $context == 'user' )
			update_user_meta( $id, $this->id, $value );
		else
			update_post_meta( $id, $this->id, $value );
	}
}