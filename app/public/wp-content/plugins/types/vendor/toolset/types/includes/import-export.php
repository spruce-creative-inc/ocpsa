<?php

require_once WPCF_EMBEDDED_INC_ABSPATH . '/import-export.php';

function wpcf_admin_export_form() {
	$form = array();
    $form['types_export_wpnonce'] = array(
        '#type'		=> 'hidden',
        '#name'		=> 'types_export_wpnonce',
        '#value'	=> wp_create_nonce( 'wpcf_export' ),
    );
	$form['types_export'] = array(
		'#type'			=> 'submit',
		'#name'			=> 'types_export',
		'#value'		=> __( 'Export', 'wpcf' ),
		'#attributes'	=> array('class' => 'button-primary'),
		'#before' 		=> '<p>'
						. __( 'Download all custom fields, post types and taxonomies created by Types plugin.', 'wpcf' )
						. '</p>'
						. '<p class="toolset-update-button-wrap">',
		'#after'		=> '</p>'
	);
	return wpcf_form_simple( $form );
}

function wpcf_admin_import_form() {
	$form = array();
	$form['types_import_wpnonce'] = array(
        '#type'		=> 'hidden',
        '#name'		=> 'types_import_wpnonce',
        '#value'	=> wp_create_nonce( 'wpcf_import' ),
    );
    if ( extension_loaded( 'simplexml' ) ) {
		$attributes = !wpcf_admin_import_dir() ? array('disabled' => 'disabled') : array();
		$form['intro'] = array(
			'#type'		=> 'markup',
			'#markup'	=> '<p>'
							. __( 'You can import Types data with the following methods:', 'wpcf' )
							. '</p>'
		);
		$form['types-import-method'] = array(
			'#type'				=> 'radios',
			'#name'				=> 'types-import-method',
			'#options'			=> array(
				'types-import-method-file'	=> array(
												'#title'		=> __( 'XML or ZIP file', 'wpcf' ),
												'#value'		=> 'file',
											),
				'types-import-method-text'	=> array(
												'#title'		=> __( 'XML text input', 'wpcf' ),
												'#value'		=> 'text',
											)
			),
			'#default_value'	=> 'file',
			'#before'			=> '<div class="js-types-import-method">',
			'#after'			=> '</div>'
		);

		$form['types-import-file'] = array(
			'#type'			=> 'file',
			'#name'			=> 'types-import-file',
			'#before'		=> '<div class="toolset-advanced-setting js-types-import-method-extra js-types-import-method-extra-file">'
								. '<p>' . __( 'Upload a .zip or .xml file from your computer:', 'wpcf' ) . '</p>',
			'#after'		=> '</div>',
			'#inline'		=> true,
			'#attributes'	=> $attributes,
		);

		$form['type-import-before-textarea'] = array(
			'#type'		=> 'markup',
			'#markup'	=> '<div class="toolset-advanced-setting js-types-import-method-extra js-types-import-method-extra-text hidden">'
							. '<p>' . __( 'Paste your XML here:', 'wpcf' ) . '</p>',
		);
		$form['types-import-text'] = array(
			'#type'			=> 'textarea',
			'#name' 		=> 'types-import-text',
			'#attributes' 	=> array('rows' => 10, 'style' => 'width:100%;' ),
		);
		$form['types-import-text-encoding'] = array(
			'#type'			=> 'textfield',
			'#name'			=> 'types-import-text-encoding',
			'#value'		=> get_option( 'blog_charset' ),
			'#description'	=> __( 'If encoding is set in text input, it will override this setting.', 'wpcf' ),
			'#label'		=> __( 'Encoding', 'wpcf' ),
		);
		$form['type-import-after-textarea'] = array(
			'#type'		=> 'markup',
			'#markup'	=> '</div>'
		);

		$form['types-import-submit'] = array(
			'#type'			=> 'submit',
			'#name'			=> 'types-import-submit',
			'#value'		=> __( 'Review and import', 'wpcf' ),
			'#attributes'	=> array_merge(
				$attributes,
				array(
					'class'		=> 'button-primary',
				)
			),
			'#before'		=> '<p class="toolset-update-button-wrap">',
			'#after'		=> '</p>',
		);

		$form['types-import-step'] = array(
			'#type' => 'hidden',
			'#name' => 'types-import-step',
			'#value' => 1,
		);
	} else {
		echo '<div class="message error"><p>'
		. __( 'PHP SimpleXML extension not loaded: Importing not available', 'wpcf' )
		. '</p></div>';
	}
	return wpcf_form_simple( $form );
}

