<?php

/**
 * format_helper - Used to format raw database output (object) to formats such 
 * as json, arrays, etc
 *
 * @package		alohamora
 * @author		University of the East Research and Development Unit
 * @author              Daryll Santos, <daryll.santos@gmail.com>
 * @copyright           Copyright (c) 2013
 * @license		http://opensource.org/licenses/mit-license.php
 * @link		https://www.facebook.com/ueccssrnd
 */
function toJSON($object) {
    return json_encode($object, 1);
}

/* End of file format_helper.php */