function wpcf_admin_import_confirmation_form() {
	$form = array();
	if ( isset( $_POST['types-import-step'] ) ) {
		$mode = 'none';
		$data = '';
		$is_xml = false;
		$uploaded_file = array(
			'file' => '',
		);
		if (
			! empty( $_POST['types-import-submit'] )
			&& (
				! empty( $_POST['types-import-method'] )
				&& $_POST['types-import-method'] == 'file'
			)
			&& ! empty( $_FILES['types-import-file']['tmp_name'] )
		) {
			if ( $_FILES['types-import-file']['type'] == 'text/xml' ) {
				$_FILES['types-import-file']['name'] .= '.txt';
				$is_xml = true;
			}
			/*
			*
			* We need to move uploaded file manually
			*/
			if ( ! empty( $_FILES['types-import-file']['error'] ) ) {
				echo '<div class="message error"><p>'
					. __( 'Error uploading file', 'wpcf' )
					. '</p></div>';
				echo wpcf_admin_import_form();
				return;
			}
			$wp_upload_dir = wp_upload_dir();
			$new_file = $wp_upload_dir['basedir'] . '/' . sanitize_file_name($_FILES['types-import-file']['name']);
			$move = move_uploaded_file( $_FILES['types-import-file']['tmp_name'], $new_file );
			if ( ! $move ) {
				echo '<div class="message error"><p>'
					. __( 'Error moving uploaded file', 'wpcf' )
					. '</p></div>';
				echo wpcf_admin_import_form();
				@unlink($_FILES['types-import-file']['name']);
				return;
			}

			$uploaded_file['file'] = $new_file;
			$info = pathinfo( $uploaded_file['file'] );
			$is_zip = $info['extension'] == 'zip' ? true : false;
			if ( $is_zip ) {
                if ( class_exists( 'ZipArchive' ) ) {
                    $zip = new \ZipArchive();
                    if ($zip->open($uploaded_file['file']) !== false) {
                        for ($index = 0; $index < $zip->numFiles; ++$index) {
                            if ('settings.xml' === $zip->getNameIndex($index)) {
                                $data = $zip->getFromIndex($index);
                            }
                        }
                        $zip->close();
                    } else {
                        echo '<div class="message error"><p>'
                            . __( 'Unable to open zip file', 'wpcf' )
                            . '</p></div>';
                        echo wpcf_admin_import_form();
						@unlink($uploaded_file['file']);
                        return;
                    }
                } else {
                    echo '<div class="message error"><p>'
						. __( 'Unable to open zip file', 'wpcf' )
						. '</p></div>';
					echo wpcf_admin_import_form();
					@unlink($uploaded_file['file']);
					return;
                }
			} else if ( $is_xml ) {
				$data = @file_get_contents( $uploaded_file['file'] );
			} else {
				echo '<div class="message error"><p>'
					. __( 'Data to import not set or not valid', 'wpcf' )
					. '</p></div>';
				echo wpcf_admin_import_form();
				@unlink($uploaded_file['file']);
				return;
			}
			/**
			 * use Transients API to store file fullpath
			 */
			$current_user = wp_get_current_user();
			$cache_key = md5( $current_user->user_email . $uploaded_file['file'] );
			set_transient( $cache_key, $uploaded_file['file'], 60*60 );
			$form['types-import-file'] = array(
				'#type' => 'hidden',
				'#name' => 'types-import-file',
				'#value' => $cache_key,
			);
			$mode = 'file';
		} elseif (
			! empty( $_POST['types-import-submit'] )
			&& (
				! empty( $_POST['types-import-method'] )
				&& $_POST['types-import-method'] == 'text'
			)
			&& ! empty( $_POST['types-import-text'] )
		) {
			$data = stripslashes( $_POST['types-import-text'] );
			if ( preg_match( '/encoding=("[^"]*"|\'[^\']*\')/s', $data, $match ) ) {
				$charset = trim( $match[1], '"' );
			} else {
				$charset = !empty( $_POST['types-import-text-encoding'] ) ? sanitize_text_field( $_POST['types-import-text-encoding'] ) : get_option( 'blog_charset' );
			}
			$form['types-import-text'] = array(
				'#type' => 'hidden',
				'#name' => 'types-import-text',
				'#value' => htmlentities( stripslashes( $_POST['types-import-text'] ), ENT_QUOTES, $charset ),
			);
			$form['types-import-text-encoding'] = array(
				'#type' => 'hidden',
				'#name' => 'types-import-text-encoding',
				'#value' => $charset,
			);
			$mode = 'text';
		}
		if ( empty( $data ) ) {
			echo '<div class="message error"><p>'
				. __( 'Data to import not set or not valid', 'wpcf' )
				. '</p></div>';
			echo wpcf_admin_import_form();
			if ( !empty($uploaded_file['file']) ) {
				@unlink($uploaded_file['file']);
			}
			return;
		} else {
			$data = wpcf_admin_import_export_settings( $data );
			if ( empty( $data ) ) {
				echo '<div class="message error"><p>'
					. __( 'Data not valid', 'wpcf' )
					. '</p></div>';
				echo wpcf_admin_import_form();
				if ( !empty($uploaded_file['file']) ) {
					@unlink($uploaded_file['file']);
				}
				return;
			} else {
				$form = array_merge( $form, $data );
				$form['types-import-mode'] = array(
					'#type' => 'hidden',
					'#name' => 'types-import-mode',
					'#value' => $mode,
				);
				$form['types-import-final'] = array(
					'#type' => 'hidden',
					'#name' => 'types-import-final',
					'#value' => 1,
				);
				$form['types-import-final-submit'] = array(
					'#type' => 'submit',
					'#name' => 'types-import-final-submit',
					'#value' => __( 'Import', 'wpcf' ),
					'#attributes' => array('class' => 'button-primary'),
					'#before'		=> '<p class="toolset-update-button-wrap">',
					'#after'		=> '</p>',
				);
				$form['types_import_wpnonce'] = array(
					'#type'		=> 'hidden',
					'#name'		=> 'types_import_wpnonce',
					'#value'	=> wp_create_nonce( 'wpcf_import' ),
				);
			}
		}
	}
	return wpcf_form_simple( $form );
}

function wpcf_admin_import_final_data() {
	$return = array();
	if (
		isset( $_POST['types-import-final'], $_POST['types_import_wpnonce'] )
		&& extension_loaded( 'simplexml' )
		&& wp_verify_nonce( $_POST['types_import_wpnonce'], 'wpcf_import' )
	) {
		if ( $_POST['types-import-mode'] === 'file' && !empty( $_POST['types-import-file'] ) ) {
			$file = get_transient( sanitize_text_field( $_POST['types-import-file'] ) );
			if ( file_exists($file) ) {
				$info = pathinfo($file);
				$is_zip = ( $info['extension'] === 'zip' );
				$is_xml = ( $info['extension'] === 'xml' || $info['extension'] === 'txt' );
				if ( $is_zip ) {
                    if ( class_exists( 'ZipArchive' ) ) {
                        $zip = new \ZipArchive();
                        if ($zip->open($file) !== false) {
                            for ($index = 0; $index < $zip->numFiles; ++$index) {
                                if ('settings.xml' === $zip->getNameIndex($index)) {
                                    $data = $zip->getFromIndex($index);
                                }
                            }
                            $zip->close();
                        } else {
                            $return[] = array(
                                'type'		=> 'error',
                                'content'	=> __( 'Unable to open zip file', 'wpcf' )
                            );
							@unlink($file);
                            return $return;
                        }
                    } else {
                        $return[] = array(
							'type'		=> 'error',
							'content'	=> __( 'Unable to open zip file', 'wpcf' )
						);
						@unlink($file);
						return $return;
                    }
				} else if ( $is_xml ) {
					$data = @file_get_contents( $file );
				}

				@unlink($file);

				if ( isset( $data ) && $data ) {
					$return = wpcf_admin_import_data( $data, false );
				} else {
					$return[] = array(
						'type'		=> 'error',
						'content'	=> __( 'Unable to process file', 'wpcf' )
					);
					return $return;
				}
			} else {
				$return[] = array(
					'type'		=> 'error',
					'content'	=> __( 'Unable to process file', 'wpcf' )
				);
				return $return;
			}
		}
		if ( $_POST['types-import-mode'] == 'text' && !empty( $_POST['types-import-text'] ) ) {
			$charset = !empty( $_POST['types-import-text-encoding'] ) ? sanitize_text_field( $_POST['types-import-text-encoding'] ) : get_option( 'blog_charset' );
			$return = wpcf_admin_import_data( stripslashes( html_entity_decode( $_POST['types-import-text'], ENT_QUOTES, $charset ) ), false );
		}
	}
	return $return;
}

/**
 * Import/Export form data.
 *
 * @return type
 *
 * @deprecated in 2.0, to remove
 */
function wpcf_admin_import_export_form()
{
    $form = array();
    $form['wpnonce'] = array(
        '#type' => 'hidden',
        '#name' => '_wpnonce',
        '#value' => wp_create_nonce( 'wpcf_import' ),
    );
    $form_base = $form;
    $show_first_screen = true;
    if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'wpcf_import' ) ) {
        $show_first_screen = false;
        if ( isset( $_POST['import-final'] ) ) {
            if ( $_POST['mode'] == 'file' && !empty( $_POST['file'] ) ) {
                $file = get_transient( sanitize_text_field( $_POST['file'] ) );
                if ( file_exists($file) ) {
                    $info = pathinfo($file);
                    $is_zip = $info['extension'] == 'zip' ? true : false;
                    if ( $is_zip ) {
                        if ( class_exists( 'ZipArchive' ) ) {
                            $zip = new \ZipArchive();
                            if ($zip->open($file) !== false) {
                                for ($index = 0; $index < $zip->numFiles; ++$index) {
                                    if ('settings.xml' === $zip->getNameIndex($index)) {
                                        $data = $zip->getFromIndex($index);
                                    }
                                }
                                $zip->close();
                            } else {
                                echo '<div class="message error"><p>'
                                    . __( 'Unable to open zip file', 'wpcf' )
                                    . '</p></div>';
                                return array();
                            }
                        } else {
                            echo '<div class="message error"><p>'
                                . __( 'Unable to open zip file', 'wpcf' )
                                . '</p></div>';
                            return array();
                        }
                    } else {
                        $data = @file_get_contents( $file );
                    }

                    @unlink($file);

                    if ( $data ) {
                        wpcf_admin_import_data( $data );
                    } else {
                        echo '<div class="message error"><p>'
                            . __( 'Unable to process file', 'wpcf' )
                            . '</p></div>';
                        return array();
                    }
                } else {
                    echo '<div class="message error"><p>'
                        . __( 'Unable to process file', 'wpcf' )
                        . '</p></div>';
                    return array();
                }
            }
            if ( $_POST['mode'] == 'text' && !empty( $_POST['text'] ) ) {
                $charset = !empty( $_POST['text-encoding'] ) ? sanitize_text_field( $_POST['text-encoding'] ) : get_option( 'blog_charset' );
                wpcf_admin_import_data( stripslashes( html_entity_decode( $_POST['text'],
                                        ENT_QUOTES, $charset ) ) );
            }
        } elseif ( isset( $_POST['step'] ) ) {
            $mode = 'none';
            $data = '';
            if ( !empty( $_POST['import-file'] ) && !empty( $_FILES['file']['tmp_name'] ) ) {
                if ( $_FILES['file']['type'] == 'text/xml' ) {
                    $_FILES['file']['name'] .= '.txt';
                }
                /*
                 *
                 * We need to move uploaded file manually
                 */
                if ( !empty( $_FILES['file']['error'] ) ) {
                    echo '<div class="message error"><p>'
                    . __( 'Error uploading file', 'wpcf' )
                    . '</p></div>';
                    return array();
                }
                $wp_upload_dir = wp_upload_dir();
                $new_file = $wp_upload_dir['basedir'] . '/' . sanitize_file_name($_FILES['file']['name']);
                $move = move_uploaded_file( $_FILES['file']['tmp_name'], $new_file );
                if ( !$move ) {
                    echo '<div class="message error"><p>'
                    . __( 'Error moving uploaded file', 'wpcf' )
                    . '</p></div>';
                    return array();
                }

                $uploaded_file = array(
                    'file' => $new_file
                );
                $info = pathinfo( $uploaded_file['file'] );
                $is_zip = $info['extension'] == 'zip' ? true : false;
                if ( $is_zip ) {
                    if ( class_exists( 'ZipArchive' ) ) {
                        $zip = new \ZipArchive();
                        if ($zip->open($uploaded_file['file']) !== false) {
                            for ($index = 0; $index < $zip->numFiles; ++$index) {
                                if ('settings.xml' === $zip->getNameIndex($index)) {
                                    $data = $zip->getFromIndex($index);
                                }
                            }
                            $zip->close();
                        } else {
                            echo '<div class="message error"><p>'
                                . __( 'Unable to open zip file', 'wpcf' )
                                . '</p></div>';
                            return array();
                        }
                    } else {
                        echo '<div class="message error"><p>'
                            . __( 'Unable to open zip file', 'wpcf' )
                            . '</p></div>';
                        return array();
                    }
                } else {
                    $data = @file_get_contents( $uploaded_file['file'] );
                }
                /**
                 * use Transients API to store file fullpath
                 */
                $current_user = wp_get_current_user();
                $cache_key = md5($current_user->user_email.$uploaded_file['file']);
                set_transient( $cache_key, $uploaded_file['file'], 60*60 );
                $form['file'] = array(
                    '#type' => 'hidden',
                    '#name' => 'file',
                    '#value' => $cache_key,
                );
                $mode = 'file';
            } elseif ( !empty( $_POST['import-text'] ) && !empty( $_POST['text'] ) ) {
                $data = stripslashes( $_POST['text'] );
                if ( preg_match( '/encoding=("[^"]*"|\'[^\']*\')/s', $data, $match ) ) {
                    $charset = trim( $match[1], '"' );
                } else {
                    $charset = !empty( $_POST['text-encoding'] ) ? sanitize_text_field( $_POST['text-encoding'] ) : get_option( 'blog_charset' );
                }
                $form['text'] = array(
                    '#type' => 'hidden',
                    '#name' => 'text',
                    '#value' => htmlentities( stripslashes( $_POST['text'] ), ENT_QUOTES, $charset ),
                );
                $form['text-encoding'] = array(
                    '#type' => 'hidden',
                    '#name' => 'text-encoding',
                    '#value' => $charset,
                );
                $mode = 'text';
            }
            if ( empty( $data ) ) {
                echo '<div class="message error"><p>'
                . __( 'Data not valid', 'wpcf' )
                . '</p></div>';
                $show_first_screen = true;
            } else {
                $data = wpcf_admin_import_export_settings( $data );
                if ( empty( $data ) ) {
                    echo '<div class="message error"><p>'
                        . __( 'Data not valid', 'wpcf' )
                        . '</p></div>';
                    $show_first_screen = true;
                } else {
                    $form = array_merge( $form, $data );
                    $form['mode'] = array(
                        '#type' => 'hidden',
                        '#name' => 'mode',
                        '#value' => $mode,
                    );
                    $form['import-final'] = array(
                        '#type' => 'hidden',
                        '#name' => 'import-final',
                        '#value' => 1,
                    );
                    $form['submit'] = array(
                        '#type' => 'submit',
                        '#name' => 'import',
                        '#value' => __( 'Import', 'wpcf' ),
                        '#attributes' => array('class' => 'button-primary'),
                    );
                }
            }
        }
    }
    if ( $show_first_screen ) {
        $form = $form_base;
        $form['submit'] = array(
            '#type' => 'submit',
            '#name' => 'export',
            '#value' => __( 'Export', 'wpcf' ),
            '#attributes' => array('class' => 'button-primary'),
            '#after' => '<br /><br />',
            '#before' => '<h3>' . __( 'Export Types data', 'wpcf' ) . '</h3>'
            . __( 'Download all custom fields, post types and taxonomies created by Types plugin.', 'wpcf' ) . '<br /><br />',
        );
        /**
         * check is temp folder available?
         */
        $temp = wpcf_get_temporary_directory();
        if ( empty($temp) ) {
            unset($form['submit']);
        }
        if ( extension_loaded( 'simplexml' ) ) {
            $attributes = !wpcf_admin_import_dir() ? array('disabled' => 'disabled') : array();
            $form['file'] = array(
                '#type' => 'file',
                '#name' => 'file',
                '#prefix' => __( 'Upload XML file', 'wpcf' ) . '<br />',
                '#before' => '<h3>' . __( 'Import Types data file', 'wpcf' ) . '</h3>',
                '#inline' => true,
                '#attributes' => $attributes,
            );
            $form['submit-file'] = array(
                '#type' => 'submit',
                '#name' => 'import-file',
                '#value' => __( 'Import file', 'wpcf' ),
                '#attributes' => array_merge(
                    $attributes,
                    array(
                        'class' => 'button-primary',
                        'disabled' => 'disabled',
                    )
                ),
                '#prefix' => '<br />',
                '#suffix' => '<br /><br />',
            );
            $form['text'] = array(
                '#type' => 'textarea',
                '#title' => __( 'Paste code here', 'wpcf' ),
                '#name' => 'text',
                '#attributes' => array('rows' => 20),
                '#before' => '<h3>' . __( 'Import Types data text input', 'wpcf' ) . '</h3>',
            );
            $form['text-encoding'] = array(
                '#type' => 'textfield',
                '#title' => __( 'Encoding', 'wpcf' ),
                '#name' => 'text-encoding',
                '#value' => get_option( 'blog_charset' ),
                '#description' => __( 'If encoding is set in text input, it will override this setting.', 'wpcf' ),
            );
            $form['submit-text'] = array(
                '#type' => 'submit',
                '#name' => 'import-text',
                '#value' => __( 'Import text', 'wpcf' ),
                '#attributes' => array('class' => 'button-primary'),
            );
            $form['step'] = array(
                '#type' => 'hidden',
                '#name' => 'step',
                '#value' => 1,
            );
        } else {
            echo '<div class="message error"><p>'
            . __( 'PHP SimpleXML extension not loaded: Importing not available', 'wpcf' )
            . '</p></div>';
        }
    }

    return $form;
}

/**
 * File upload error handler.
 *
 * @param type $file
 * @param type $error_msg
 */
function wpcf_admin_import_export_file_upload_error($file, $error_msg)
{
    echo '<div class="message error"><p>' . $error_msg . '</p></div>';
}

/**
 * Import settings.
 *
 * @global object $wpdb
 * @param SimpleXMLElement $data
 * @return string
 */
function wpcf_admin_import_export_settings($data)
{
    global $wpdb;
    $form = array();
    $form['title'] = array(
        '#type' => 'markup',
        '#markup' => '<h2>' . __( 'General settings', 'wpcf' ) . '</h2>',
    );

	$form[ Types_Import_Export::XML_KEY_TOOLSET_COMMON_SETTINGS ] = array(
		'#type' => 'checkbox',
		'#title' => __( 'Overwrite general Toolset settings.', 'wpcf' ),
		'#name' => Types_Import_Export::XML_KEY_TOOLSET_COMMON_SETTINGS,
		'#inline' => true,
		'#after' => sprintf(
			' <i class="fa fa-question-circle icon-question-sign js-show-tooltip" data-header="%s" data-content="%s"></i><br />',
			esc_attr( __( 'Overwrite general Toolset settings.', 'wpcf') ),
			esc_attr( __( 'These settings include values from the General tab in Toolset Settings, like the active version of Bootstrap.', 'wpcf') )
		),
	);

    $form['overwrite-settings'] = array(
        '#type' => 'checkbox',
        '#title' => __( 'Overwrite Types settings.', 'wpcf' ),
        '#name' => 'overwrite-settings',
        '#inline' => true,
        '#after' => '<br />',
    );
	$form['overwrite-or-add-groups'] = array(
        '#type' => 'checkbox',
        '#title' => __( 'Bulk overwrite field groups if they already exist.', 'wpcf' ),
        '#name' => 'overwrite-groups',
        '#inline' => true,
        '#after' => '<br />',
    );
    $form['delete-groups'] = array(
        '#type' => 'checkbox',
        '#title' => __( 'Delete a field group if it doesn\'t exist in the import file.', 'wpcf' ),
        '#name' => 'delete-groups',
        '#inline' => true,
        '#after' => '<br />',
    );
    $form['delete-fields'] = array(
        '#type' => 'checkbox',
        '#title' => __( 'Delete a custom field if doesn\'t exist in the import file.', 'wpcf' ),
        '#name' => 'delete-fields',
        '#inline' => true,
        '#after' => '<br />',
    );
    $form['delete-types'] = array(
        '#type' => 'checkbox',
        '#title' => __( 'Delete a post type if it doesn\'t exist in the import file.', 'wpcf' ),
        '#name' => 'delete-types',
        '#inline' => true,
        '#after' => '<br />',
    );
    $form['delete-tax'] = array(
        '#type' => 'checkbox',
        '#title' => __( 'Delete a taxonomy if it doesn\'t exist in the import file.', 'wpcf' ),
        '#name' => 'delete-tax',
        '#inline' => true,
        '#after' => '<br />',
    );
    libxml_use_internal_errors( true );

    // remove any non UTF-8 characters (see types-596)
    $data = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $data);
    $data = simplexml_load_string( $data );
    if ( !$data ) {
        echo '<div class="message error"><p>' . __( 'Error parsing XML', 'wpcf' ) . '</p></div>';
        foreach ( libxml_get_errors() as $error ) {
            echo '<div class="message error"><p> ' . sprintf( __( 'Error on line %s', 'wpcf' ), $error->line ) . ': '. $error->message . '</p></div>';
        }
        libxml_clear_errors();
        return false;
    }
//    $data = new SimpleXMLElement($data);

	// Collection of all post types which are for RFG
	$rfg_post_types = array();

    // Check groups
    if ( !empty( $data->groups ) ) {
        $form['title-1'] = array(
            '#type' => 'markup',
            '#markup' => '<h2>' . __( 'Groups to be added/updated', 'wpcf' ) . '</h2>',
        );
        $groups_check = array();
        foreach ( $data->groups->group as $group ) {
            $group = (array) $group;

	        // clean dead RFG (beta relict)
	        if ( is_object( $group['post_title'] ) && $group['post_title']->count() === 0 ) {
		        continue;
	        }

            // For RFG
			$is_rfg = isset( $group['meta'] );
	        if( $is_rfg && property_exists( $group['meta'], '_types_repeatable_field_group_post_type' ) ) {
	        	// collect post type
		        $rfg_post_types[] = $group['meta']->_types_repeatable_field_group_post_type;
	        }

	        $title = '<strong>' . esc_html( $group['post_title'] ) . '</strong>';
	        $title .= $is_rfg
		        ? ' <i>(' . __( 'Repeatable Field Group', 'wpcf' ) . ')</i>'
		        : '';

            $form['group-add-' . $group['ID']] = array(
                '#type' => 'checkbox',
                '#name' => 'groups[' . $group['ID'] . '][add]',
                '#default_value' => true,
                '#title' => $title,
                '#inline' => true,
                '#after' => '<br />',
            );
	        $post = $wpdb->get_var(
		        $wpdb->prepare(
			        "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = %s",
			        $group['post_title'],
			        $group['post_type']
		        )
	        );
            if ( !empty( $post ) ) {
            	$options = array( __( 'Update', 'wpcf' ) => 'update' );

            	if( ! $is_rfg ) {
            		// allow create new only for non RFGs
            		$options[ __( 'Create new', 'wpcf' ) ] = 'add';
	            }

                $form['group-add-' . $group['ID']]['#after'] = wpcf_form_simple(
                        array('group-add-update-' . $group['ID'] => array(
                                '#type' => 'radios',
                                '#name' => 'groups[' . $group['ID'] . '][update]',
                                '#inline' => true,
                                '#options' => $options,
                                '#default_value' => 'update',
                                '#before' => '<br />',
                                '#after' => '<br />',
                            )
                        )
                );
            }
            $groups_check[] = $group['post_title'];
        }
        $groups_existing = get_posts( 'post_type=wp-types-group&post_status=null' );
        if ( !empty( $groups_existing ) ) {
            $groups_to_be_deleted = array();
            foreach ( $groups_existing as $post ) {
                if ( !in_array( $post->post_title, $groups_check ) ) {
                    $groups_to_be_deleted['<strong>' . $post->post_title . '</strong>'] = $post->ID;
                }
            }
            if ( !empty( $groups_to_be_deleted ) ) {
                $form['title-groups-deleted'] = array(
                    '#type' => 'markup',
                    '#markup' => '<h2>' . __( 'Groups to be deleted', 'wpcf' ) . '</h2>',
                );
                $form['groups-deleted'] = array(
                    '#type' => 'checkboxes',
                    '#name' => 'groups-to-be-deleted',
                    '#options' => $groups_to_be_deleted,
                );
            }
        }
    }

    // Check fields
    if ( !empty( $data->fields ) ) {
        $form['title-fields'] = array(
            '#type' => 'markup',
            '#markup' => '<h2>' . __( 'Fields to be added/updated', 'wpcf' ) . '</h2>',
        );
        $fields_existing = wpcf_admin_fields_get_fields();
        $fields_check = array();
        $fields_to_be_deleted = array();
        foreach ( $data->fields->field as $field ) {
            $field = (array) $field;
            if ( empty( $field['id'] ) || empty( $field['name'] ) ) {
                continue;
            }
            $form['field-add-' . $field['id']] = array(
                '#type' => 'checkbox',
                '#name' => 'fields[' . $field['id'] . '][add]',
                '#default_value' => true,
                '#title' => '<strong>' . $field['name'] . '</strong>',
                '#inline' => true,
                '#after' => '<br />',
            );
            $fields_check[] = $field['id'];
        }

        foreach ( $fields_existing as $field_id => $field ) {
            if ( !in_array( $field_id, $fields_check ) ) {
                $fields_to_be_deleted['<strong>' . $field['name'] . '</strong>'] = $field['id'];
            }
        }

        if ( !empty( $fields_to_be_deleted ) ) {
            $form['title-fields-deleted'] = array(
                '#type' => 'markup',
                '#markup' => '<h2>' . __( 'Fields to be deleted', 'wpcf' ) . '</h2>',
            );
            $form['fields-deleted'] = array(
                '#type' => 'checkboxes',
                '#name' => 'fields-to-be-deleted',
                '#options' => $fields_to_be_deleted,
            );
        }
    }

    // Check user groups
    if ( !empty( $data->user_groups ) ) {
        $form['title-users'] = array(
            '#type' => 'markup',
            '#markup' => '<h2>' . __( 'User Groups to be added/updated', 'wpcf' ) . '</h2>',
        );
        $groups_check = array();
        foreach ( $data->user_groups->group as $group ) {
            $group = (array) $group;
            $form['user-group-add-' . $group['ID']] = array(
                '#type' => 'checkbox',
                '#name' => 'user_groups[' . $group['ID'] . '][add]',
                '#default_value' => true,
                '#title' => '<strong>' . esc_html( $group['post_title'] ) . '</strong>',
                '#inline' => true,
                '#after' => '<br /><br />',
            );
            $post = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = %s",
                    $group['post_title'],
                    $group['post_type']
                )
            );
            if ( !empty( $post ) ) {
                $form['user-group-add-' . $group['ID']]['#after'] = wpcf_form_simple(
                        array('user-group-add-update-' . $group['ID'] => array(
                                '#type' => 'radios',
                                '#name' => 'user_groups[' . $group['ID'] . '][update]',
                                '#inline' => true,
                                '#options' => array(
                                    __( 'Update', 'wpcf' ) => 'update',
                                    __( 'Create new', 'wpcf' ) => 'add'
                                ),
                                '#default_value' => 'update',
                                '#before' => '<br />',
                                '#after' => '<br />',
                            )
                        )
                );
            }
            $groups_check[] = $group['post_title'];
        }
        $groups_existing = get_posts( 'post_type=wp-types-user-group&post_status=null' );
        if ( !empty( $groups_existing ) ) {
            $groups_to_be_deleted = array();
            foreach ( $groups_existing as $post ) {
                if ( !in_array( $post->post_title, $groups_check ) ) {
                    $groups_to_be_deleted['<strong>' . $post->post_title . '</strong>'] = $post->ID;
                }
            }
            if ( !empty( $groups_to_be_deleted ) ) {
                $form['title-groups-deleted'] = array(
                    '#type' => 'markup',
                    '#markup' => '<h2>' . __( 'Groups to be deleted', 'wpcf' ) . '</h2>',
                );
                $form['user-groups-deleted'] = array(
                    '#type' => 'checkboxes',
                    '#name' => 'user-groups-to-be-deleted',
                    '#options' => $groups_to_be_deleted,
                );
            }
        }
    }

    // Check user fields
    if ( !empty( $data->user_fields ) ) {
        $form['user-title-fields'] = array(
            '#type' => 'markup',
            '#markup' => '<h2>' . __( 'User fields to be added/updated', 'wpcf' ) . '</h2>',
        );
        $fields_existing = wpcf_admin_fields_get_fields( false, false, false, 'wpcf-usermeta' );
        $fields_check = array();
        $fields_to_be_deleted = array();
        foreach ( $data->user_fields->field as $field ) {
            $field = (array) $field;
            if ( empty( $field['id'] ) || empty( $field['name'] ) ) {
                continue;
            }
            $form['user-field-add-' . $field['id']] = array(
                '#type' => 'checkbox',
                '#name' => 'user_fields[' . $field['id'] . '][add]',
                '#default_value' => true,
                '#title' => '<strong>' . $field['name'] . '</strong>',
                '#inline' => true,
                '#after' => '<br />',
            );
            $fields_check[] = $field['id'];
        }

        foreach ( $fields_existing as $field_id => $field ) {
            if ( !in_array( $field_id, $fields_check ) ) {
                $fields_to_be_deleted['<strong>' . $field['name'] . '</strong>'] = $field['id'];
            }
        }

        if ( !empty( $fields_to_be_deleted ) ) {
            $form['user-title-fields-deleted'] = array(
                '#type' => 'markup',
                '#markup' => '<h2>' . __( 'Fields to be deleted', 'wpcf' ) . '</h2>',
            );
            $form['user-fields-deleted'] = array(
                '#type' => 'checkboxes',
                '#name' => 'user-fields-to-be-deleted',
                '#options' => $fields_to_be_deleted,
            );
        }
    }

	// Add form inputs for term meta
	$term_form_additions = wpcf_admin_import_export_settings_for_terms( $data );

	$form = array_merge( $form, $term_form_additions );

    // Check types
    if ( !empty( $data->types ) ) {
        $form['title-types'] = array(
            '#type' => 'markup',
            '#markup' => '<h2>' . __( 'Post Types to be added/updated', 'wpcf' ) . '</h2>',
        );
	    $post_type_option = new Types_Utils_Post_Type_Option();
        $types_existing = $post_type_option->get_post_types();
        $types_check = array();
        $types_to_be_deleted = array();
        foreach ( $data->types->type as $type ) {
            $type = (array) $type;
	        if( in_array( $type['id'], $rfg_post_types ) ) {
		        // Post Type of RFG - will be imported with the related RFG
		        continue;
	        }
            $form['type-add-' . $type['id']] = array(
                '#type' => 'checkbox',
                '#name' => 'types[' . $type['id'] . '][add]',
                '#default_value' => true,
                '#title' => '<strong>' . $type['labels']->name . '</strong>',
                '#inline' => true,
                '#after' => '<br />',
            );
            $types_check[] = $type['id'];
        }

        foreach ( $types_existing as $type_id => $type ) {
	        if( in_array( $type_id, $rfg_post_types ) ) {
		        // Post Type of RFG
		        continue;
	        }

            if ( !in_array( $type_id, $types_check ) ) {
                $types_to_be_deleted['<strong>' . $type['labels']['name'] . '</strong>'] = $type_id;
            }
        }

        if ( !empty( $types_to_be_deleted ) ) {
            $form['title-types-deleted'] = array(
                '#type' => 'markup',
                '#markup' => '<h2>' . __( 'Post Types to be deleted', 'wpcf' ) . '</h2>',
            );
            $form['types-deleted'] = array(
                '#type' => 'checkboxes',
                '#name' => 'types-to-be-deleted',
                '#options' => $types_to_be_deleted,
            );
        }
    }

	// Relationships
	if( ! empty( $data->m2m_relationships ) ) {
		$form['m2m-title'] = array(
			'#type' => 'markup',
			'#markup' => '<h2>' . __( 'Relationships', 'wpcf' ) . '</h2>',
		);

		if ( ! apply_filters( 'toolset_is_m2m_enabled', false ) ) {
			// m2m is not active - user must activate it to import new relationships
			$form['m2m-not-active'] = array(
				'#type' => 'markup',
				'#markup' => '<div class="toolset-alert toolset-alert-error" style="line-height:1.5;">'
				             . sprintf( __( 'The export contains new formatted relationships, but your site has relationships that use the old storage. <a href="%s"><b>Migrate to the new relationship system</b></a> and start the import process again to import the relationships of your export.', 'wpcf' ),
					              admin_url( 'admin.php?page=types-relationships' ) )
				             . '</div>',
			);
		} else {
			// m2m active
			do_action( 'toolset_do_m2m_full_init' );
			$relationship_repository = Toolset_Relationship_Definition_Repository::get_instance();
			foreach( $data->m2m_relationships as $relationships ) {
				$relationships = (array) $relationships;

				foreach( $relationships as $relationship ) {
					$relationship = (array) $relationship;

					if( $relationship['origin'] == 'post_reference_field'
						|| $relationship['origin'] == 'repeatable_group' ) {
						// PRF and RFG relationships will be imported when the PRF / RFG is imported
						continue;
					}

					if( $relationship_repository->get_definition( $relationship['slug'] ) ) {
						// relationship already exist
						$form['m2m-relationship-already-exists-' . $relationship['id'] ] = array(
							'#type' => 'markup',
							'#markup' => '<div><i class="fa fa-check-square-o"></i> '
							             . sprintf( __( '<b>%s</b> already exists.', 'wpcf' ), $relationship['display_name_plural']  )
							             . '</div>',
							'#type' => 'checkbox',
							'#name' => 'm2m-relationships-exists[' . $relationship['id'] . ']',
							'#default_value' => false,
							'#title' => sprintf( __( '<b>%s</b> <i>(already exists)</i>', 'wpcf' ), $relationship['display_name_plural']  ),
							'#inline' => true,
							'#attributes' => array(
									'disabled' => 'disabled',
							),
							'#after' => '<br />',
						);
						continue;
					}

					$form['m2m-relationship-add-' . $relationship['id'] ] = array(
						'#type' => 'checkbox',
						'#name' => 'm2m-relationships[' . $relationship['slug'] . ']',
						'#default_value' => true,
						'#title' => '<strong>' . $relationship['display_name_plural'] . '</strong>',
						'#inline' => true,
						'#after' => '<br />',
					);
				}
			}
		}
	}

    // Check taxonomies
    if ( !empty( $data->taxonomies ) ) {
        $form['title-tax'] = array(
            '#type' => 'markup',
            '#markup' => '<h2>' . __( 'Custom taxonomies to be added/updated', 'wpcf' ) . '</h2>',
        );
        $taxonomies_existing = get_option( WPCF_OPTION_NAME_CUSTOM_TAXONOMIES, array() );
        $taxonomies_check = array();
        $taxonomies_to_be_deleted = array();
        foreach ( $data->taxonomies->taxonomy as $taxonomy ) {
            $taxonomy = (array) $taxonomy;
            $form['taxonomy-add-' . $taxonomy['id']] = array(
                '#type' => 'checkbox',
                '#name' => 'taxonomies[' . $taxonomy['id'] . '][add]',
                '#default_value' => true,
                '#title' => '<strong>' . $taxonomy['labels']->name . '</strong>',
                '#inline' => true,
                '#after' => '<br />',
            );
            $taxonomies_check[] = $taxonomy['id'];
        }

        foreach ( $taxonomies_existing as $taxonomy_id => $taxonomy ) {
            if ( !in_array( $taxonomy_id, $taxonomies_check ) ) {
                $taxonomies_to_be_deleted['<strong>' . $taxonomy['labels']['name'] . '</strong>'] = $taxonomy_id;
            }
        }

        if ( !empty( $taxonomies_to_be_deleted ) ) {
            $form['title-taxonomies-deleted'] = array(
                '#type' => 'markup',
                '#markup' => '<h2>' . __( 'Custom taxonomies to be deleted', 'wpcf' ) . '</h2>',
            );
            $form['taxonomies-deleted'] = array(
                '#type' => 'checkboxes',
                '#name' => 'taxonomies-to-be-deleted',
                '#options' => $taxonomies_to_be_deleted,
            );
        }
    }

    // Check post relationships
    if ( !empty( $data->post_relationships ) ) {
        $form['title-post-relationships'] = array(
            '#type' => 'markup',
            '#markup' => '<h2>' . __( 'Post relationship', 'wpcf' ) . '</h2>',
        );
        $form['pr-add'] = array(
            '#type' => 'checkbox',
            '#name' => 'post_relationship',
            '#default_value' => true,
            '#title' => '<strong>' . __( 'Create relationships', 'wpcf' ) . '</strong>',
            '#inline' => true,
            '#after' => '<br />',
        );
    }

    return $form;
}


/**
 * Add form inputs related to term field group and field definition import.
 *
 * @param SimpleXMLElement $data Import data from XML
 * @return array Enlimbo form elements (yuck).
 * @since 2.1
 */
function wpcf_admin_import_export_settings_for_terms( $data ) {

	$form = array();

	if ( !empty( $data->term_groups ) ) {

		$form['title-terms'] = array(
			'#type' => 'markup',
			'#markup' => '<h2>' . __( 'Term field groups to be added or updated', 'wpcf' ) . '</h2>',
		);

		$group_factory = Toolset_Field_Group_Term_Factory::get_instance();

		$groups_check = array();
		foreach ( $data->term_groups->group as $group ) {
			$group = (array) $group;

			$group_id = $group['ID'];
			$group_slug = $group['post_title'];

			$form[ 'term-group-add-' . $group_id ] = array(
				'#type' => 'checkbox',
				'#name' => 'term_groups[' . $group_id . '][add]',
				'#default_value' => true,
				'#title' => '<strong>' . esc_html( $group_slug ) . '</strong>',
				'#inline' => true,
				'#after' => '<br /><br />',
			);

			$existing_groups = $group_factory->query_groups(
				array(
					'name' => $group_slug,
					'post_type' => $group['post_type']
				)
			);

			$group_already_exists = ( count( $existing_groups ) > 0 );

			if ( $group_already_exists ) {
				$form[ 'term-group-add-' . $group_id ]['#after'] = wpcf_form_simple(
					array(
						'term-group-add-update-' . $group_id => array(
							'#type' => 'radios',
							'#name' => 'term_groups[' . $group_id . '][update]',
							'#inline' => true,
							'#options' => array(
								__( 'Update', 'wpcf' ) => 'update',
								__( 'Create new', 'wpcf' ) => 'add'
							),
							'#default_value' => 'update',
							'#before' => '<br />',
							'#after' => '<br />',
						)
					)
				);
			}
			$groups_check[] = $group_slug;
		}

		$groups_existing = get_posts( array( 'post_type' => Toolset_Field_Group_Term::POST_TYPE, 'post_status' => null ) );

		if ( !empty( $groups_existing ) ) {
			$groups_to_be_deleted = array();
			foreach ( $groups_existing as $post ) {
				if ( !in_array( $post->post_title, $groups_check ) ) {
					$groups_to_be_deleted['<strong>' . $post->post_title . '</strong>'] = $post->ID;
				}
			}
			if ( !empty( $groups_to_be_deleted ) ) {
				$form['title-term-groups-deleted'] = array(
					'#type' => 'markup',
					'#markup' => '<h2>' . __( 'Term groups to be deleted', 'wpcf' ) . '</h2>',
				);
				$form['term-groups-deleted'] = array(
					'#type' => 'checkboxes',
					'#name' => 'term-groups-to-be-deleted',
					'#options' => $groups_to_be_deleted,
				);
			}
		}
	}

	// Check term fields
	if ( !empty( $data->term_fields ) ) {
		$form['term-title-fields'] = array(
			'#type' => 'markup',
			'#markup' => '<h2>' . __( 'Term fields to be added/updated', 'wpcf' ) . '</h2>',
		);
		$fields_existing = wpcf_admin_fields_get_fields( false, false, false, Toolset_Field_Definition_Factory_Term::FIELD_DEFINITIONS_OPTION );
		$fields_check = array();
		$fields_to_be_deleted = array();
		foreach ( $data->term_fields->field as $field ) {
			$field = (array) $field;
			if ( empty( $field['id'] ) || empty( $field['name'] ) ) {
				continue;
			}
			$form['term-field-add-' . $field['id']] = array(
				'#type' => 'checkbox',
				'#name' => 'term_fields[' . $field['id'] . '][add]',
				'#default_value' => true,
				'#title' => '<strong>' . $field['name'] . '</strong>',
				'#inline' => true,
				'#after' => '<br />',
			);
			$fields_check[] = $field['id'];
		}

		foreach ( $fields_existing as $field_id => $field ) {
			if ( !in_array( $field_id, $fields_check ) ) {
				$fields_to_be_deleted['<strong>' . $field['name'] . '</strong>'] = $field['id'];
			}
		}

		if ( !empty( $fields_to_be_deleted ) ) {
			$form['term-title-fields-deleted'] = array(
				'#type' => 'markup',
				'#markup' => '<h2>' . __( 'Term ields to be deleted', 'wpcf' ) . '</h2>',
			);
			$form['term-fields-deleted'] = array(
				'#type' => 'checkboxes',
				'#name' => 'term-fields-to-be-deleted',
				'#options' => $fields_to_be_deleted,
			);
		}
	}


	return $form;

}

/**
 * Exports data to XML.
 */
function wpcf_admin_export_data($download = true)
{
    /**
     *
     * Since Types 1.2
     * Merged function with Module Manager
     * /embedded/includes/module-manager.php
     * wpcf_admin_export_selected_data( array $items, $_type = 'all', $return = 'download' )
     *
     */
    $return = $download ? 'download' : 'xml';
    return wpcf_admin_export_selected_data( array(), 'all', $return );
}

/**
 * Check upload dir.
 *
 * @return type
 */
function wpcf_admin_import_dir()
{
    $dir = get_temp_dir();
    return is_writable( $dir );
}
